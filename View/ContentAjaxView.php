<?php

namespace Module7\AjaxToolsBundle\View;

use Module7\AjaxToolsBundle\View\AjaxViewInterface;
use Module7\AjaxToolsBundle\Exception\AjaxToolsException;

abstract class ContentAjaxView implements AjaxViewInterface
{
    const BUTTON_SAVE = 'save';
    const BUTTON_DELETE = 'delete';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var array
     */
    protected $buttons = array();

    /**
     *
     * {@inheritDoc}
     * @see \Module7\AjaxToolsBundle\View\AjaxViewInterface::getResponse()
     */
    public function getResponse()
    {
        $response = array(
            'title' => $this->getTitle(),
            'type' => AjaxViewInterface::TYPE_FORM,
            'response' => $this->getContent(),
            'buttons' => $this->getButtonsSpecification(),
        );

        return $response;
    }

    /**
     * Returns the title of the view
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the view
     *
     * @param string $title
     * @return \Module7\AjaxToolsBundle\View\ContentAjaxView
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the content of the View
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of the view
     *
     * @param string $content
     * @return \Module7\AjaxToolsBundle\View\ContentAjaxView
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns the specification of the buttons for the AjaxView
     *
     * @return array
     */
    protected function getButtonsSpecification()
    {
        $specification = array();

        foreach ($this->buttons as $button) {
            $specification[$button->getName()] = $button->getButtonSpecification();
        }

        return $specification;
    }

    protected function addButton(AjaxViewButtonInterface $button)
    {
        $this->buttons[$button->getName()] = $button;
    }
}