<?php

namespace WabLab\Library\Sorting\Tests\Integration;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use WabLab\Library\Sorting\Contracts\IComparer;
use WabLab\Library\Sorting\Contracts\ISorter;

abstract class AbstractIntegration extends TestCase
{
    protected ISorter $sorter;
    protected IComparer $comparer;

    public function __construct(ISorter $sorter, ?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->sorter = $sorter;

        $this->comparer = $this->getMockBuilder(IComparer::class)->getMock();
        $this->comparer->method('compare')->willReturnCallback(function ($left, $right) {
            return $left > $right ? 1 : ($right > $left ? -1 : 0);
        });
    }

    public function testSortingEmptyArray_ASCOrder_NoKeyPreservation()
    {
        $array = [];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_ASCENDING, false);
        $this->assertCount(0, $array);
    }
    public function testSortingEmptyArray_DESCOrder_NoKeyPreservation()
    {
        $array = [];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING, false);
        $this->assertCount(0, $array);
    }
    public function testSortingEmptyArray_ASCOrder_KeyPreservation()
    {
        $array = [];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_ASCENDING, true);
        $this->assertCount(0, $array);
    }

    public function testSortingEmptyArray_DESCOrder_KeyPreservation()
    {
        $array = [];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING, true);
        $this->assertCount(0, $array);
    }


    public function testSortingArray_OneItem_ASCOrder_NoKeyPreservation()
    {
        $array = [525];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_ASCENDING, false);
        $this->assertCount(1, $array);
        $this->assertEquals(525, $array[0]);
    }

    public function testSortingArray_OneItem_DESCOrder_NoKeyPreservation()
    {
        $array = [525];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING, false);
        $this->assertCount(1, $array);
        $this->assertEquals(525, $array[0]);
    }

    public function testSortingArray_OneItem_ASCOrder_KeyPreservation()
    {
        $array = [525];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_ASCENDING, true);
        $this->assertCount(1, $array);
        $this->assertEquals(525, $array[0]);
    }

    public function testSortingArray_OneItem_DESCOrder_KeyPreservation()
    {
        $array = [525];
        $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING, true);
        $this->assertCount(1, $array);
        $this->assertEquals(525, $array[0]);
    }

    public function testSortingArray_TwoItems_Numeric_ASCOrder_NoKeyPreservation()
    {
        $array = [2, 1];
        $sortedArray = $this->sorter->sort($array, $this->comparer, ISorter::ORDER_ASCENDING, false);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(1, $sortedArray[0]);
        $this->assertEquals(2, $sortedArray[1]);

    }

    public function testSortingArray_TwoItems_Numeric_ASCOrder_KeyPreservation()
    {
        $array = [2, 1];
        $sortedArray = $this->sorter->sort($array, $this->comparer, ISorter::ORDER_ASCENDING, true);
        $this->assertArrayAscSorted($sortedArray);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(2, $sortedArray[0]);
        $this->assertEquals(1, $sortedArray[1]);

    }

    public function testSortingArray_TwoItems_Numeric_DESCOrder()
    {
        $array = [2, 1];

        //
        // no key preserving
        //
        $sortedArray = $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING, false);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(2, $sortedArray[0]);
        $this->assertEquals(1, $sortedArray[1]);

        //
        // preserve keys
        //
        $sortedArray = $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING, true);
        $this->assertArrayDescSorted($sortedArray);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(2, $sortedArray[0]);
        $this->assertEquals(1, $sortedArray[1]);

    }


    public function testSortingArray_TwoItems_Associative()
    {
        $assocArray = ['key1' => 1, 'key2'=> 2];

        //
        // no key preserving
        //
        $sortedArray = $this->sorter->sort($assocArray, $this->comparer, ISorter::ORDER_ASCENDING, false);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(1, $sortedArray[0]);
        $this->assertEquals(2, $sortedArray[1]);

        $sortedArray = $this->sorter->sort($assocArray, $this->comparer, ISorter::ORDER_DESCENDING, false);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(2, $sortedArray[0]);
        $this->assertEquals(1, $sortedArray[1]);

        //
        // preserve keys
        //
        $sortedArray = $this->sorter->sort($assocArray, $this->comparer, ISorter::ORDER_ASCENDING, true);
        $this->assertArrayAscSorted($sortedArray);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(1, $sortedArray['key1']);
        $this->assertEquals(2, $sortedArray['key2']);

        $sortedArray = $this->sorter->sort($assocArray, $this->comparer, ISorter::ORDER_DESCENDING, true);
        $this->assertArrayDescSorted($sortedArray);
        $this->assertCount(2, $sortedArray);
        $this->assertEquals(1, $sortedArray['key1']);
        $this->assertEquals(2, $sortedArray['key2']);

    }

    public function testBestCaseSorting_ManyItems() {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        // ascending
        $sortedArray = $this->sorter->sort($array, $this->comparer);
        $this->assertArrayAscSorted($sortedArray);
        $this->assertCount(9, $sortedArray);

        // descending
        $sortedArray = $this->sorter->sort(array_reverse($array), $this->comparer, ISorter::ORDER_DESCENDING);
        $this->assertArrayDescSorted($sortedArray);
        $this->assertCount(9, $sortedArray);
    }

    public function testWorstCaseSorting_ManyItems() {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        // descending
        $sortedArray = $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING);
        $this->assertArrayDescSorted($sortedArray);
        $this->assertCount(9, $sortedArray);

        // ascending
        $sortedArray = $this->sorter->sort(array_reverse($array), $this->comparer);
        $this->assertArrayAscSorted($sortedArray);
        $this->assertCount(9, $sortedArray);

    }

    public function testNormalCaseSorting_ManyItems() {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        shuffle($array);

        // ascending
        $sortedArray = $this->sorter->sort($array, $this->comparer);
        $this->assertArrayAscSorted($sortedArray);
        $this->assertCount(9, $sortedArray);

        // descending
        $sortedArray = $this->sorter->sort($array, $this->comparer, ISorter::ORDER_DESCENDING);
        $this->assertArrayDescSorted($sortedArray);
        $this->assertCount(9, $sortedArray);
    }

    protected function assertArrayAscSorted(array $array)
    {
        $preItem = null;
        foreach ($array as $item) {
            if($preItem && $this->comparer->compare($preItem, $item) > 0) {
                throw new ExpectationFailedException('Failed to assert that array elements are sorted ascending.');
            }
            $preItem = $item;
        }
        $this->assertTrue(true, 'Array elements are sorted ascending');
    }

    protected function assertArrayDescSorted(array $array)
    {
        $preItem = null;
        foreach ($array as $item) {
            if($preItem && $this->comparer->compare($preItem, $item) < 0) {
                throw new ExpectationFailedException('Failed to assert that array elements are sorted descending.');
            }
            $preItem = $item;
        }
        $this->assertTrue(true, 'Array elements are sorted descending');
    }

}