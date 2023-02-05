<?php

namespace Shisa\Sofi\Doctrine\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Shisa\Sofi\DataObjects\Pagination\Pagination;
use Shisa\Sofi\DataObjects\Pagination\PaginationResult;
use Shisa\Sofi\DataObjects\Pagination\TakeSkipPagination;

class PaginationService
{
    private Pagination $defaultPagination;

    public function __construct(
        private EntityManager $em,
        ?Pagination $defaultPagination = null
    ) {
        $this->defaultPagination = $defaultPagination
            ?? new TakeSkipPagination();
    }

    /**
     * @param Expr\OrderBy[] $orderBys
     */
    public function paginate(
        QueryBuilder $qb,
        $orderBys = [],
        $joins = [],
        $selects = [],
        $fetchModes = [],
        Pagination $pagination = null
    ): PaginationResult {
        $pagination = $pagination ?? $this->defaultPagination;
        $result = new PaginationResult();
        $result->pagination = $pagination;

        // Get the queried entity
        /**@var Expr\From */
        $from = $qb->getDQLPart('from')[0];
        $entity = $from->getFrom();
        $alias = $from->getAlias();
        $pk = $this->em->getClassMetadata($entity)
            ->getSingleIdentifierColumnName();

        // Order the original query builder
        foreach ($orderBys as $idx => $orderBy) {
            $qb = $qb->add('orderBy', $orderBy, !!$idx);
        }

        // Set up pagination
        $qb = $qb->setFirstResult($pagination->getSkip())
            ->setMaxResults($pagination->getTake())
            ->select("$alias.$pk");

        // Get result according to identities of entity
        $paginator = new Paginator($qb);
        $result->total = $paginator->setUseOutputWalkers(false)->count();
        if (!$result->total) {
            return $result;
        }
        $pks = $paginator->getQuery()->getSingleColumnResult();

        // Load by pks
        $result->dataset = $this->getDatasetByPks(
            $entity,
            $pks,
            $orderBys,
            $joins,
            $selects,
            $fetchModes
        );
        return $result;
    }

    /**
     * @param Expr\OrderBy[] $orderBys
     */
    public function getDatasetByPks(
        string $entity,
        array $pks,
        $orderBys = [],
        $joins = [],
        $selects = [],
        $fetchModes = []
    ) {
        $alias = '__pagination_entity__';
        $pk = $this->em->getClassMetadata($entity)
            ->getSingleIdentifierColumnName();

        $qb = $this->em->createQueryBuilder();
        // from
        $qb = $qb->add('from', new Expr\From($entity, $alias), true);
        // where
        $qb = $qb->andWhere($qb->expr()->in("$alias.$pk", ':pks'))
            ->setParameter('pks', $pks);

        // join
        foreach ($joins as $k => $v) {
            $qb = $qb->leftJoin($k, $v);
        }

        // order by
        foreach ($orderBys as $idx => $orderBy) {
            $qb = $qb->add('orderBy', $orderBy, !!$idx);
        }

        // select
        if (!$selects) {
            $selects = array_values($joins);
        }
        if (!in_array($alias, $selects)) {
            $selects[] = $alias;
        }
        $qb = $qb->select($selects);

        // fetch mode
        $query = $qb->getQuery();
        foreach ($fetchModes as $fetchMode) {
            $query = $query->setFetchMode(...$fetchMode);
        }

        // execute
        return $query->execute();
    }
}
