;
(function($, window, document, undefined) {
    var pluginName = 'jgpModalDialog',
        dataKey = 'plugin_' + pluginName;

    var Plugin = function(element, options) {
        this.element = element;
        this.options = {
            modalsParkContainerSel: '#jgp-modal-dialogs-park-container',
            modalContainerSel: $.fn[pluginName].defaults.modalContainerSel,
            embededCollectionSel: '.jgp-embeded-entity-collection',
            embededSelectSel: '.jgp-embeded-entity-select',
            onReload: function() {},
            onNewFormLoad: function(form) {},
            onSave: function() {},
            onDelete: function() {}
        };

        this.init(options);
    };

    Plugin.prototype = {
        init: function(options) {
            $.extend(this.options, options);
            var plugin = this;
            var $element = $(this.element);
            this.modalsParkContainer = $(this.options.modalsParkContainerSel);
            this.modalContainer = $(this.options.modalContainerSel);
            this.modalTitle = this.modalContainer.find('.modal-title span.anchorjs-icon');
            this.modalBody = this.modalContainer.find('.modal-body');
            this.modalSaveButton = this.modalContainer.find('#jgp-modal-dialog-save-submit');
            this.modalDeleteButton = this.modalContainer.find('#jgp-modal-dialog-delete-submit');
            this.modalCancelButton = this.modalContainer.find('#jgp-modal-dialog-cancel-submit');
            this.modalClasses = new Array();
            var formType = $element.attr('data-form');
            formType = typeof formType !== 'undefined' ? formType : 'save';
            this.dialogDataOrig = {
                buttons: {
                    save: {
                        label: 'save',
                        new_form: false,
                        show: true,
                        url: ''
                    },
                    delete: {
                        label: 'delete',
                        new_form: true,
                        show: false,
                        url: ''
                    },
                    cancel: {
                        label: 'cancel',
                        new_form: true,
                        show: true,
                        url: ''
                    }
                },
                formType: formType,
                callbacks: {
                    save: function() {
                        plugin._sendForm('save', plugin.options.onSave);
                    },
                    'delete': function() {
                        plugin._sendForm('delete', plugin.options.onDelete);
                    },
                    'cancel': function() {
                        plugin._hideModal();
                    }
                },
                title: this.modalTitle.text()
            };

            this.dialogDataOrig.buttons[formType].url = $element.attr('data-src');

            this.dialogData = $.extend({}, this.dialogDataOrig);

            this.parkedDialogs = new Array();

            // Bind the click event to the element
            $element.click(function() {
                // First we reset the dialogs containers
                plugin._resetDialog();

                // Load the first form
                plugin._loadForm($element.attr('data-src'));
            });
        },

        _initializeEmbededCollections: function(container) {
            var plugin = this;
            $(container).find(this.options.embededCollectionSel).each(function() {
                $(this).ehEmbededEntityCollection({
                    onAddElement: function(formData) {
                        plugin._onLoadEmbededFormCallback(formData);
                    },
                    onEditElement: function(formData) {
                        plugin._onLoadEmbededFormCallback(formData);
                    },
                    onNewFormLoad: plugin.options.onNewFormLoad
                });
            });
            $(container).find(this.options.embededSelectSel).each(function() {
                $(this).ehEmbededEntitySelect({
                    onAddElement: function(formData) {
                        plugin._onLoadEmbededFormCallback(formData);
                    },
                    onEditElement: function(formData) {
                        plugin._onLoadEmbededFormCallback(formData);
                    },
                    onNewFormLoad: plugin.options.onNewFormLoad,
                    onSelectElement: function() {
                        plugin._unparkDialog();
                    }
                });
            });
        },

        _onLoadEmbededFormCallback: function(formData) {
            var plugin = this;

            // First we have to park the current form
            this._parkDialog();

            // set the functions
            this.dialogData = {
                callbacks: {
                    save: function() {
                        if (typeof plugin.dialogData.buttons.save.url !== 'undefined' &&
                            plugin.dialogData.buttons.save.url !== '') {
                            plugin._sendForm('save', function(result) {
                                if (!result.error) {
                                    var elementResult = {
                                        response: result.data,
                                        element: plugin.modalBody.children(),
                                    };
                                    if (formData.onSave(elementResult)) {
                                        plugin._unparkDialog();
                                    }
                                }
                            });
                        } else {
                            var elementResult = {
                                element: plugin.modalBody.children(),
                            }
                            if (formData.onSave(elementResult)) {
                                plugin._unparkDialog();
                            }
                        }
                    },
                    delete: function() {
                        var elementResult = {
                            element: plugin.modalBody.children(),
                        };

                        if (formData.onDelete(elementResult)) {
                            plugin._unparkDialog();
                        }
                    },
                    cancel: function() {
                        formData.onCancel(plugin.modalBody.children());
                        plugin._unparkDialog();
                    }
                }
            }

            // Set the buttons info
            this.dialogData.buttons = formData.buttons;

            // Set the title of the dialog
            this.dialogData.title = formData.dialogTitle;

            // Update the buttons status
            this._updateButtonsStatus();

            // Update the dialog title
            this._setModalTitle(this.dialogData.title);

            // Initialize entity collections
            this._initializeEmbededCollections(formData.form);

            // Now load the form
            this._loadEmbededForm(formData);
        },

        _loadEmbededForm: function(formData) {
            this.modalBody.append(formData.form);
        },

        _createModalContainer: function(id) {
            var modalContainer = this.modalsPrototype.clone();
            modalContainer.attr('id', id);
            modalContainer.appentTo(this.modalsContainer);

            return modalContainer;
        },

        _resetDialog: function() {
            var plugin = this;

            this.dialogData = $.extend({}, this.dialogDataOrig);
            // Bind the click event to the buttons elements
            this.modalSaveButton.unbind('click');
            this.modalSaveButton.click(function() {
                plugin.dialogData.callbacks.save();
            });

            this.modalDeleteButton.unbind('click');
            this.modalDeleteButton.click(function() {
                plugin.dialogData.callbacks.delete();
            });

            this.modalCancelButton.unbind('click');
            this.modalCancelButton.click(function() {
                plugin.dialogData.callbacks.cancel();
            });

            // Bind the after hide event
            this.modalContainer.unbind('hidden.bs.modal');
            this.modalContainer.on('hidden.bs.modal', function(event) {

            });

            this.modalsParkContainer.empty();
            this.parkedDialogs = new Array();
            this.modalBody.empty();
        },

        _showModal: function() {
            var plugin = this;
            this.modalContainer.modal({
                backdrop: 'static',
                show: true,
                keyboard: false
            });

            $.fn[pluginName].defaults.onKeypressHandler = function(event) {
                if (event.keyCode == 27) {
                    // When the user presses escape we click the cancel button
                    plugin.modalCancelButton.click();
                }
                else if (event.keyCode == 13) {
                    event.stopPropagation();
                    // The user has pressed enter

                    if (plugin.dialogData.buttons.save.show) {
                        plugin.modalSaveButton.click();
                    }
                    else if (plugin.dialogData.buttons.delete.show) {
                        plugin.modalDeleteButton.click();
                    }
                    else {
                        plugin.modalCancelButton.click();
                    }
                    return false;
                }
            };
        },

        _hideModal: function() {
            this.modalContainer.modal('hide');
            this._updateClasses(new Array());
            $(this.modalContainer).unbind('keyup');
        },

        _loadForm: function(formUrl) {
            $.ajax({
                url: formUrl,
                context: this,
                success: function(response) {
                    this._loadContent(response);
                }
            });
        },

        _updateButtonsStatus: function() {
            for (button in this.dialogData.buttons) {
                var buttonValue = this.dialogData.buttons[button];
                var buttonName = 'modal' + button.charAt(0).toUpperCase() + button.slice(1) + 'Button';

                // Set the button label if applies
                if (typeof buttonValue.label !== 'undefined') {
                    this[buttonName].empty();
                    this[buttonName].append(buttonValue.label);
                }

                if (buttonValue.show) {
                    this[buttonName].show();
                } else {
                    this[buttonName].hide();
                }
            }
        },

        _loadContent: function(response) {
            this.modalBody.empty()
            this.modalBody.append(response.response);
            this._bindKeyEvents(this.modalBody);
            this._setModalTitle(response.title);

            this.dialogData.buttons = response.buttons;
            this.dialogData.title = response.title;
            if (typeof response.form_type !== 'undefined') {
                this.dialogData.form_type = response.form_type;
            }

            this._updateButtonsStatus();

            // Initialize entity collections
            this._initializeEmbededCollections(this.modalBody);

            // Call the new form load callback
            this.options.onNewFormLoad(this.modalBody);

            if (typeof response.classes !== 'undefined') {
                this._updateClasses(response.classes);
            }

            this._showModal();
        },

        _updateClasses: function(classes) {
            // First we have to remove the current classes
            var plugin = this;
            this.modalClasses.forEach(function(cssClass) {
                $(plugin.modalContainer).removeClass(cssClass);
            });

            this.modalClasses = classes;
            this.modalClasses.forEach(function(cssClass) {
                $(plugin.modalContainer).addClass(cssClass);
            });
        },

        _parkDialog: function() {
            var dialogToPark = this.modalBody.children();
            dialogToPark.appendTo(this.modalsParkContainer);

            var data = {
                dialog: dialogToPark,
                dialogData: this.dialogData
            };

            this.parkedDialogs.push(data);
        },

        _unparkDialog: function() {
            var data = this.parkedDialogs.pop();

            if (typeof data !== 'undefined') {
                this.modalBody.empty();
                this.modalBody.append(data.dialog);
                this.dialogData = data.dialogData;

                // Update buttons state
                this._updateButtonsStatus();

                // Update the dialog title
                this._setModalTitle(this.dialogData.title);
            }
        },

        _setModalTitle: function(title) {
            this.modalTitle.empty();
            this.modalTitle.append(title);
        },

        _sendForm: function(type, resultCallback) {
            var form = this.modalBody.find("form");
            var plugin = this;

            var formUrl = this.dialogData.buttons[type].url;

            if (this.dialogData.buttons[type].new_form) {
                this.dialogData.formType = type;

                var requestType = 'GET';
                var data = "";

                this._ajaxRequest(formUrl, requestType, data, type);
            } else {
                this.dialogData.formType = type;

                var requestType = "POST";

                var data = new FormData(form[0]);
                this._ajaxRequest(formUrl, requestType, data, type, resultCallback);
            }

        },

        _ajaxRequest: function(formUrl, requestType, data, type, resultCallback) {
            $.ajax({
                url: formUrl,
                type: requestType,
                data: data,
                contentType: false,
                processData: false,
                context: this,
                success: function(response) {
                    switch (response['type']) {
                        case 'form':
                            // We have a form to render
                            this._loadContent(response);
                            break;
                        case 'result':
                            if (typeof resultCallback !== 'undefined') {
                                var result = {
                                    error: false,
                                    data: response
                                };
                                resultCallback(result);
                            }
                            break;
                        case 'reload':
                            window.location.reload();
                            break;
                        case 'redirect':
                            if (typeof response['url'] !== 'undefined') {
                                window.location.replace(response['url']);
                            }
                            break;
                        case 'end':
                            this._hideModal();
                            if (typeof resultCallback !== 'undefined') {
                                var result = {
                                    error: false,
                                    data: response
                                };
                                resultCallback(result);
                            }
                            break;
                    }

                },
                error: function() {
                    if (typeof resultCallback !== 'undefined') {
                        var result = {
                            error: true
                        };

                        resultCallback(result);
                    }
                }
            });
        },

        _bindKeyEvents: function(element) {
            var plugin = this;
            $(element).find('input').keypress(function(event) {
                if (event.charCode == 13) {
                    plugin.modalSaveButton.click();
                    event.stopPropagation();
                }
            });
        }

    };

    $.fn[pluginName] = function(options) {
        var args = arguments;

        if (options === undefined || typeof options === 'object') {
            // Creates a new plugin instance, for each selected element, and
            // stores a reference withint the element's data
            return this.each(function() {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin(this,
                        options));
                }
            });
        } else if (typeof options === 'string' && options[0] !== '_' &&
            options !== 'init') {
            // Call a public pluguin method (not starting with an
            // underscore) for each
            // selected element.
            if (Array.prototype.slice.call(args, 1).length == 0 &&
                $.inArray(options, $.fn[pluginName].getters) != -1) {
                // If the user does not pass any arguments and the method
                // allows to
                // work as a getter then break the chainability so we can
                // return a value
                // instead the element reference.
                var instance = $.data(this[0], 'plugin_' + pluginName);
                return instance[options].apply(instance, Array.prototype.slice
                    .call(args, 1));
            } else {
                // Invoke the speficied method on each selected element
                return this.each(function() {
                    var instance = $.data(this, 'plugin_' + pluginName);
                    if (instance instanceof Plugin &&
                        typeof instance[options] === 'function') {
                        instance[options].apply(instance, Array.prototype.slice
                            .call(args, 1));
                    }
                });
            }
        }
    };

    // Bind the key events for the modal container
    // this has to be done once, because all instances share the same modal container
    $.fn[pluginName].defaults = {
        modalContainerSel: '#jgp-modal-dialogs-modal',
        onKeypressHandler : function(event) {}
    };
    $($.fn[pluginName].defaults.modalContainerSel).keydown(function(event) {
        return $.fn[pluginName].defaults.onKeypressHandler(event);
    });

})(jQuery, window, document);
