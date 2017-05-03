<?php

namespace Jagilpe\AjaxModalsBundle\View;

use Jagilpe\AjaxModalsBundle\Exception\AjaxModalsException;

/**
 * Factory class for the AjaxViews
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
class AjaxViewFactory
{
    /**
     * Creates an AjaxView of the given type
     *
     * @param string $viewType
     *
     * @return AjaxViewInterface
     */
    public function createView($viewType)
    {
        if (class_exists($viewType)) {
            $reflectionClass = new \ReflectionClass($viewType);

            if ($reflectionClass->implementsInterface(AjaxViewInterface::class)) {
                return $reflectionClass->newInstance();
            }
        }

        throw new AjaxModalsException("View type $viewType does not exists.");
    }
}