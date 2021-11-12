<?php

namespace WabLab\Library\Sorting\Contracts;

use phpDocumentor\Reflection\Types\Static_;

interface ISorter
{
    const ORDER_DESCENDING = 'desc';
    const ORDER_ASCENDING = 'asc';

    public function sort(array $array, IComparer $comparer, string $order = self::ORDER_ASCENDING, $preserveKeys = false):array;
}