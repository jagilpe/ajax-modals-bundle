<?php

namespace Module7\AjaxToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Module7\AjaxToolsBundle\View\AjaxViewInterface;

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
        $ajaxViewsFactory = $this->get('m7_ajax_tools.ajax_views.factory');

        return $ajaxViewsFactory->createView($viewType);
    }
}