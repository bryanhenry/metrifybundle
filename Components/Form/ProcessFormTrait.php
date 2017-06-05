<?php
/**
 * Components/Form/ProcessFormTrait.php
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

namespace BryanHenry\MetrifyBundle\Components\Form;

use BryanHenry\MetrifyBundle\Components\Entity\EntityInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

trait ProcessFormTrait {

    /**
     * Process forms via rest controller generically.
     *
     * @param Request $Request
     * @param FormTypeInterface $FormType
     * @param EntityInterface $Entity
     *
     * @return View
     *
     */
    public function processForm(Request $Request, string $form, EntityInterface $Entity) : View
    {

        /**
         * Instantiate the form.
         *
         * @var FormTypeInterface $Form
         */
        $Form = $this->createForm($form, $Entity);

        // Mine the data out of the request and submit it to the form.
        $Form->submit(json_decode($Request->getContent(), true));

        /**
         * @var boolean $create
         */
        $create = $Entity->getId() == null;

        // Validate.
        if ($Form->isSubmitted() && $Form->isValid()) {

            /**
             * Get the entity manager.
             *
             * @var EntityManager $EntityManager
             */
            $EntityManager = $this->getDoctrine()->getManager();

            // Persist if valid and return a success.
            $EntityManager->persist($Entity);
            $EntityManager->flush();

            // Return 200 or 201 success code.
            return $this->view(null, ($create) ? 201 : 200);

        } else {

            // Return 400 bad request.
            return $this->view($Form, 400);

        }
    }

}