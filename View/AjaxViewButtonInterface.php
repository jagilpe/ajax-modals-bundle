<?php

namespace Jagilpe\AjaxModalsBundle\View;

/**
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
interface AjaxViewButtonInterface
{
    /**
     * Returns the name of the button
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the button specification to be used in the AjaxView
     *
     * @return array
     */
    public function getButtonSpecification();
}