<?php

namespace WabLab\Library\Sorting\Contracts;

interface ISorter
{
    const ORDER_DESCENDING = 'desc';
    const ORDER_ASCENDING = 'asc';

    public function sort(array $array, IComparator $comparator, string $order = self::ORDER_ASCENDING, $preserveKeys = false):array;
}