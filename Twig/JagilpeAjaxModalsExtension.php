<?php

namespace Jagilpe\AjaxModalsBundle\Twig;

/**
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 */
class JagilpeAjaxModalsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * {@inheritDoc}
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'jagilpe_ajax_modals_bundle_extension';
    }

    /**
     * {@inheritDoc}
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        $functions = array();
        $functions[] = new \Twig_SimpleFunction(
            'jgp_modal_container',
            array($this, 'renderModalContainer'),
            array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )
        );

        return $functions;
    }

    /**
     * Returns the html structure required by all the modal functionality
     *
     * @return string
     */
    public function renderModalContainer(\Twig_Environment $environment, array $options = array())
    {
        return $environment->render('JagilpeAjaxModalsBundle:Modal:jgp_modal_dialog.html.twig', $options);
    }
}