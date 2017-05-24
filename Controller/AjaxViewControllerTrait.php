<?php

namespace Jagilpe\AjaxModalsBundle\Controller;

use Jagilpe\AjaxModalsBundle\View\AjaxViewInterface;

/**
 * Base trait to work with Ajax View in a controller
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 */
trait AjaxViewControllerTrait
{
    /**
     * Creates an Ajax View of the given type
     *
     * @param string $viewType
     *
     * @return AjaxViewInterface
     */
    protected function createAjaxView($viewType)
    {
        $ajaxViewsFactory = $this->get('jgp_ajax_modals.ajax_views.factory');

        return $ajaxViewsFactory->createView($viewType);
    }
}