function shuffleArray(arr) {
    var j, x, i;
    var self = this;
    for (i = arr.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = arr[i];
        arr[i] = arr[j];
        arr[j] = x;
    }
    return arr;
}

$.widget('wonders.wonderSets', {
    options: {
        boxSelector: '.box',
        setCheckboxSelector: '.box-title input[type=checkbox]',
        wonderCheckboxSelector: '.box-body input[type=checkbox]'
    },
    _create: function() {
        var self = this;
        $(this.element).find(this.options.boxSelector).each(function() {
            var box = this;
            $(this).find(self.options.setCheckboxSelector).on('change', function() {
                $(box).find(self.options.wonderCheckboxSelector).prop('checked', this.checked);
            })
        });
    }
});


$.widget('wonders.game', {
    options:{
        template: '',
        containerSelector: 'tbody',
        defaultPlayers: 3,
        minPlayers: 3,
        maxPlayers: 8,
        addPlayerTrigger: '.add-player',
        wondersCheckboxes: '.wonder-in-play',
        wonderGroupsSelector: '.wonder-set',
        wondersConfig: {},
        shufflePlayerTrigger: '.shuffle-player',
        shuffleWonderTrigger: '.shuffle-wonder',
        shuffleSideTrigger: '.shuffle-side',
        shuffleAllTrigger: '.shuffle-all'
    },
    _create: function() {
        var self = this;
        this.players = [];
        this.validWonders = this.collectWonders();
        this.playerIndex = 0;
        for (var i = 0; i< this.options.defaultPlayers; i++) {
            this.addPlayer();
        }
        $(this.options.wondersCheckboxes + ',' + this.options.wonderGroupsSelector).on('change', function() {
            self.recollectWonders();
        });

        $(this.options.addPlayerTrigger).on('click', function() {
            self.addPlayer();
        });
        $(this.options.shufflePlayerTrigger).on('click', function() {
            self.shufflePlayers();
        });
        $(this.options.shuffleWonderTrigger).on('click', function() {
            self.shuffleWonders();
        });
        $(this.options.shuffleSideTrigger).on('click', function() {
            self.shuffleSides();
        });
        $(this.options.shuffleAllTrigger).on('click', function() {
            self.shufflePlayers();
            self.shuffleWonders();
            self.shuffleSides();
        });
        $('#form_game_cities, #form_game_leaders').on('change', function() {
            self.recollectWonders();
            self.checkExtensions();
        });
        this.attachValidation();
        // this.automateGame();
    },

    attachValidation: function() {
        var that = this;
        window.addEventListener('load', function() {
            var form = $(that.element).closest('form');
            form.on('submit', function(event) {
                if (!that.validateForm(this)) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        }, false);
    },

    validateForm: function(form) {
        //validate wonders
        var wonders = $('.wonder-select');
        var wonderValues = {};
        for (var i = 0;i<wonders.length;i++) {
            var val = $(wonders[i]).val();
            if (!val) {
                continue;
            }
            if (typeof wonderValues[val] != "undefined") {
                alert('You cannot have 2 players play with the same wonder');
                return false;
            }
            wonderValues[val] = val;
        }

        //validate wonders
        var sides = $('.wonder-side');
        for (var i = 0;i<sides.length;i++) {
            var val = $(sides[i]).val();
            if (!val) {
                alert('Select Wonders Sides for all players');
                return false;
            }
        }

        //validate players
        var players = $('.player-select');
        var playerValues = {};
        for (i = 0;i<players.length;i++) {
            val = $(players[i]).val();
            if (!val) {
                continue;
            }
            if (typeof playerValues[val] != "undefined") {
                alert('You cannot have 2 times the same player');
                return false;
            }
            playerValues[val] = val;
        }
        return true;
    },

    checkExtensions: function () {
        if ($('#form_game_leaders').prop('checked')) {
            $('.leaders-score').removeAttr('disabled').closest('td').show();
        } else {
            $('.leaders-score').attr('disabled', 'disabled').closest('td').hide();
        }
        if ($('#form_game_cities').prop('checked')) {
            $('.cities-score').removeAttr('disabled').closest('td').show();
        } else {
            $('.cities-score').attr('disabled', 'disabled').closest('td').hide();
        }
        var _players = Object.values(this.players);
        _players.forEach(function(item) {
            item.player('calculateTotal');
        });
    },
    /**
     * for debug purposes.
     * fills in the form and submits it.
     */
    automateGame: function () {
        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }
        var alreadyPlayed = localStorage.autoPlay;
        if (!alreadyPlayed) {
            alreadyPlayed = 0;
        }
        console.log(alreadyPlayed);
        var cookieName = 'autoPlayedGames';
        var maxValue = 100;
        if (alreadyPlayed >= maxValue) {
            alert('played enough');
        } else {
            var playerCount = Math.random() * 5;
            for (var i = 0; i<playerCount; i++) {
                this.addPlayer();
            }
            var playerIds = [];
            var options = $($('.player-select')[0]).find('option');
            for (var i in options) {
                if (options.hasOwnProperty(i)) {
                    var val = $(options[i]).attr('value');
                    if (val) {
                        playerIds.push(val);
                    }
                }
            }
            shuffleArray(playerIds);
            $('.player-select').each(function (i) {
                $(this).val(playerIds[i]);
                $(this).trigger('change');
            });
            $('input[name^=form]:enabled').not('.new-player').not('.js-datepicker').not('[type=checkbox]').each (
                function() {
                    if ($(this).attr('readonly')) {
                        return;
                    }
                    $(this).val(Math.floor(Math.random() * (10))).trigger('change')
                }
            );
            $('.js-datepicker').val('2018-0' + Math.floor(Math.random() * (10)) + '-' + Math.floor(Math.random() * (18) + 10));
            $('#form_game_cities, #form_game_leaders').each(function () {
                var r = Math.floor(Math.random() * (2));
                $(this).prop('checked', (r == 1))
                $(this).trigger('change');
            });
            this.shufflePlayers();
            this.shuffleSides();
            this.shuffleWonders();
            alreadyPlayed++;
            localStorage.autoPlay = alreadyPlayed;
            $('form[name=form]').submit();
        }

    },
    shufflePlayers: function() {
        var self = this;
        var _players = Object.values(this.players);
        shuffleArray(_players);
        //rearrange the elements
        _players.forEach(function(item) {
            item.player('widget').appendTo($(self.element).find(self.options.containerSelector))
        });
    },
    getMaxPlayers: function () {
        return this.options.maxPlayers;
    },
    addPlayer: function() {
        var text = this.options.template.replace(/__id__/g , this.playerIndex);
        text = text.replace(/__id1__/g , this.playerIndex+1);
        $(this.element).find(this.options.containerSelector).append(text);
        var player = $(this.element).find(this.options.containerSelector + ' > :last-child').player({game: this, index:this.playerIndex});
        player.player('populateWonder', this.validWonders);
        this.players['p' + this.playerIndex] = player;
        this.playerIndex++;
        if (Object.keys(this.players).length >= this.getMaxPlayers()) {
            $(this.options.addPlayerTrigger).attr('disabled', 'disabled');
        }
        this.checkExtensions()

    },
    collectWonders: function() {
        var validWonders = [];
        var validCheckboxes = $(this.options.wondersCheckboxes + ':checked').not(':disabled');
        var leadersEnabled = $('#form_game_leaders').prop('checked'); //TODO: move this to params
        var citiesEnabled = $('#form_game_cities').prop('checked'); //TODO: move this to params
        for (var i = 0;i< validCheckboxes.length; i++) {
            var value = $(validCheckboxes[i]).attr('value');
            var config = this.options.wondersConfig[value];
            if (leadersEnabled && !config.leaders) {
                continue;
            }
            if (!leadersEnabled && !config.withoutLeaders) {
                continue;
            }
            if (citiesEnabled && !config.cities) {
                continue;
            }
            if (!citiesEnabled && !config.withoutCities) {
                continue;
            }
            validWonders.push({
                'name': config.name,
                'id': config.id
            });
        }
        validWonders.sort(function compare(a,b) {
            if (a.name < b.name)
                return -1;
            if (a.name > b.name)
                return 1;
            return 0;
        });

        return validWonders;
    },
    recollectWonders: function() {
        this.validWonders = this.collectWonders();
        for (var i in this.players) {
            if (this.players.hasOwnProperty(i)) {
                this.players[i].player('populateWonder', this.validWonders);
            }
        }
    },

    shuffleWonders: function() {
        var wonders = this.validWonders;
        if (wonders.length < Object.values(this.players).length) {
            alert('Not enough wonders selected');
            return false;
        }
        shuffleArray(wonders);
        var index = 0;
        for (var i in this.players) {
            if (this.players.hasOwnProperty(i)) {
                this.players[i].player('setWonder', wonders[index].id);
                index++;
            }
        }
        return true;
    },
    removePlayer: function(player) {
        delete this.players['p' + player];
    },
    shuffleSides: function() {
        var sideSelects = $(this.element).find('select.side-select');
        var totalSides = Object.values(this.players).length;
        var max = Math.pow(2, totalSides) - 1;
        var min = 0;
        var random = Math.floor(Math.random() * (max - min + 1)) + min;
        random = random.toString(2);
        var pad = '';
        for (var i = 0;i<totalSides;i++) {
            pad += '0';
        }
        random = pad.substring(0, pad.length - random.length) + random;
        var index = 0;
        for (var p in this.players) {
            if (this.players.hasOwnProperty(p)) {
                this.players[p].player('setSide', (random[index] == "0") ? 'A' : 'B');
                index++;
            }
        }
    }
});

$.widget('wonders.player', {
    options: {
        game: '',
        index: ''
    },
    _create: function() {
        var that = this;
        this.military = $(this.element).find('.military').military();
        this.cash = $(this.element).find('.cash').cash();
        this.science = $(this.element).find('.science').science();
        this.addDeleteButton();
        $(this.element).find('.category-score').on('change', function() {
            that.calculateTotal();
        });
        $(this.element).find('select').select2();
        $(this.element).find('.player-select').on('change', function () {
            if ($(this).val()) {
                $(that.element).find('.new-player').parent().hide();
                // $(that.element).find('.new-player').removeAttr('required');
            } else {
                $(that.element).find('.new-player').parent().show();
                // $(that.element).find('.new-player').attr('required', 'required');
            }
        });
        $(this.element).find('label').each(function(){
            var icon = $(this).attr('data-icon');
            if (icon) {
                $(this).html('<span class="' + icon + '"></span>');
            }
        });
    },
    setWonder:function(wonder) {
        $(this.element).find('.wonder-select').val(wonder).trigger('change');
    },
    setSide:function(side) {
        $(this.element).find('.wonder-side').val(side).trigger('change');
    },
    populateWonder: function(validWonders) {
        var wonderSelect = $(this.element).find('.wonder-select');
        wonderSelect.select2('destroy');
        var currentVal = $(wonderSelect).val();
        $(wonderSelect).html('<option>Select Wonder</option>');
        validWonders.forEach(function (validWonder){
            var option = $('<option></option>');
            option.attr('value', validWonder.id);
            option.html(validWonder.name);
            $(wonderSelect).append(option);
        });
        $(wonderSelect).val(currentVal);
        wonderSelect.select2();
    },
    addDeleteButton: function() {
        var self = this;
        var del = $('<a></a>');
        del.attr('href', '#');
        del.addClass('fa fa-trash');
        del.on('click', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove player?')) {
                $(self.element).slideUp(500, function() {
                    $(self.element).remove();
                });
                self.options.game.removePlayer(self.options.index);
                $(self.options.game.options.addPlayerTrigger).removeAttr('disabled');
            }
        });
        (this.element).find('td:first').append(del);
    },
    calculateTotal: function () {
        var scoringInputs = $(this.element).find('.category-score');
        var sum = 0;
        for (var i = 0; i<scoringInputs.length;i++) {
            if (!$(scoringInputs[i]).hasClass('ignore') && $(scoringInputs[i]).is(':visible')) {
                var score = parseInt($(scoringInputs[i]).val());
                if (!isNaN(score)) {
                    sum += score;
                }
            }
        }
        $(this.element).find('.grand-total').val(sum);
    }
});

$.widget('wonders.military', {
    _create: function() {
        var self = this;
        Object.keys(this.getTokens()).forEach(function (element, index, array) {
            $(self.element).find('.' + element +' input').on('change', function () {
                self.getScore();
            });
        });
    },
    getTokens: function() {
        return {
            'five': 5,
            'three': 3,
            'one': 1,
            'minus_one': -1
        }
    },
    getScore: function() {
        var selectors = this.getTokens();
        var sum = 0;
        for (var i in selectors) {
            if (selectors.hasOwnProperty(i)) {
                var value = parseInt($(this.element).find('.' + i +' input').val());
                value = isNaN(value) ? 0 : value;
                sum += this.compute(selectors[i], value);
            }
        }
        $(this.element).find('.score input').val(sum).trigger('change');
    },
    compute: function (field, value) {
        return field * value;
    }
});

$.widget('wonders.cash', $.wonders.military, {
    getTokens: function() {
        return {
            'coins': 1/3,
            'minus_one': -1
        }
    },
    compute: function (field, value) {
        return Math.floor(field * value);
    }
});

$.widget('wonders.science', {
    _create: function() {
        var self = this;
        this.getSymbols().forEach(function (element, index, array) {
            $(self.element).find('.' + element +' input').on('change', function () {
                self.getScore();
            });
        });
    },
    getSymbols: function() {
        return ['tablet', 'gear', 'compass']
    },
    getScore: function() {
        var selectors = this.getSymbols(),
            sum = 0,
            min = Number.MAX_SAFE_INTEGER,
            self = this;
        this.getSymbols().forEach(function (element, index, array) {
            var value = parseInt($(self.element).find('.' + element +' input').val());
            value = isNaN(value) ? 0 : value;
            min = (value < min) ? value : min;
            sum += value * value;
        });
        sum += min * 7;
        $(this.element).find('.score input').val(sum).trigger('change');
    }
});
