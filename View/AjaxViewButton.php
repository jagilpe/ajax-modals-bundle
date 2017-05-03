<?php

namespace Jagilpe\AjaxModalsBundle\View;

use Jagilpe\AjaxModalsBundle\View\AjaxViewButtonInterface;

/**
 * Represents a button in a AjaxView
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
class AjaxViewButton implements AjaxViewButtonInterface
{
    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var boolean
     */
    protected $showButton = true;

    /**
     *
     * @var string
     */
    protected $label;

    /**
     *
     * @var boolean
     */
    protected $opensNewForm = false;

    /**
     *
     * @var string
     */
    protected $url;

    public function __construct($buttonName)
    {
        $this->name = $buttonName;
        $this->label = $buttonName;
    }

    /**
     * {@inheritDoc}
     * @see \Jagilpe\AjaxModalsBundle\View\AjaxViewButtonInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Jagilpe\AjaxModalsBundle\View\AjaxViewButtonInterface::getButtonSpecification()
     */
    public function getButtonSpecification()
    {
        $specification = array(
            'show' => $this->showButton,
            'label' => $this->label,
            'new_form' => $this->opensNewForm,
            'url' => $this->url,
        );

        return $specification;
    }

    /**
     * Returns the label of the button
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the label of the button
     *
     * @param string $label
     *
     * @return AjaxViewButton
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Returns the url for the form submission
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url for the form submission
     *
     * @param string $url
     *
     * @return AjaxViewButton
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Sets if the button should be show
     *
     * @param boolean $showButton
     *
     * @return AjaxViewButton
     */
    public function showButton($showButton = true)
    {
        $this->showButton = $showButton;
        return $this;
    }


    /**
     * Sets if after the button is clicked another form should be opened
     *
     * @param boolean $opensNewForm
     *
     * @return AjaxViewButton
     */
    public function opensNewForm($opensNewForm = true)
    {
        $this->opensNewForm = $opensNewForm;
        return $this;
    }
}