<?php

namespace Shisa\Sofi\DataObjects\Pagination;

class PaginationResult
{
    /**
     * The requested pagination parameters
     */
    public Pagination $pagination;

    /**
     * The result dataset
     */
    public array $dataset = [];

    /**
     * Total records count
     */
    public int $total = 0;
}
