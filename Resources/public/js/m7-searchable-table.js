;(function($, window, document, undefined) {
    var pluginName = 'm7SearchableTable', dataKey = 'plugin_' + pluginName;
    
    var Plugin = function(element, options) {
    	this.element = element;
    	this.options = {
    		
    	};
    	
    	this.init(options);
    };
    
    Plugin.prototype = {
    	init : function(options) {
    	    $.extend(this.options, options);
    	    var plugin = this;
    	    var $element = $(this.element);
    	    
    	    this.searchFields = $element.attr("data-terms").split(',');
    	    
    	    var listOptions = {
    	    	valueNames: this.searchFields
    	    };
    	    this.list = new List(this.element, listOptions);
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
		} else if (typeof options === 'string' && options[0] !== '_'
			&& options !== 'init') {
		    // Call a public pluguin method (not starting with an
		    // underscore) for each
		    // selected element.
		    if (Array.prototype.slice.call(args, 1).length == 0
			    && $.inArray(options, $.fn[pluginName].getters) != -1) {
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
			    if (instance instanceof Plugin
				    && typeof instance[options] === 'function') {
				instance[options].apply(instance, Array.prototype.slice
					.call(args, 1));
			    }
			});
		    }
		}
    };
    
    $(document).ready(function() {
    	$('.m7-searchable-table').m7SearchableTable();
    });
})(jQuery, window, document);