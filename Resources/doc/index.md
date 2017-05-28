Documentation
=============

This bundle was built with the main goal of working with forms with Ajax, loading and handling them using the 
Bootstrap 3 Modal component.

# Introduction

The operation of the bundled can be divided in two:

* The frontend logic that is responsible for loading, handling and closing the dialog in the browser.
* The backend logic that is responsible for building the content of the dialog and treating the data sent from the frontend.

# Frontend logic

## Basic usage

To open an ajax dialog simply bind the jQuery plugin `jgpModalDialog` to any element in your page. This element must have
an attribute called `data-src` with the url from where the initial content of the dialog can be loaded from (more on this
later in the Backend logic point).

In your page template include the trigger element/s:
```twig
<div id="my-modal-trigger-1" class="modal-trigger" data-src="{{ path(route_to_dialog_1_content) }}">Open the dialog 1</div>
<div id="my-modal-trigger-2" class="modal-trigger" data-src="{{ path(route_to_dialog_2_content) }}">Open the dialog 2</div>
<div id="my-modal-trigger-3" class="modal-trigger" data-src="{{ path(route_to_dialog_3_content) }}">Open the dialog 3</div>
```

In your javascript code bind the jQuery plugin to the trigger elements:

```javascript
// Bind the plugin element by element
$("#my-modal-trigger-1").jgpModalDialog();
$("#my-modal-trigger-2").jgpModalDialog();
$("#my-modal-trigger-3").jgpModalDialog();

// Or bind all at the same time
$(".modal-trigger").jgpModalDialog();
```

Now when the user clicks on the trigger element, the plugin will make a request to the url specified in the data-src
attribute, will load the received content in the modal container and will open the bootstrap modal with this content.
From this moment the modal will be directed by the responses received by the plugin from the server.

## Event callbacks

The jQuery plugin accepts an options object with which you can define different callback functions that will be called
as response to different events of the dialog.

```javascript
$("#my-modal-trigger-1").jgpModalDialog({
    onNewFormLoad: function(form) {
        // This callback will be called each time a new form is loaded in the dialog (not only the first time)
    },
    onSave: function() {
        // This callback is called when the modal is closed after the user has clicked save
    },
    onDelete: function() {
        // This callback is called when the modal is closed after the user has clicked delete
    }
});
```

# Backend logic
 
The way we work with the ajax dialogs is the same as we would work with an standard Symfony form. The main difference is
that instead of returning a `Symfony\Component\HttpFoundation\Response` we have to return an instance of 
`Jagilpe\AjaxModalsBundle\View\AjaxViewInterface`.

## Generating the initial content of the dialog

For the initial content that will be included when the dialog is opened we simply write the controller for the route 
we included in the trigger element.

```php
<?php
// src/AppBundle/Controller/MyAjaxController.php
namespace AppBundle\Controller;

use Jagilpe\AjaxModalsBundle\Controller\AjaxViewControllerTrait;
use Jagilpe\AjaxModalsBundle\View\FormAjaxView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyAjaxController extends Controller
{
    // This trait will add a factory method for creating the views
    use AjaxViewControllerTrait;
    
    /**
     * @Route("/my-ajax-dialog", name="my_ajax_dialog")
     */
    public function myAjaxDialogAction(Request $request)
    {
        // Build the form as we would usually build it using the Symfony Form API
        $form = $this->createFormBuilder()
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->getForm();

        // Create the form ajax view
        $view = $this->createAjaxView(FormAjaxView::class);
        $view->setTitle("Title of the dialog");
        $view->setContent(
            $this->renderView(':ajax_views:my-ajax-dialog.html.twig', array('form' => $form,))
        );
        // Configure the save button
        $view->getButton(FormAjaxView::BUTTON_SAVE)
            ->showButton()
            ->setUrl($this->get('router')->generate('my_ajax_dialog'));
        
        // Return the view
        return $view;
    }
}

```

## Handling the form data

Once again we will handle the form data as we usually would, with the exception that in the end we have to return an 
AjaxViewInterface instance.

```php
<?php
// src/AppBundle/Controller/MyAjaxController.php
namespace AppBundle\Controller;

use Jagilpe\AjaxModalsBundle\Controller\AjaxViewControllerTrait;
use Jagilpe\AjaxModalsBundle\View\EndAjaxView;
use Jagilpe\AjaxModalsBundle\View\ErrorAjaxView;
use Jagilpe\AjaxModalsBundle\View\FormAjaxView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyAjaxController extends Controller
{
    use AjaxViewControllerTrait;

    /**
     * @Route("/my-ajax-dialog", name="my_ajax_dialog")
     */
    public function myAjaxDialogAction(Request $request)
    {
        // Build the form as we would usually build it using the Symfony Form API
        $form = $this->createFormBuilder()
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->getForm();

        // Handle and check the form as usual
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                // Make something with the data
                // ...

                // This view will close the ajax dialog
                $view = $this->createAjaxView(EndAjaxView::class);

            } catch(\Exception $exception) {
                // We have to inform the dialog that there was an error
                $view = $this->createAjaxView(ErrorAjaxView::class);
                $view->setErrorFromException($exception);
            }

        } else {
            // Create the form ajax view
            $view = $this->createAjaxView(FormAjaxView::class);
            $view->setTitle("Title of the dialog");
            $view->setContent(
                $this->renderView(':ajax_views:my-ajax-dialog.html.twig', array('form' => $form->createView(),))
            );
            // Configure the save button
            $view->getButton(FormAjaxView::BUTTON_SAVE)
                ->showButton()
                ->setUrl($this->get('router')->generate('my_ajax_dialog'));
        }

        // Return the view
        return $view;
    }
}
```