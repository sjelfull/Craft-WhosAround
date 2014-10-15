(function ($) {

    Randomm = function () {}

    Randomm.setFieldName = function () {
        this.fieldname = arguments[0];

        return this.fieldname;
    }

    Randomm.getFieldName = function () {
        return this.fieldname;
    }

    Randomm.init = function () {
        var namespace = arguments[0];
        var $link = $('#' + namespace + '-randomize'),
            $field = $('#' + namespace);

        $link.on('click', function (e) {
            e.preventDefault();
            var $link = $(this),
                randomType = $link.data('randomm-type');

            if (typeof chance !== 'undefined' && typeof chance[randomType] === 'function') {
                // Check if there is any custom arguments set
                var arguments = $link.data('randomm-arguments')
                var value = chance[randomType](arguments)
                $field.val(value);
            }
        });

        // Wrap field so that we can absolute position the trigger link
        $link.parent().children().wrapAll('<div class="randomm-input-wrapper"></div>');

        // Trigger generation if  field is empty and auto-generation is enabled
        if ($field.val().trim() === '' && typeof $link.attr('data-randomm-autogenerate') !== 'undefined')
        {
            $link.trigger('click');
        }
    }

})(jQuery);