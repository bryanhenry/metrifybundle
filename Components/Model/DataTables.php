<?php
/**
 * Model/DataTables.php
 *
 * Copyright 2017 Bryan Henry
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author  Bryan Henry <bryan@misterflow.com>
 * @since   File available since Release 1.0.0
 *
 */

namespace BryanHenry\MetrifyBundle\Components\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("none")
 */
class DataTables {

    /**
     * The draw sequence.
     *
     * @var int
     */
    protected $draw;

    /**
     * The total number of records.
     *
     * @var int
     */
    protected $recordsTotal;

    /**
     * The total number of records filtered from the total.
     *
     * @var int
     */
    protected $recordsFiltered;

    /**
     * The collection of items.
     *
     * @var array
     */
    protected $data;

    public function __construct(array $collection = [], int $draw = 1, int $filtered = 0, int $total = 0)
    {

        $this->setData($collection);
        $this->setDraw($draw);
        $this->setRecordsFiltered($filtered);
        $this->setRecordsTotal($total);

    }

    /**
     * Get the draw sequence.
     *
     * @return int
     *
     */
    public function getDraw() : int
    {
        return $this->draw;
    }

    /**
     * Get the total number of records.
     *
     * @return int
     *
     */
    public function getRecordsTotal() : int
    {
        return $this->recordsTotal;
    }

    /**
     * Get the total number of records filtered out.
     *
     * @return int
     *
     */
    public function getRecordsFiltered() : int
    {
        return $this->recordsFiltered;
    }

    /**
     * Get the collection of items.
     *
     * @return array
     *
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Set the draw sequence.
     *
     * @param int $draw
     *
     * @return DataTables
     *
     */
    public function setDraw(int $draw) : DataTables
    {
        $this->draw = $draw;

        return $this;

    }

    /**
     * Set the total number of records.
     *
     * @param int $recordsTotal
     *
     * @return DataTables
     *
     */
    public function setRecordsTotal(int $recordsTotal) : DataTables
    {
        $this->recordsTotal = $recordsTotal;

        return $this;
    }

    /**
     * Set the total number of records filtered out.
     *
     * @param int $recordsFiltered
     *
     * @return DataTables
     *
     */
    public function setRecordsFiltered(int $recordsFiltered) : DataTables
    {
        $this->recordsFiltered = $recordsFiltered;

        return $this;

    }

    /**
     * Set the collection of items.
     *
     * @param array $data
     *
     * @return DataTables
     */
    public function setData(array $data) : DataTables
    {

        $this->data = $data;

        return $this;
    }

}