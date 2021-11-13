<?php

namespace WabLab\Library\Sorting\Tests\Integration;

use WabLab\Library\Sorting\MergeSort;

class MergeSortTest extends AbstractIntegration
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct(new MergeSort(), $name, $data, $dataName);
    }

}