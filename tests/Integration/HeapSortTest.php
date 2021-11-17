<?php

namespace WabLab\Library\Sorting\Tests\Integration;

use WabLab\Library\Sorting\HeapSort;

class HeapSortTest extends AbstractIntegration
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct(new HeapSort(), $name, $data, $dataName);
    }

}