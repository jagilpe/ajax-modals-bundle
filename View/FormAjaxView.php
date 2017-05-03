<?php

namespace Jagilpe\AjaxModalsBundle\View;

use Jagilpe\AjaxModalsBundle\View\AjaxViewInterface;
use Jagilpe\AjaxModalsBundle\Exception\AjaxModalsException;

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
            throw new AjaxModalsException("Button $buttonName does not exists.");
        }
    }
}