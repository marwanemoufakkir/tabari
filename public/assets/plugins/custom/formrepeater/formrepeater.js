(function($, undefined) {

    $('#kt_docs_repeater_advanced').repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function() {
            $(this).slideDown();

        },

        hide: function(deleteElement) {
            $(this).slideUp(deleteElement);
        },

        ready: function() {


        }
    });



})(jQuery);