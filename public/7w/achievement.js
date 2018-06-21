$.widget('wonders.achievement', {
    options: {
        filterSelectors: ['.color-filter', '.group-filter', '.achieved-filter']
    },
    _create: function() {
        var self = this;
        this.options.filterSelectors.forEach(function (item) {
            $(item).on('change', function () {
                self.recollect();
            })
        });
        this.fixHeight();
    },
    fixHeight: function() {
        var max = 0;
        var elements = $(this.element).find('.achievement-box .small-box .inner');
        elements.each(function (item) {
            if ($(this).height() > max) {
                max = $(this).height();
            }
        });
        elements.height(max);
    },
    recollect: function () {
        var self = this;
        var selectors = [];

        this.options.filterSelectors.forEach(function (item) {
            var name = item.replace('-filter', '').replace('.', '');
            var checked = $(item + ':checked');
            var filters = [];
            for (var i = 0; i< checked.length; i++) {
                filters.push('[data-' + name + '=' + $(checked[i]).val() + ']');
            }
            selectors.push(filters);
        });
        $('.achievement-box').hide();
        $(self.element).find('.achievement-box').slideUp();
        var combinations = selectors[0];
        for (var j = 1; j<selectors.length; j++) {
            var tmpCombinations = this.cartesian(combinations, selectors[j]);
            combinations = [];
            tmpCombinations.forEach(function(tmp) {
                combinations.push(tmp.join(''));
            });
        }
        combinations.forEach(function(elem) {
            $(self.element).find('.achievement-box' + elem).show();
        });

    },
    cartesian: function() {
        var r = [], arg = arguments, max = arg.length-1;
        function helper(arr, i) {
            for (var j=0, l=arg[i].length; j<l; j++) {
                var a = arr.slice(0); // clone arr
                a.push(arg[i][j]);
                if (i==max)
                    r.push(a);
                else
                    helper(a, i+1);
            }
        }
        helper([], 0);
        return r;
    }
});
