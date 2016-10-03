<?php

namespace Module7\AjaxToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Base class to work with AjaxViews
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 */
class AjaxViewController extends Controller
{
    use AjaxViewControllerTrait;
}