<?php
/**
 * Components/Controller/AbstractCrudController.php
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

namespace BryanHenry\MetrifyBundle\Components\Controller;

use BryanHenry\MetrifyBundle\Components\Entity\FormEntityInterface;
use BryanHenry\MetrifyBundle\Components\Form\Exceptions\FormEntityInterfaceNotImplementedException;
use BryanHenry\MetrifyBundle\Components\Form\ProcessFormTrait;
use BryanHenry\MetrifyBundle\Components\Model\DataTables;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCrudController extends FOSRestController {

    use ProcessFormTrait;

    /**
     * Returns the class name of the form used to processed CRUD.
     *
     * @return string
     *
     */
    abstract protected function getFormName() : string;

    /**
     * Returns the class name of the entity used when processing crud.
     *
     * @return string
     *
     */
    abstract protected function getEntityName(): string;

    /**
     * Returns an the set entity after checking its interface type.
     *
     * @throws FormEntityInterfaceNotImplementedException
     *
     * @return FormEntityInterface
     *
     */
    private function getEntity() : FormEntityInterface
    {

        // Get the class name.
        $class = $this->getEntityName();

        $Entity = new $class();

        // Check to make sure its implementing our interface.
        if(!$Entity instanceof FormEntityInterface) {

            throw new FormEntityInterfaceNotImplementedException();

        } else {

            return $Entity;
        }

    }

    /**
     * Get the repository for the entity.
     *
     * @return EntityRepository
     *
     */
    protected function getRepository() : EntityRepository
    {

        return $this->getDoctrine()->getManager()->getRepository($this->getEntityName());

    }

    /**
     * Create entity.
     *
     * @param Request $Request
     *
     * @return View
     *
     */
    public function postAction(Request $Request) : View
    {

        // Process the request.
        return $this->processForm($Request, $this->getFormName(), $this->getEntity());

    }

    /**
     * Attempt to look up an existing item.
     *
     * @param int $id
     *
     * @return View
     *
     */
    public function getAction(int $id) : View
    {

        // Look for entity.
        $Entity = $this->getRepository()->findOne($id);

        if($Entity !== null) {

            return $this->view($Entity, 200);

        } else {

            // Return not found.
            return $this->view(null, 404);

        }

    }

    /**
     * Get collection of entities.
     *
     * @QueryParam(name="start", default="1", nullable=true)
     * @QueryParam(name="start", default="0", nullable=true)
     * @QueryParam(name="length", default="10", nullable=true)
     * @QueryParam(name="datatables", default=1, nullable=true)

     * @param Request $Request
     *
     * @return View
     *
     */
    public function cgetAction(ParamFetcher $ParamFetcher) : View
    {

        if(!$ParamFetcher->get('datatables')) {

            return $this->view($this->getRepository()->findAll(), 200);

        } else {

            /**
             * Total number of records in collection.
             *
             * @var int $total_count
             */
            $total_count = $this->getRepository()->getCollectionCount();

            /**
             * Paginated collection of entities from persistence.
             *
             * @var array $collection
             */
            $collection = $this->getRepository()->getCollection($ParamFetcher->get('start'), $ParamFetcher->get('length'));

            // Instantiate datatables object to be serialized.
            return $this->view(new DataTables($collection, $ParamFetcher->get('draw'), 0, $total_count), 200);

        }

    }

    /**
     * Update entity.
     *
     * @param int $id
     *
     * @return View
     *
     */
    public function putAction(Request $Request, int $id) : View
    {

        // Look for entity.
        $Entity = $this->getRepository()->findOne($id);

        // If entity exists, attempt to update.
        if($Entity !== null) {

            // Process the request.
            return $this->processForm($Request, $this->getFormName(), $Entity);

        } else {

            // Return not found.
            return $this->view(null, 404);

        }

    }

    /**
     * Delete entity.
     *
     * @param int $id
     *
     * @return View
     *
     */
    public function deleteAction(int $id) : View
    {

        // Look for entity.
        $Entity = $this->getRepository()->findOne($id);

        // If entity exists, attempt to remove.
        if($Entity !== null) {

            $EntityManager = $this->getDoctrine()->getManager();
            $EntityManager->remove($Entity);
            $EntityManager->flush($Entity);

            // Return success.
            return $this->view(null, 204);

        } else {

            // Return not found.
            return $this->view(null, 404);

        }

    }

}