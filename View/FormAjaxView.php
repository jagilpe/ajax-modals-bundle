<?php

namespace Module7\AjaxToolsBundle\View;

use Module7\AjaxToolsBundle\View\AjaxViewInterface;
use Module7\AjaxToolsBundle\Exception\AjaxToolsException;

class FormAjaxView extends ContentAjaxView
{
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
}