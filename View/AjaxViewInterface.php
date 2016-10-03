<?php

namespace Module7\AjaxToolsBundle\View;

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

    /**
     * Returns the response data to be sent as a json object
     *
     * @return array
     */
    public function getResponse();
}