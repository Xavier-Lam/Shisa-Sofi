<?php

namespace Shisa\Sofi\DataObjects\Pagination;

class TakeSkipPagination extends Pagination
{
    /**
     * Current page, start from 1.
     */
    public int $page = 1;

    /**
     * Records per page
     */
    public int $perPage = 30;

    public function getSkip(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function getTake(): int
    {
        return $this->perPage;
    }
}
