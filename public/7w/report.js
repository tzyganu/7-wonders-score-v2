$.widget('wonders.reportUi', {
    options: {
        config: [],
        dependencyMap: []
    },
    _create: function() {
        var config = this.options.config;
        var self = this;
        for (var i = 0; i< config.length;i++) {
            $(this.element).find('[data-group=' + config[i] + ']').on('change', function () {
                var group = $(this).attr('data-group');
                $(self.element).find('[data-group-parent=' + group + ']:enabled').prop('checked', $(this).prop('checked'));
            });
            var childElements = $(self.element).find('[data-group-parent=' + config[i] + ']');
            childElements.on('change', function() {
                var group = $(this).attr('data-group-parent');
                var allSelected = true;
                $(self.element).find('[data-group-parent=' + group + ']:enabled').each(function () {
                    if (!$(this).prop('checked')) {
                        allSelected = false;
                    }
                });
                $(self.element).find('[data-group=' + group + ']').prop('checked', allSelected);
            });
        }
        //row enablers
        $(this.element).find('[data-enabler=true]').each(function () {
            $(this).on('change', function() {
                $(this).closest('tr').find('[data-row-checkbox=true]').prop('disabled', !$(this).prop('checked'));
                $(this).closest('tr').find('[data-row-selector=true]').prop('disabled', !$(this).prop('checked'));
            });
            $(this).trigger('change');
        });
        $(this.element).find('[data-row-selector=true]').each(function () {
            $(this).on('change', function() {
                $(this).closest('tr').find('[data-row-checkbox=true]').prop('checked', $(this).prop('checked')).trigger('change');
            });
        });
        var dependencyMap = this.options.dependencyMap;
        if (dependencyMap.length) {
            $(this.element).find('.dependency-map').select2().on('change', function () {
                var val = $(this).val();
                $(self.element).find('[data-is-agg=true]').hide();
                $(self.element).find('[data-enabler=true]').prop('checked', false).trigger('change');
                for (var i = 0; i<dependencyMap[val].fields.length; i++) {
                    var row = $(self.element).find('[data-row-agg=' + dependencyMap[val].fields[i] + ']');
                    row.show();
                    $(row).find('[data-enabler=true]').prop('checked', true).trigger('change');
                }
            }).trigger('change')
        }
        $('[data-row-checkbox=true]:checked').trigger('change');
    }
});
