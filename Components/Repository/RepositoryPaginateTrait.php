<?php
/**
 * Components/RepositoryPaginateTrait.php
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

namespace BryanHenry\MetrifyBundle\Components\Repository;

trait RepositoryPaginateTrait {

    /**
     * Get paginated records.
     *
     * @param int $start
     * @param int $limit
     *
     * @return array
     *
     */
    public function getCollection(int $start, ?int $limit) : array
    {

        // Create query builder, and set offset.
        $QueryBuilder = $this->createQueryBuilder('paginate')->setFirstResult($start);

        // Set limit if passed.
        if($limit !== null) {

            $QueryBuilder->setMaxResults($limit);
        }

        // Return results.
        return $QueryBuilder->getQuery()->getResult();

    }

    /**
     * Get the number of records.
     *
     * @param int $start
     * @param int $limit
     *
     * @return int
     *
     */
    public function getCollectionCount() : int
    {

        try {

            // Count number of rows.
            return $this->createQueryBuilder('paginate')->select('count(table_alias.id)')->getQuery()->getSingleScalarResult();

        } catch(\Doctrine\ORM\NoResultException $Exception) {

            return 0;
        }

    }

}