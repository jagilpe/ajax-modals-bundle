<?php

namespace Jagilpe\AjaxModalsBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;
use Jagilpe\AjaxModalsBundle\View\AjaxViewInterface;

/**
 * Generates the right Response object from the AjaxView in Ajax Controllers
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
class AjaxViewControllerEventListener
{
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        if (!$controllerResult instanceof AjaxViewInterface) {
            return;
        }

        $view = $controllerResult;

        $response = new Response(json_encode($view->getResponse()));
        $response->headers->set('Content-Type', 'application/json');

        $event->setResponse($response);
    }
}