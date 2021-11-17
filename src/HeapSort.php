<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparator;
use WabLab\Library\Sorting\Contracts\ISorter;

class HeapSort implements ISorter
{

    public function sort(array $array, IComparator $comparator, string $order = self::ORDER_ASCENDING, $preserveKeys = false): array
    {
        $arrayKeys = [];
        $arrayValues = [];
        $count = 0;
        foreach ($array as $key => $value) {
            if ($preserveKeys)
                $arrayKeys[] = $key;
            $arrayValues[] = $value;
            $count++;
        }
        if(!$preserveKeys)
            $arrayKeys = null;

        for($consideredCount = $count; $consideredCount > 0; $consideredCount--) {
            for( $lastParentInx = floor($consideredCount / 2) - 1; $lastParentInx >=0; $lastParentInx--) {
                $this->heapify($arrayValues, $consideredCount, $lastParentInx, $comparator, $order, $arrayKeys);
            }
            [$arrayValues[0], $arrayValues[$consideredCount-1]] = [$arrayValues[$consideredCount-1], $arrayValues[0]];
            if($preserveKeys)
                [$arrayKeys[0], $arrayKeys[$consideredCount-1]] = [$arrayKeys[$consideredCount-1], $arrayKeys[0]];
        }

        if ($preserveKeys)
            return array_combine($arrayKeys, $arrayValues);
        return $arrayValues;
    }

    public function heapify(array &$arrayValues, int $count, int $currentInx, IComparator $comparator, string $order, ?array &$arrayKeys = null)
    {
        $largestInx = $currentInx;
        $leftInx = 2 * $largestInx + 1;
        $rightInx = 2 * $largestInx + 2;

        if( $leftInx < $count && $this->canSwap($order, $comparator, $arrayValues, $leftInx, $largestInx)) {
            $largestInx = $leftInx;
        }

        if( $rightInx < $count && $this->canSwap($order, $comparator, $arrayValues, $rightInx, $largestInx) ) {
            $largestInx = $rightInx;
        }

        if($largestInx != $currentInx) {
            [$arrayValues[$currentInx], $arrayValues[$largestInx]] = [$arrayValues[$largestInx], $arrayValues[$currentInx]];
            if($arrayKeys)
                [$arrayKeys[$currentInx], $arrayKeys[$largestInx]] = [$arrayKeys[$largestInx], $arrayKeys[$currentInx]];

            $this->heapify($arrayValues, $count, $largestInx, $comparator, $order, $arrayKeys);
        }
    }

    protected function canSwap(string $order, IComparator $comparator, array &$arrayValues, float|int $currentInx, int $largestInx): bool
    {
        return ($this->canSwapAscending($order, $comparator, $arrayValues, $currentInx, $largestInx) || $this->canSwapDescending($order, $comparator, $arrayValues, $currentInx, $largestInx));
    }

    protected function canSwapAscending(string $order, IComparator $comparator, array &$arrayValues, float|int $currentInx, int $largestInx): bool
    {
        return ($order == static::ORDER_ASCENDING && $comparator->compare($arrayValues[$currentInx], $arrayValues[$largestInx]) > 0);
    }

    protected function canSwapDescending(string $order, IComparator $comparator, array &$arrayValues, float|int $currentInx, int $largestInx): bool
    {
        return ($order == static::ORDER_DESCENDING && $comparator->compare($arrayValues[$currentInx], $arrayValues[$largestInx]) < 0);
    }

}