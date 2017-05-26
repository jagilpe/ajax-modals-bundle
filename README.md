AjaxModalsBundle
====

AjaxModalsBundle is a Symfony Bundle for generating ajax form dialogs using Bootstrap Modal API.

# Installation

You can install the bundle using composer:

```bash
composer require jagilpe/ajax-modals-bundle
```

or add the package to your composer.json file directly.

To enable the bundle, you just have to register the bundle in your AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Jagilpe\AjaxModalsBundle\JagilpeAjaxModalsBundle(),
    // ...
);
```

You have also to include in the page template the container in which the modals will be loaded. For this simply include 
the `jgp_modal_container` twig function at the end of the body of your page, directly under the body tag.

```twig
<html>
    <head>
        <!-- Head content -->
    </head>
    <body>
        <!-- Body content -->
        {{ jgp_modal_container() }}        
    </body>
</html>
```

Finally you have to include the provided javascript file somewhere in your base template. 
If you use assetic to manage the assets:

```twig
{% block javascripts %}
    {{ parent() }}
    {% javascripts
        'bundles/jagilpeajaxmodals/js/jgp-modal-dialog.js' %}
        <script src="{{ asset_url }}"></script>
    {% endjavascripts %}
{% endblock %}
```
This javascript depends on jQuery and Bootstrap 3 modal, so you have to load it somewhere in the template before this file.
You have also to load the Bootstrap CSS in your page.

# Documentation

You can read the documentation of the usage of the bundle [here](Resources/doc/index.md)

# API Reference

https://api.gilpereda.com/ajax-modals-bundle/master/