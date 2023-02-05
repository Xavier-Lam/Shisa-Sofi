<?php

namespace Shisa\Sofi\DataObjects\Pagination;

class TakeSkipPagination extends Pagination
{
    /**
     * Skip n records
     */
    public int $skip = 0;

    /**
     * Take next n records
     */
    public int $take = 30;

    public function getSkip(): int
    {
        return $this->skip;
    }

    public function getTake(): int
    {
        return $this->take;
    }
}
