<?php

namespace WabLab\Library\Sorting\Tests\Integration;

use WabLab\Library\Sorting\SelectionSort;

class SelectionSortTest extends AbstractIntegration
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct(new SelectionSort(), $name, $data, $dataName);
    }

}