<?php

namespace WabLab\Library\Sorting;

use WabLab\Library\Sorting\Contracts\IComparator;
use WabLab\Library\Sorting\Contracts\ISorter;

class BubbleSort implements ISorter
{

    public function sort(array $array, IComparator $comparator, string $order = self::ORDER_ASCENDING, $preserveKeys = false): array
    {
        $arrayKeys = [];
        $arrayValues = [];
        $count = 0;
        foreach($array as $key => $value) {
            if($preserveKeys)
                $arrayKeys[] = $key;
            $arrayValues[] = $value;
            $count++;
        }

        $maxValueIndex = $count - 1;
        do {
            $swapped = false;
            for($seeker = 1; $seeker <= $maxValueIndex; $seeker++) {
                if( ($order == static::ORDER_ASCENDING && $comparator->compare($arrayValues[$seeker-1], $arrayValues[$seeker]) > 0) || ($order == static::ORDER_DESCENDING && $comparator->compare($arrayValues[$seeker-1], $arrayValues[$seeker]) < 0)) {
                    [$arrayValues[$seeker-1], $arrayValues[$seeker]] = [$arrayValues[$seeker], $arrayValues[$seeker-1]];
                    if($preserveKeys)
                        [$arrayKeys[$seeker-1], $arrayKeys[$seeker]] = [$arrayKeys[$seeker], $arrayKeys[$seeker-1]];
                    $swapped = true;
                }
            }
            $maxValueIndex--;
        } while($swapped);

        if($preserveKeys)
            return array_combine($arrayKeys, $arrayValues);
        return $arrayValues;
    }
}


