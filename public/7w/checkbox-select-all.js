$.widget('wonders.checkboxSelectAll', {
    options: {
        enabler: '',
        checker: '',
        list: ''
    },
    _create: function () {
        var self = this;
        var enabler = $(this.element).find(this.options.enabler);

        var checker = $(this.element).find(this.options.checker);
        if (checker.length) {
            $(checker).on('change', function (item) {
                $(self.element).find(self.options.list).prop('checked', $(this).prop('checked'));
            });
            $(this.element).find(this.options.list).each(function () {
                $(this).on('change', function() {
                    var allList = $(self.element).find(self.options.list);
                    var checked = $(self.element).find(self.options.list + ':checked');
                    $(self.element).find(self.options.checker).prop('checked', allList.length == checked.length);
                });
            })
        }
        if (enabler.length) {
            $(enabler).on('change', function (item) {
                $(self.element).find(self.options.list).prop('disabled', !$(this).prop('checked'));
                $(self.element).find(self.options.checker).prop('disabled', !$(this).prop('checked'));
            });
            $(enabler).trigger('change');
        }
    }
});
