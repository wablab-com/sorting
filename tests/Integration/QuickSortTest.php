<?php

namespace WabLab\Library\Sorting\Tests\Integration;

use WabLab\Library\Sorting\QuickSort;

class QuickSortTest extends AbstractIntegration
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct(new QuickSort(), $name, $data, $dataName);
    }

}