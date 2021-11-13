<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparer;
use WabLab\Library\Sorting\Contracts\ISorter;

class MergeSort implements ISorter
{

    public function sort(array $array, IComparer $comparer, string $order = self::ORDER_ASCENDING, $preserveKeys = false): array
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

        [$sortedKeys, $sortedValues] = $this->mergeSort($arrayValues, $comparer, $order, $preserveKeys ? $arrayKeys : null);

        if ($preserveKeys)
            return array_combine($sortedKeys, $sortedValues);
        return $sortedValues;
    }

    protected function mergeSort(array $arrayValues, IComparer $comparer, string $order, ?array $arrayKeys = null): array
    {
        $elementsCount = count($arrayValues);

        if ($elementsCount < 2) {
            return [$arrayKeys, $arrayValues];
        }

        $halfSize = floor($elementsCount / 2);
        $leftValues = array_splice($arrayValues, 0, $halfSize);
        if ($arrayKeys)
            $leftKeys = array_splice($arrayKeys, 0, $halfSize);

        [$leftSortedKeys, $leftSortedValues] = $this->mergeSort($leftValues, $comparer, $order, $leftKeys ?? null);
        [$rightSortedKeys, $rightSortedValues] = $this->mergeSort($arrayValues, $comparer, $order, $arrayKeys);

        return $this->mergeTwoSortedArrays($leftSortedValues, $rightSortedValues, $comparer, $order, $leftSortedKeys, $rightSortedKeys);
    }

    protected function mergeTwoSortedArrays(array $leftValues, array $rightValues, IComparer $comparer, string $order, ?array $leftKeys = null, ?array $rightKeys = null): array
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

            } else if ( ($order == static::ORDER_ASCENDING && $comparer->compare($leftValues[$leftInx], $rightValues[$rightInx]) <= 0) || ($order == static::ORDER_DESCENDING && $comparer->compare($leftValues[$leftInx], $rightValues[$rightInx]) > 0)) {
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


