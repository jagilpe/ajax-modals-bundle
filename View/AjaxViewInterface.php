<?php

namespace Jagilpe\AjaxModalsBundle\View;

/**
 * Defines an interface for the AjaxView reponses
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 */
interface AjaxViewInterface
{
    const TYPE_END = 'end';
    const TYPE_FORM = 'form';
    const TYPE_ERROR = 'error';
    const TYPE_PAGE_RELOAD = 'reload';
    const TYPE_PAGE_REDIRECT = 'redirect';

    /**
     * Returns the response data to be sent as a json object
     *
     * @return array
     */
    public function getResponse();
}