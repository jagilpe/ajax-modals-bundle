<?php

namespace Module7\AjaxToolsBundle\View;

/**
 * Defines a view to use as a response in an Ajax Controller
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
class PageReloadAjaxView implements AjaxViewInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Module7\AjaxToolsBundle\View\AjaxViewInterface::getResponse()
     */
    public function getResponse()
    {
        $response = array(
            'type' => AjaxViewInterface::TYPE_PAGE_RELOAD,
            'response' => 0,
        );

        return $response;
    }
}