<?php

namespace WabLab\Library\Sorting\Contracts;

interface IComparator
{
    /**
     * @param mixed $left
     * @param mixed $right
     * @return int Returns < 0 if left is less than right; > 0 if left is greater than right, and 0 if they are equal.
     */
    public function compare(mixed $left, mixed $right):int;
}