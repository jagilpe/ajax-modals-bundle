<?php

namespace Module7\AjaxToolsBundle\View;

use Module7\AjaxToolsBundle\View\AjaxViewInterface;
use Module7\AjaxToolsBundle\Exception\AjaxToolsException;

class FormAjaxView implements AjaxViewInterface
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

    public function __construct()
    {
        // Set the default buttons
        $saveButton = new AjaxViewButton(self::BUTTON_SAVE);
        $saveButton->showButton(false);

        $deleteButton = new AjaxViewButton(self::BUTTON_DELETE);
        $deleteButton->showButton(false);

        $this->addButton($saveButton);
        $this->addButton($deleteButton);
    }

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
     * Returns a determined button to be able to configure it
     *
     * @param string $buttonName
     *
     * @return AjaxViewButton
     */
    public function getButton($buttonName)
    {
        if (isset($this->buttons[$buttonName])) {
            return $this->buttons[$buttonName];
        }
        else {
            throw new AjaxToolsException("Button $buttonName does not exists.");
        }
    }

    /**
     * Returns the specification of the buttons for the AjaxView
     *
     * @return array
     */
    public function getButtonsSpecification()
    {
        $specification = array();

        foreach ($this->buttons as $button) {
            $specification[$button->getName()] = $button->getButtonSpecification();
        }

        return $specification;
    }

    private function addButton(AjaxViewButtonInterface $button)
    {
        $this->buttons[$button->getName()] = $button;
    }
}