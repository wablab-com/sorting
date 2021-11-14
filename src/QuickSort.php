<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparator;
use WabLab\Library\Sorting\Contracts\ISorter;

class QuickSort implements ISorter
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

        $this->quickSort($arrayValues, 0, $count-1, $comparator, $order, $arrayKeys);

        if($preserveKeys)
            return array_combine($arrayKeys, $arrayValues);
        return $arrayValues;
    }

    protected function quickSort(array &$arrayValues, int $fromIndex, int $toIndex, IComparator $comparator, string $order, ?array &$arrayKeys = null)
    {
        if ($fromIndex < $toIndex) {
            $pivotIndex = $this->setPivot_MoveMaxAndMin_ToLeftAndRight($arrayValues, $fromIndex, $toIndex, $comparator, $order, $arrayKeys);

            $this->quickSort($arrayValues, $fromIndex, $pivotIndex - 1, $comparator, $order, $arrayKeys);
            $this->quickSort($arrayValues, $pivotIndex + 1, $toIndex, $comparator, $order, $arrayKeys);
        }
    }

    protected function setPivot_MoveMaxAndMin_ToLeftAndRight(array &$arrayValues, int $fromIndex, int $toIndex, IComparator $comparator, string $order, ?array &$arrayKeys = null): int
    {
        $pivotIndex = $toIndex;
        $projectedPivotIndex = $fromIndex - 1;
        for ($seeker = $fromIndex; $seeker < $toIndex; $seeker++) {
            if ( ($order == static::ORDER_ASCENDING && $comparator->compare($arrayValues[$seeker], $arrayValues[$pivotIndex]) < 0) || ($order == static::ORDER_DESCENDING && $comparator->compare($arrayValues[$seeker], $arrayValues[$pivotIndex]) > 0)) {
                $projectedPivotIndex++;
                [$arrayValues[$seeker], $arrayValues[$projectedPivotIndex]] = [$arrayValues[$projectedPivotIndex], $arrayValues[$seeker]];
                if($arrayKeys)
                    [$arrayKeys[$seeker], $arrayKeys[$projectedPivotIndex]] = [$arrayKeys[$projectedPivotIndex], $arrayKeys[$seeker]];

            }
        }
        $projectedPivotIndex++;
        [$arrayValues[$pivotIndex], $arrayValues[$projectedPivotIndex]] = [$arrayValues[$projectedPivotIndex], $arrayValues[$pivotIndex]];
        if($arrayKeys)
            [$arrayKeys[$pivotIndex], $arrayKeys[$projectedPivotIndex]] = [$arrayKeys[$projectedPivotIndex], $arrayKeys[$pivotIndex]];

        return $projectedPivotIndex;
    }
}


