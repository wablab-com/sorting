<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparator;
use WabLab\Library\Sorting\Contracts\ISorter;

class SelectionSort implements ISorter
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

        for ($mainInx = 0; $mainInx < $count; $mainInx++) {
            $lowInx = $mainInx;
            for ($seeker = $mainInx + 1; $seeker < $count; $seeker++) {
                if (($order == static::ORDER_ASCENDING && $comparator->compare($arrayValues[$lowInx], $arrayValues[$seeker]) > 0) || ($order == static::ORDER_DESCENDING && $comparator->compare($arrayValues[$lowInx], $arrayValues[$seeker]) < 0)) {
                    $lowInx = $seeker;
                }
            }

            [$arrayValues[$lowInx], $arrayValues[$mainInx]] = [$arrayValues[$mainInx], $arrayValues[$lowInx]];
            if ($preserveKeys)
                [$arrayKeys[$lowInx], $arrayKeys[$mainInx]] = [$arrayKeys[$mainInx], $arrayKeys[$lowInx]];
        }

        if ($preserveKeys)
            return array_combine($arrayKeys, $arrayValues);
        return $arrayValues;
    }

}