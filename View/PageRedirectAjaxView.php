<?php

namespace Module7\AjaxToolsBundle\View;

/**
 * Defines a view to use as a response in an Ajax Controller
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
class PageRedirectAjaxView implements AjaxViewInterface
{
    /**
     *
     * @var string
     */
    private $redirectUrl;

    public function __construct($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Module7\AjaxToolsBundle\View\AjaxViewInterface::getResponse()
     */
    public function getResponse()
    {
        $response = array(
            'type' => AjaxViewInterface::TYPE_PAGE_REDIRECT,
            'url' => $this->redirectUrl,
            'response' => 0,
        );

        return $response;
    }
}