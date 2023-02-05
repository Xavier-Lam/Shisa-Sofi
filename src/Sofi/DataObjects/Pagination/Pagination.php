<?php

namespace Shisa\Sofi\DataObjects\Pagination;

abstract class Pagination
{
    abstract function getSkip(): int;

    abstract function getTake(): int;
}
