<?php

namespace WabLab\Library\Sorting\Tests\Integration;

use WabLab\Library\Sorting\BubbleSort;

class InsertionSortTest extends AbstractIntegration
{

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct(new BubbleSort(), $name, $data, $dataName);
    }


}