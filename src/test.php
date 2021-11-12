<?php

require_once __DIR__.'/../vendor/autoload.php';

use WabLab\Library\Sorting\BubbleSort;
use WabLab\Library\Sorting\Contracts\IComparer;

class BasicComparer implements IComparer
{

    public function compare(mixed $left, mixed $right): int
    {
        if($left > $right) {
            return 1;
        } elseif($left < $right) {
            return -1;
        }
        return 0;
    }
}

$sort = new BubbleSort();
$sorted = $sort->sort([5,3,7,9,2,1,9,0,4,6,4], new BasicComparer(), \WabLab\Library\Sorting\Contracts\ISorter::ORDER_DESCENDING, true);
print_r($sorted);



