<?php

namespace Module7\AjaxToolsBundle\View;

use Module7\AjaxToolsBundle\View\ContentAjaxView;

class FeedbackAjaxView extends ContentAjaxView
{
    public function __construct($message)
    {
        // Set the default buttons
        $saveButton = new AjaxViewButton(self::BUTTON_SAVE);
        $saveButton->showButton(false);

        $deleteButton = new AjaxViewButton(self::BUTTON_DELETE);
        $deleteButton->showButton(false);

        $this->addButton($saveButton);
        $this->addButton($deleteButton);

        $this->setContent($message);
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
}