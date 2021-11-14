<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparator;
use WabLab\Library\Sorting\Contracts\ISorter;

class InsertionSort implements ISorter
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

        for ($seeker = 0; $seeker < $count; $seeker++) {
            $currentValue = $arrayValues[$seeker];
            if ($preserveKeys)
                $currentKey = $arrayKeys[$seeker];
            $pairIndex = $seeker - 1;

            while ($pairIndex >= 0 && (($order == static::ORDER_ASCENDING && $comparator->compare($arrayValues[$pairIndex], $currentValue) > 0) || ($order == static::ORDER_DESCENDING && $comparator->compare($arrayValues[$pairIndex], $currentValue) < 0))) {
                $arrayValues[$pairIndex + 1] = $arrayValues[$pairIndex];
                if ($preserveKeys)
                    $arrayKeys[$pairIndex + 1] = $arrayKeys[$pairIndex];
                $pairIndex--;
            }
            $arrayValues[$pairIndex + 1] = $currentValue;
            if ($preserveKeys)
                $arrayKeys[$pairIndex + 1] = $currentKey;
        }

        if ($preserveKeys)
            return array_combine($arrayKeys, $arrayValues);
        return $arrayValues;
    }
}


