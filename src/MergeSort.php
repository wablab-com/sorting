<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparator;
use WabLab\Library\Sorting\Contracts\ISorter;

class MergeSort implements ISorter
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

        [$sortedKeys, $sortedValues] = $this->mergeSort($arrayValues, $comparator, $order, $preserveKeys ? $arrayKeys : null);

        if ($preserveKeys)
            return array_combine($sortedKeys, $sortedValues);
        return $sortedValues;
    }

    protected function mergeSort(array $arrayValues, IComparator $comparator, string $order, ?array $arrayKeys = null): array
    {
        $elementsCount = count($arrayValues);

        if ($elementsCount < 2) {
            return [$arrayKeys, $arrayValues];
        }

        $halfSize = floor($elementsCount / 2);
        $leftValues = array_splice($arrayValues, 0, $halfSize);
        if ($arrayKeys)
            $leftKeys = array_splice($arrayKeys, 0, $halfSize);

        [$leftSortedKeys, $leftSortedValues] = $this->mergeSort($leftValues, $comparator, $order, $leftKeys ?? null);
        [$rightSortedKeys, $rightSortedValues] = $this->mergeSort($arrayValues, $comparator, $order, $arrayKeys);

        return $this->mergeTwoSortedArrays($leftSortedValues, $rightSortedValues, $comparator, $order, $leftSortedKeys, $rightSortedKeys);
    }

    protected function mergeTwoSortedArrays(array $leftValues, array $rightValues, IComparator $comparator, string $order, ?array $leftKeys = null, ?array $rightKeys = null): array
    {
        $sortedValues = [];
        $sortedKeys = [];
        $leftSize = count($leftValues);
        $rightSize = count($rightValues);
        $leftInx = 0;
        $rightInx = 0;
        while ($leftInx < $leftSize || $rightInx < $rightSize) {
            if ($leftInx == $leftSize) {
                if ($rightKeys)
                    $sortedKeys[] = $rightKeys[$rightInx];
                $sortedValues[] = $rightValues[$rightInx++];

            } else if ($rightInx == $rightSize) {
                if ($leftKeys)
                    $sortedKeys[] = $leftKeys[$leftInx];
                $sortedValues[] = $leftValues[$leftInx++];

            } else if ( ($order == static::ORDER_ASCENDING && $comparator->compare($leftValues[$leftInx], $rightValues[$rightInx]) <= 0) || ($order == static::ORDER_DESCENDING && $comparator->compare($leftValues[$leftInx], $rightValues[$rightInx]) > 0)) {
                if ($leftKeys)
                    $sortedKeys[] = $leftKeys[$leftInx];
                $sortedValues[] = $leftValues[$leftInx++];

            } else {
                if ($rightKeys)
                    $sortedKeys[] = $rightKeys[$rightInx];
                $sortedValues[] = $rightValues[$rightInx++];
            }
        }

        return [$sortedKeys, $sortedValues];
    }
}


