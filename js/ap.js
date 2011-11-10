var Ap = new Class({
    Implements: [Options],
    options: {
        /**
         * Форма
         */
        formId: null,

        /**
         * Начальное количество АП
         */
        startApId: null,

        /**
         * Куда записывать результат
         */
        resultId: null,

        usersCoundId: null,

        'buttons': {
            'calcPeople': null
        },

        /**
         * Цена итемов
         */
        itemsAp: {
            'icon'  : [300, 600, 900, 1200],
            'seal'  : [600, 1200, 1800, 2400],
            'cup'   : [1200, 2400, 3600, 4800],
            'crown' : [2400, 4800, 7200, 9600]
        },

        /**
         * Название итемов
         */
        itemsName: {
            'icon'  : {
                '300'   : 'Простая древняя иконка',
                '600'   : 'Обычная древняя икона',
                '900'   : 'Дорогая древняя икона',
                '1200'  : 'Бесценная древняя икона'
            },
            'seal'  : {
                '600'   : 'Простая древняя печать',
                '1200'  : 'Обычная древняя печать',
                '1800'  : 'Дорогая древняя печать',
                '2400'  : 'Бесценная древняя печать'
            },
            'cup'   : {
                '1200'  : 'Простая древняя чаша',
                '2400'  : 'Обычная древняя чаша',
                '3600'  : 'Дорогая древняя чаша',
                '4800'  : 'Бесценная древняя чаша'
            },
            'crown' : {
                '2400'  : 'Простая древняя корона',
                '4800'  : 'Обычная древняя корона',
                '7200'  : 'Дорогая древняя корона',
                '9600'  : 'Бесценная древняя корона'
            }
        },

        users: [],
        usersItems: [],
        usersGroupLegend: null,
        iterationN: 1,

        groupNowApId: null,

        shablon: {
            iterationBlock: '<div><a href="#iteration-{iterationN}" name="iteration-{iterationN}" onclick="$(\'iteration-{iterationN}\').toggleClass(\'hide\');">Распределение #{iterationN}</a>' +
                    '<div id="iteration-{iterationN}" class="iteration-conteiner">{block}</div>' +
                    '</div>',

            block:  '<div class="group-block"><div class="group-block-title left">{userId}<span>{userAp}</span><small>+{userAddAp}</small></div>' +
                    '<div class="group-block-items right">{items}</div><div class="clear"></div>' +
                    '</div><hr class="space" />',

            items:  '<div class="group-block-item"><img src="/images/ap/{itemName}_{itemN}.png" />{itemFullName} ' +
                    '<span>{itemAp} APs</span><div class="clear"></div>' +
                    '</div>',

            groupNowAp: '<div>{userId} &mdash; {userAp} Aps</div>'

        }
    },
    startOptions: {},

    initialize: function (options) {
        this.setOptions(options);
        this.startOptions = Object.clone(this.options);

        var that = this;
        $$('#' + this.options.formId + " input[type='text'], #"+ this.options.startApId + ', #' + this.options.usersCoundId).addEvents({
            'focus': function() {
                var value = Number.from($(this).get('value'));
                if (!value) $(this).set('value', '');
            },
            'blur': function() {
                var value = Number.from($(this).get('value'));
                if (!value) $(this).set('value', '0');
            },
            'keyup': function() {
                $(that.options.resultId).set('text', that.calc());
            },
            'keypress': function(e) {
                //var e = new Event(e);
                if(e.code <= 31 || (e.code >= 48 && e.code <= 57) ) {
                    return true;
                } else {
                    e.stopPropagation();
                    e.stop();
                    return false;
                }
            }
        });

        if (this.options.buttons.calcPeople) {
            this.options.buttons.calcPeople.addEvent('click', function(e) {
                that.calcPersone();
                e.stop();
                return false;
            });
        }

        $('clear').addEvent('click', function(e) {
            that.clear();
            e.stop();
            return false;
        })

        return this;
    },

    calc: function() {
        if (!$(this.options.formId)) throw new Error("Can't found form with id '" + this.options.formId + "'");
        
        var formItems = $(this.options.formId).serialize(true);
        var allAp = 0;
        var startAp = ($(this.options.startApId)) ? Number.from($(this.options.startApId).get('value')) : 0;

        if (undefined == formItems.items) return 0;

        formItems = formItems.items;
        var itemsAp = this.options.itemsAp;

        for (var itemType in formItems) {
            for (var itemName in formItems[itemType]) {
                if (undefined != itemsAp[itemType][itemName]) {
                    var itemCound = formItems[itemType][itemName];
                    var value = Number.from(itemsAp[itemType][itemName]);

                    allAp += (null != value) ? (itemCound * value) : 0;
                }
            }
        }

        return (allAp + startAp);
    },
    calcPersone: function() {
        var userCound = Number.from($(this.options.usersCoundId).get('value'));
        if (!userCound) throw new Error("userCound is empty");

        var items = $(this.options.formId).serialize(true);
        if (!items.items) throw new Error("items is empty");

        var itemsAp = this.options.itemsAp;


        var stek = [];
        var allPrice = 0;


        // Заполняем стек
        Object.each(items.items, function(value, key) {
            Object.each(value, function(valueItem, valueKey) {
                var itemAp = itemsAp[key][valueKey];
                for (var i = 0; i < valueItem; i++) {
                    stek = Array.append([{
                        ap: itemAp,
                        name: key,
                        n: valueKey
                    }], stek);
                    allPrice += itemAp;
                }
            });
        });

        var users = this.options.users;
        var srez = allPrice / userCound;

        var usersItems = Array.clone(this.options.usersItems);

        for (var userId = 0; userId < userCound; userId++) {
            if (undefined == users[userId]) users[userId] = 0;
            if (undefined == usersItems[userId]) usersItems[userId] = [];

            if (users[userId] >= srez) continue;

            for (var itemId in stek) {
                if (stek[itemId]) {
                    if ((users[userId] + stek[itemId].ap) <= srez) {
                        users[userId] += stek[itemId].ap;

                        usersItems[userId].append([stek[itemId]]);

                        //console.log(stek[itemId], itemId);
                        stek[itemId] = null;
                    }
                }
                if (users[userId] >= srez) break;
            }
        }

        var getMinUser = function(users) {
            var minId = null, minValue = null;
            users.each(function(value, key) {
                if (null == minId) {
                    minId = key;
                    minValue = value;
                    return;
                }

                if (value < minValue) {
                    minId = key;
                    minValue = value;
                }
            });

            return {key: minId, value: minValue};
        }

        stek.each(function(value) {
            if (value) {
                var minUser = getMinUser(users);
                users[minUser.key] += value.ap;
                if (undefined == usersItems[minUser.key]) usersItems[minUser.key] = [];
                usersItems[minUser.key].append([value]);
            }
        });

        //console.log(users, usersItems);
        this.addPerconeResultLine(users, usersItems);
    },
    clear: function() {
        this.setOptions(this.startOptions);

        $(this.options.resultId).set('text', '0');
        $$('input[type=text]').set('value', '0');
        $(this.options.usersGroupLegend).set('html', '');
        $(this.options.groupNowApId).set('html', '');
    },
    clearToNext: function() {
        $(this.options.resultId).set('text', '0');
        $$('input[type=text]').set('value', '0');
    },
    addPerconeResultLine: function(users, usersItems) {
        var that = this;

        if (!that.options.usersGroupLegend) throw new Error('usersGroupLegend is null');

        var blockHtml = '';
        
        users.each(function(userAp, userId) {
            var itemsHtml = '';
            var userAddAp = 0;

            usersItems[userId].each(function(value) {
                var item = String.from(that.options.shablon.items);
                item = item.substitute({
                    'itemName': value.name,
                    'itemN': value.n,
                    'itemAp': value.ap,
                    'itemFullName': (that.options.itemsName[value.name][value.ap]) ? that.options.itemsName[value.name][value.ap] : 'Ошибочное имя'
                });

                userAddAp += value.ap;
                itemsHtml += item;
            });

            var block = String.from(that.options.shablon.block);

            block = block.substitute({
                'userId': userId + 1,
                'userAp': userAp,
                'userAddAp': userAddAp,
                'iterationN': that.options.iterationN,
                'items': itemsHtml
            });

            blockHtml += block;
        });

        var iterationHtml = String.from(that.options.shablon.iterationBlock);
            iterationHtml = iterationHtml.substitute({
                'iterationN': that.options.iterationN,
                'block': blockHtml
            });

        that.options.iterationN++;

        $$('.iteration-conteiner').addClass('hide');

        var el = $(that.options.usersGroupLegend);
            el.set('html', el.get('html') + iterationHtml);

        this.clearToNext();
        this.showUsersNowAp();
    },
    showUsersNowAp: function() {
        var users = this.options.users;
        var that = this;

        var html = '';
        var allAp = 0;
        users.each(function(userAp, userId) {
            html += String.from(that.options.shablon.groupNowAp).substitute({
                'userId': (userId + 1),
                'userAp': userAp
            });
            allAp += userAp;
        });

        html += '<span style="border-top: 1px solid #000;">Всего AP: ' + allAp + '</span>';

        $(that.options.groupNowApId).set('html', html);
    }
});

var ArmorItems = new Class({
    Implements: [Options],
    options: {
        urls: {
            items: '/armor.php'
        },
        
        spy: null,
        start: 0,
        request: null,

        filterLoading: null,
        filter: null,

        items: {},
        
        formValues: null
    },
    armor: {},

    initRequest: function() {
        var that = this;

        var loading = this.options.filterLoading;

        var request = new Request.JSON({
            url: that.options.urls.items,
            method: 'get',
            link: 'cancel',
            noCache: true,
            onRequest: function() {
                loading.removeClass('hide');
            },
            onSuccess: function(responseJSON) {
                loading.addClass('hide');

                 that.options.start += 100;
                 that.displayItemList(responseJSON);

                 that.spyAct();
            },
            onFailure: function() {
                //reset the message
                loading.addClass('hide');
            },
            onComplete: function() {
                //remove the spinner
                loading.addClass('hide');
            },
            onError: function() {
                loading.addClass('hide');
            }
        });
        this.options.request = request;
    },

    spyAct: function() {
        var spyContainer = this.options.itemsList;
        var filter = this.options.filter;

        var min = spyContainer.getScrollSize().y - spyContainer.getSize().y - 300;

        this.spy = new ScrollSpy({
            container: spyContainer,
            min: min,
            onEnter: function() {
                filter.fireEvent('submit');
            }
        })
    },

    displayItemList: function(postsJSON) {
        var that = this;
        postsJSON.each(function(post, i) {
            that.options.items[post['id']] = post;
            
            var postDiv = new Element('div', {
                'class': 'post' + ((post['q']) ? ' q' + post['q'] : ''),
                'events': {
                    click: function(e) {
                        that.armor.man.setItem(post);
                    },
                    // Правый клик (сравнение)
                    contextmenu: function() {
                        that.armor.compare.compare(post);

                        return false;
                    }
                },
                id: 'post-' + post['id'],
                html: '<img src="' + post['smallimage'] + '" /> <span>' + post['name'] + '</span>'
            });
            postDiv.inject(that.options.itemsList);
        });
    },

    initFilter: function() {
        var filter = this.options.filter;
        var that = this;
        filter.addEvent('submit', function(event) {
            var data = this.serialize(true);
            if (Object.toQueryString(data) != Object.toQueryString(that.options.formValues)) {
                that.options.itemsList.set('html', '');
                that.options.start = 0;
                that.options.formValues = data;
            }

            that.options.request.send({
                data: {
                    'start': that.options.start,
                    'data': that.options.formValues
                }
            });
            return false;
        });
    },

    initialize: function (options) {
        this.setOptions(options);

        this.initRequest();
        this.initFilter();
    }
});

var Man = new Class({
    Implements: [Options],
    options: {
        man: $('man'),
        status: $('status'),
        selectItem: null,
        items: {},
        items2: {},

        filterType: null,
        filterSlot: null,
        filterName: null,
        filterClass: null
    },
    armor: {},

    initSelectItem: function() {
        var that = this;
        $$('.item').each(function(item) {
            item.addEvent('click', function() {
                $$('.item').removeClass('selectedItem');
                this.addClass('selectedItem');
                that.options.selectItem = this;

                var slot = Number.from(this.get('slot'));
                that.options.filterSlot.set('value', slot);

                var class = Number.from(that.options.filterClass.get('value'));

                that.options.filterType.set('value', '0');

                // Танк
                if (1 == class) {

                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань, Кожа, Кольчуга, Латы
                        that.options.filterType.set('value', '1,2,3,4');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Щиты, Двуручные мечи, Мечи, Булавы
                        that.options.filterType.set('value', '5,8,9,11');
                    }
                }

                // Гладиатор
                if (2 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань, Кожа, Кольчуга, Латы
                        that.options.filterType.set('value', '1,2,3,4');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Щиты, Копья, Двуручные мечи, Мечи, Кинжалы, Булавы?, Луки,
                        that.options.filterType.set('value', '5,7,8,9,10,11,13');
                    }
                }

                // Целитель
                if (3 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань, Кожа, Кольчуга
                        that.options.filterType.set('value', '1,2,3');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Щиты, Булавы, Посохи
                        that.options.filterType.set('value', '5,11,12');
                    }
                }

                // Чародей
                if (4 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань, Кожа, Кольчуга
                        that.options.filterType.set('value', '1,2,3');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Щиты, Булавы, Посохи
                        that.options.filterType.set('value', '5,11,12');
                    }
                }

                // Стрелок
                if (5 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань, Кожа
                        that.options.filterType.set('value', '1,2');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Мечи, Кинжалы, Луки
                        that.options.filterType.set('value', '9,10,13');
                    }
                }

                // Убийца
                if (6 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань, Кожа
                        that.options.filterType.set('value', '1,2');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Мечи, Кинжалы, Луки
                        that.options.filterType.set('value', '9,10,13');
                    }
                }

                // Волшебник
                if (7 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань
                        that.options.filterType.set('value', '1');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Орбы, Гримуары
                        that.options.filterType.set('value', '14,15');
                    }
                }

                // Заклинатель
                if (8 == class) {
                    // Тело - Торс, Штаны, Ботинки, Наплечники, Перчатки
                    if ([2, 3, 4, 5, 6].indexOf(slot) != -1) {
                        // Ткань
                        that.options.filterType.set('value', '1');
                    }

                    // Оружие
                    if ([12, 13].indexOf(slot) != -1) {
                        // Орбы, Гримуары
                        that.options.filterType.set('value', '14,15');
                    }
                }

                that.options.filterName.set('value', '');
                $('filter').fireEvent('submit');
                $('add-items').tween('left', -150, 380);
                
            }).addEvent('contextmenu', function() {
                if (that.options.items[this.get('id')]) {
                    that.armor.compare.compare(that.options.items[this.get('id')]);
                } else {
                    this.fireEvent('click');
                }

                return false;
            });
        });
        this.options.filterClass.addEvent('change', function() {
            $$('.item.selectedItem').fireEvent('click');
        });

        $$('.swapWeapon').each(function(a) {
            a.addEvent('click', function() {
                that.swapWeapon();
            });
        });
    },

    initialize: function (options) {
        this.setOptions(options);

        var tween = new Fx.Tween($('add-items'));

        this.initSelectItem();
    },

    setItem: function(item) {
        var that = this;

        var img = new Element('img', {
            'src': item.smallimage
        });

        var div;
        switch (Number.from(item.type)) {
            case 1: case 2: case 3: case 4: case 6: case 17: case 19:
                div = $('item-' + item.slot);
            break;

            case 7: case 8: case 12: case 13: case 14:  case 15:
                this.options.items['item-12'] = null;
                this.options.items['item-13'] = null;
                $('item-12').empty(); $('item-13').empty();
                img.clone().inject($('item-12'));
                
                div = $('item-13');
            break;
            case 5: case 9: case 10: case 11:

                if (this.options.items['item-13'] && [5, 9, 10, 11].indexOf(Number.from(this.options.items['item-13'].type)) == -1) {
                    $('item-13').empty(); $('item-12').empty();
                    this.options.items['item-13'] = null;
                }

                if (5 == item.type) {
                    div = $('item-12');
                } else {
                    if (this.options.selectItem && [12, 13].indexOf(Number.from(this.options.selectItem.get('slot'))) != -1) {
                        div = this.options.selectItem;
                    } else {
                        var isBrack = false;
                        $$('#item-12, #item-13').each(function(el) {
                            if (isBrack) return;
                            if (!el.getElement('img')) {
                                isBrack = true;
                                div = el;
                            }
                        });
                        if (!div) div = $('#item-12');
                    }
                }

            break;

            case 16: case 18:
                if (this.options.selectItem && this.options.selectItem.get('slot') == item.slot) {
                    div = this.options.selectItem;
                } else {
                    var isBrack = false;
                    $$('#item-' + item.slot + '-1, #item-' + item.slot + '-2').each(function(el) {
                        if (isBrack) return;
                        if (!el.getElement('img')) {
                            isBrack = true;
                            div = el;
                        }
                    });
                    if (!div) div = $$('[slot=' + item.slot + ']')[0];
                }
            break;
        }

        if (div) {
            this.options.items[div.get('id')] = item;
            img.inject(div.empty());
            this.updateStatus();

            if ($('add-items').getStyle('left').toInt() > 0) $('add-items').tween('left', 380, -150);
        }
    },

    updateStatus: function() {
        var that = this;
        var status = {};

        function calcSkill(name, value) {
            if ('Атака' == name) {
                status[name] = value;
            } else {
                if (value.length > 2) {
                    if ('+' == value[0] && '-' == value[1]) value = value.substr(1);
                }
                value = Number.from(value);

                if (!value) return;

                status[name] = (!status[name]) ? value : status[name] + value;
            }
        }

        Object.each(this.options.items, function(item) {
            if (!item || !item.skills) return;
            
            if (item.skills.main) {
                Object.each(item.skills.main, function(skill, skillName) {
                    calcSkill(skillName, skill);
                });
            }

            if (item.skills.other) {
                Object.each(item.skills.other, function(skill, skillName) {
                    calcSkill(skillName, skill);
                });
            }

            if (item.skills.point) {
                Object.each(item.skills.point, function(skill, skillName) {
                    calcSkill(skillName, skill);
                });
            }

            if (item.skills.stoun) {
                Object.each(item.skills.stoun, function(st) {
                    if (Object.getLength(st))
                        calcSkill(st.name, st.value);
                });
            }

            //pvp
            if (item.pvp_atack != 0) calcSkill('PvP атака', item.pvp_atack);
            if (item.pvp_protect != 0) calcSkill('PvP защита', item.pvp_protect);
        });

        var statusText = '';
        Object.each(status, function(value, name) {
            statusText += '<div>' + name + ' = ' + value + '</div>';
        })
        that.options.status.set('html', statusText);
    },

    swapWeapon: function() {
        if (!this.options.items['item-12']) this.options.items['item-12'] = null;
        if (!this.options.items['item-13']) this.options.items['item-13'] = null;
        if (!this.options.items2['item-12']) this.options.items2['item-12'] = null;
        if (!this.options.items2['item-13']) this.options.items2['item-13'] = null;

        var item13 = this.options.items['item-13'];
        var item12 = this.options.items['item-12'];
        var item13html = $('item-13').get('html');
        var item12html = $('item-12').get('html');

        this.options.items['item-13'] = this.options.items2['item-13'];
        this.options.items['item-12'] = this.options.items2['item-12'];
        $('item-13').set('html', $('item2-13').get('html'));
        $('item-12').set('html', $('item2-12').get('html'));

        this.options.items2['item-13'] = item13;
        this.options.items2['item-12'] = item12;
        $('item2-13').set('html', item13html);
        $('item2-12').set('html', item12html);

        this.updateStatus();
    }

});

var Armor = new Class({
    Implements: [Options],
    options: {
        types: {
            itemsType: {
                '1': 'Тканые доспехи',
                '2': 'Кожаные доспехи',
                '3': 'Кольчужные доспехи',
                '4': 'Латные доспехи',
                '5': 'Щиты',
                '6': 'Головной убор',

                '7': 'Копья',
                '8': 'Двуручные мечи',
                '9': 'Мечи',
                '10':'Кинжалы',
                '11':'Булавы',
                '12':'Посохи',
                '13':'Луки',
                '14':'Орбы',
                '15':'Гримуары',

                '16':'Серьги',
                '17':'Ожерелья',
                '18':'Кольца',
                '19':'Пояса'
            },
            skills: {
                '1': 'Атака',
                '2': 'Физическая атака',
                '3': 'Маг. атака',
                '4': 'Скор. атаки',
                '5': 'Скор. магии',
                '6': 'Точность',
                '7': 'Точн. магии',
                '8': 'Ф. крит.',
                '9': 'М. крит.',
                '10':'Сила магии',
                '11':'Сила исцелен.',

                '12':'Парир.',
                '13':'Уклонение',
                '14':'Концентрац.',
                '15':'Блок урона',
                '16':'Блок щитом',
                '17':'Блок ф. крит.',
                '18':'Блок м. крит.',

                '19':'Физ. защита',
                '20':'Маг. защита',
                '21':'Защ. от земли',
                '22':'Защ. от возд.',
                '23':'Защ. от воды',
                '24':'Защ. от огня',
                '25':'Защита от ф. крит.',

                '26':'Сопротивление оглушению',
                '27':'Сопротивление опрокидыванию',
                '28':'Сопротивление отталкиванию',

                '29':'Макс. HP',
                '30':'Макс. MP',

                '31':'Скор. полета',
                '32':'Время полета',
                '33':'Скор. движ.',

                '34':'Агрессия',

                '35':'ЛВК',

                '36':'PvP атака',
                '37':'PvP защита'
            },
            slots: {
                '1': 'Голова',
                '2': 'Торс',
                '3': 'Штаны',
                '4': 'Ботинки',
                '5': 'Наплечники',
                '6': 'Перчатки',

                '7': 'Ожерелья',
                '8': 'Серьги',
                '9': 'Кольца',
                '10':'Пояс',

                '11':'Крыло',

                '12':'Главная или Вторая Рука',
                '13':'Главная Рука'
            }
        },
        magicStouns: {
            1:  [
                {
                    name: 'Маг. камень: HP +95',
                    q: 1,
                    skills: {'Макс. HP': 95}
                },
                {
                    name: 'Маг. камень: HP +85',
                    q: 1,
                    skills: {'Макс. HP': 85}
                },
                {
                    name: 'Маг. камень: HP +75',
                    q: 1,
                    skills: {'Макс. HP': 75}
                },
                {
                    name: 'Маг. камень: HP +65',
                    q: 1,
                    skills: {'Макс. HP': 65}
                },
                {
                    name: 'Маг. камень: HP +55',
                    q: 1,
                    skills: {'Макс. HP': 55}
                }
            ]
        }
    },

    armorItems: null,
    man: null,
    compare: null,

    initialize: function (options) {
        this.setOptions(options);

        if (options.items) {
            this.armorItems = new ArmorItems(options.items);
            this.armorItems.armor = this;
        }

        if (options.man) {
            this.man = new Man(options.man);
            this.man.armor = this;
        }

        if (options.compare) {
            this.compare = new ItemCompare(options.compare);
            this.compare.armor = this;
        }
    }

});

var MessageBox = new Class({
    Implements: [Options],
    options: {
        html: '',
        element: null,
        sp: null,
        close: false
    },
    message: null,

    armor: null,

    initialize: function (options) {
        this.setOptions(options);
        var that = this;

        if (undefined == options.sp) {
            var sp = new Element('div', {id: 'sp', class: 'hide', 'events':{'click':function() {that.hide();}}});
            sp.inject(document.body);
            this.options.sp = sp;
        } else {
            this.options.sp = sp;
        }

        var message = new Element('div', {
            html: ((null == this.options.element) ? this.options.html : this.options.element.get('html')) + '<div class="clear"></div>',
            class:'modal hide',
            styles: {
                'top': 120
            }
        });

        if (true == options.close) {
            var close = new Element('button', {
                class: 'close',
                events: {
                    click: function() {
                        that.hide();
                        return false;
                    }
                }
            })
            close.inject(message, 'top');
        }

        this.initMagicStounPanel();

        message.inject(document.body);
        this.message = message;
    },
    show: function() {
        this.message.removeClass('hide');
        
        var minWidth = this.message.getSize().x;
        var left = (window.getSize().x < minWidth) ? 0 : window.getSize().x / 2 - this.message.getSize().x / 2;

        this.message.setStyles({
            left: left,
            top: window.getScrollTop() + 50
        });

        this.options.sp.setStyles({
            width: '100%',
            height: '100%'
        }).removeClass('hide');

        $('container').setStyles({
            width: (window.getSize().x > minWidth) ? window.getSize().x - 100 : minWidth - 100,
            height: this.message.getSize().y + 200,
            overflow: 'hidden'
        })

        var that = this;

        if ('' != this.stounPanel.get('html')) {
            this.stounPanel.removeClass('hide');
            this.stounPanel.setStyle('top', this.message.getElement('.magicStoun').getPosition().y + 'px');
            this.stounPanel.setStyle('left', this.message.getElement('.magicStoun').getPosition().x + this.message.getSize().x - this.message.getStyle('padding-left').toInt() * 2 - 15 + 'px');
        }

        window.addEvent('resize', function() {
            if (!that.message.hasClass('hide')) {
                $('container').setStyles({
                    width: (window.getSize().x > minWidth) ? window.getSize().x - 100 : minWidth - 100,
                    height: that.message.getSize().y + 200,
                    overflow: 'hidden'
                });

                var left = (this.getSize().x < minWidth) ? 0 : this.getSize().x / 2 - that.message.getSize().x / 2;

                that.message.setStyles({
                    'left': left
                });

                that.options.sp.setStyles({
                    width: '100%',
                    height: '100%'
                });

                that.stounPanel.setStyle('top', that.message.getElement('.magicStoun').getPosition().y + 'px');
                that.stounPanel.setStyle('left', that.message.getElement('.magicStoun').getPosition().x + that.message.getSize().x - that.message.getStyle('padding-left').toInt() * 2 - 15 + 'px');
            }
        });
    },
    hide: function() {
        this.message.addClass('hide');
        this.options.sp.addClass('hide');
        $('container').setStyles({
            width: 'auto',
            height: 'auto',
            overflow: 'auto'
        });
        this.stounPanel.addClass('hide');
    },
    initMagicStounPanel: function() {
        var that = this;
        if ($$('.magicStounPanel').length) {
            this.stounPanel = $$('.magicStounPanel')[0];
        } else {
            this.stounPanel = new Element('div.magicStounPanel.hide', {
                html: '<h3>Магические камни</h3>' +
                        '<div><div><label>Тип: <select id="selectMagicStounList">' +

                            '<option value="0">Выберите тип</option>' +
                            '<option value="1">HP</option>' +

                        '</select></label></div>' +
                        '<div id="magicStounList">' +

                '</div></div>'

            }).inject(document.body);

            this.stounPanel.getElement('#selectMagicStounList').addEvent('change', function() {
                var magicStounList = $('magicStounList');
                magicStounList.empty();

                Array.each(that.armor.options.magicStouns[this.get('value').toInt()], function(el) {
                    new Element('div.magicStounItem.q' + el.q, {
                        html: el.name,
                        events: {
                            click: function() {
                                Object.each(el.skills, function(value, name) {
                                    if (null == that.armor.compare.selectStoun) {
                                        console.log('select stoun');
                                    } else {
                                        var i = that.armor.compare.selectStoun.get('data-stoun');
                                        that.armor.compare.item.skills.stoun[i] = {'name': name, 'value': value};
                                        that.armor.compare.selectStoun.addClass('q' + el.q);
                                    }
                                });
                                console.log(that.armor.compare.item.skills);
                                that.armor.compare.setPoint(that.armor.compare.item.point);
                            }
                        }
                    }).inject(magicStounList);
                })
            });
        }
    }
});

var ItemCompare = new Class({
    Implements: [Options],
    options: {
        conpareContainer: null
    },
    armor: null,

    message : null,
    item: null,

    selectStoun: null,

    initialize: function(options) {
        this.setOptions(options);
    },
    compare: function(item) {
        var that = this;
        if (undefined == item.point) {
            item.point = 0;
            item.skills.point = {};
        }

        if (item.stoun && item.stoun.count) {
            item.skills.stoun = {};
            item.selectStoun = {};
            for (var i = 1; i <= item.stoun.count; i++) {
                item.skills.stoun[i] = {};
            }
        }

        this.item = item;
        
        var html = this.compareHtml(item);
        this.message = new MessageBox({html: html, close: true});
        this.message.armor = this.armor;

        if ([2, 3, 4, 5, 6, 12, 13].indexOf(Number.from(item.slot)) != -1) {
            this.message.message.getElement('.up').addEvent('click', function() {
                if (Number.from(that.item.q) >= 5) {
                    if (that.item.point <= 14) that.setPoint(that.item.point + 1);
                } else {
                    if (that.item.point <= 9) that.setPoint(that.item.point + 1);
                }
            });

            this.message.message.getElement('.down').addEvent('click', function() {
                if (that.item.point >= 1) that.setPoint(that.item.point - 1);
            });
        }

        var buttonPanel = new Element('div.buttons-panel');

        new Element('button', {
            html: 'Добавить',
            class: 'gp-button',
            events: {
                click: function() {
                    that.armor.man.setItem(that.item);
                    that.message.hide();
                }
            }
        }).inject(buttonPanel);

        new Element('button', {
            html: 'Отмена',
            class: 'gp-button',
            events: {
                click: function() {
                    that.message.hide();
                }
            }
        }).inject(buttonPanel);

        // Выбор магических камней
        if (item.selectStoun) {
            this.message.message.getElements('.stoun').addEvent('click', function() {
                that.selectStoun = this;
                $$('.stoun.selectedItem').removeClass('selectedItem');
                this.addClass('selectedItem');
            }).addEvent('contextmenu', function() {
                this.removeClass('q0').removeClass('q1');
                var i = this.get('data-stoun');
                that.armor.compare.item.skills.stoun[i] = {};
                that.armor.compare.setPoint(that.armor.compare.item.point);
                return false;
            });
        }

        buttonPanel.inject(this.message.message);

        this.message.show();
    },
    setPoint: function(point) {
        point = Number.from(point);

        //if (this.item.point == point) return ;
        if (point < 0) point = 0;

        this.item.point = Number.from(point);

        if (!this.item.skills.point) {
            this.item.skills.point = {};
        }

        var slot = Number.from(this.item.slot);
        var type = Number.from(this.item.type);

        // Оружие и щиты
        if ([12, 13].indexOf(slot) != -1) {

            // 7: Копья, 8: Двуручные мечи, 13: Луки
            if ([7, 9, 13].indexOf(type) != -1) {
                this.item.skills.point['Атака'] = 4 * point;
            } else

            // 11: Булавы, 12: Посохи
            if ([11, 12].indexOf(type) != -1) {
                this.item.skills.point['Атака'] = 3 * point;
                this.item.skills.point['Сила магии'] = 20 * point;
            } else

            // 9: Мечи, 10: Кинжалы
            if ([9, 10].indexOf(type) != -1) {
                this.item.skills.point['Атака'] = 2 * point;
            } else

            // 14: Орбы, 15: Гримуары
            if ([14, 15].indexOf(type) != -1) {
                this.item.skills.point['Атака'] = 3 * point;
                this.item.skills.point['Сила магии'] = 20 * point;
            } else


            // 5: Щиты
            if (5 == type) {
                if (point <= 10) {
                    this.item.skills.point['Блок урона'] = 2 * point;
                    this.item.skills.point['Блок щитом'] = 0 * point;
                } else {
                    this.item.skills.point['Блок урона'] = 2 * point;
                    this.item.skills.point['Блок щитом'] = 30 * (point - 10);
                }
            }

        } else {

            // Тканые доспехи
            if (1 == type) {
                // Тело
                if (2 == slot) {
                    this.item.skills.point['Физ. защита'] = 3 * point;
                    this.item.skills.point['Макс. HP'] = 14 * point;
                    this.item.skills.point['Блок ф. крит.'] = 4 * point;
                } else

                // Штаны
                if (3 == slot) {
                    this.item.skills.point['Физ. защита'] = 2 * point;
                    this.item.skills.point['Макс. HP'] = 12 * point;
                    this.item.skills.point['Блок ф. крит.'] = 3 * point;
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    this.item.skills.point['Физ. защита'] = 1 * point;
                    this.item.skills.point['Макс. HP'] = 10 * point;
                    this.item.skills.point['Блок ф. крит.'] = 2 * point;
                }
            } else

            // Кожаные доспехи
            if (2 == type) {
                // Тело
                if (2 == slot) {
                    this.item.skills.point['Физ. защита'] = 4 * point;
                    this.item.skills.point['Макс. HP'] = 12 * point;
                    this.item.skills.point['Блок ф. крит.'] = 4 * point;
                } else

                // Штаны
                if (3 == slot) {
                    this.item.skills.point['Физ. защита'] = 3 * point;
                    this.item.skills.point['Макс. HP'] = 10 * point;
                    this.item.skills.point['Блок ф. крит.'] = 3 * point;
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    this.item.skills.point['Физ. защита'] = 2 * point;
                    this.item.skills.point['Макс. HP'] = 10 * point;
                    this.item.skills.point['Блок ф. крит.'] = 2 * point;
                }
            } else

            // Кольчужные доспехи
            if (3 == type) {
                // Тело
                if (2 == slot) {
                    this.item.skills.point['Физ. защита'] = 5 * point;
                    this.item.skills.point['Макс. HP'] = 10 * point;
                    this.item.skills.point['Блок ф. крит.'] = 4 * point;
                } else

                // Штаны
                if (3 == slot) {
                    this.item.skills.point['Физ. защита'] = 4 * point;
                    this.item.skills.point['Макс. HP'] = 8 * point;
                    this.item.skills.point['Блок ф. крит.'] = 3 * point;
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    this.item.skills.point['Физ. защита'] = 3 * point;
                    this.item.skills.point['Макс. HP'] = 6 * point;
                    this.item.skills.point['Блок ф. крит.'] = 2 * point;
                }
            } else

            // Латные доспехи
            if (4 == type) {
                // Тело
                if (2 == slot) {
                    this.item.skills.point['Физ. защита'] = 6 * point;
                    this.item.skills.point['Макс. HP'] = 8 * point;
                    this.item.skills.point['Блок ф. крит.'] = 4 * point;
                } else

                // Штаны
                if (3 == slot) {
                    this.item.skills.point['Физ. защита'] = 5 * point;
                    this.item.skills.point['Макс. HP'] = 6 * point;
                    this.item.skills.point['Блок ф. крит.'] = 3 * point;
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    this.item.skills.point['Физ. защита'] = 4 * point;
                    this.item.skills.point['Макс. HP'] = 4 * point;
                    this.item.skills.point['Блок ф. крит.'] = 2 * point;
                }
            }
        }

        // Скилы
        var that = this;
        var item = this.item;
        var skills = {};

        this.message.message.getElement('h3>span>i').set('text', (point > 0) ? '+' + point : point);

        var mainSkill = (item.skills.main && Object.getLength(item.skills.main)) ? Object.clone(item.skills.main) : {};
        var pointSkill = (item.skills.point && Object.getLength(item.skills.point)) ? Object.clone(item.skills.point) : {};
        var stounSkill = (item.skills.stoun && Object.getLength(item.skills.stoun)) ? Object.clone(item.skills.stoun) : {};

        if (Object.getLength(stounSkill)) {
            var s = {};
            Object.each(stounSkill, function(i) {
                if (Object.getLength(i))
                    s[i.name] = (!s[i.name]) ? Number.from(i.value) : s[i.name] + Number.from(i.value);
            });
            stounSkill = s;
        }

        if (Object.getLength(mainSkill)) Object.each(mainSkill, function(value, name) {
            that.message.message.getElement("[data-skill='" + name + "']").set('html', name + ' <span>' + value + '</span>');
        });

        that.message.message.getElements("[data-skillType='point']").destroy();
        //html += '<div class="skill" data-skillType="main" data-skill="main[' + name + ']">' + name + ' <span>' + value + '</span></div>';

        if (Object.getLength(pointSkill)) Object.each(pointSkill, function(value, name) {
            if (!value) return;
            var skillItem = that.message.message.getElement("[data-skill='" + name + "']>span");
            if (null != skillItem) {
                skillItem.set('text', skillItem.get('text') + ' (+' + value + ')');
            } else {
                new Element('div.skill', {
                    'data-skillType': 'point',
                    'data-skill': name,
                    html: name + ' <span>(+' + value + ')</span>'
                }).inject(that.message.message.getElement("[skill='main']>.clear"), 'before');
            }
        });

        if (Object.getLength(stounSkill)) Object.each(stounSkill, function(value, name) {
            if (!value) return;
            var skillItem = that.message.message.getElement("[data-skill='" + name + "']>span");
            if (null != skillItem) {
                skillItem.set('text', skillItem.get('text') + ' (+' + value + ')');
            } else {
                new Element('div.skill', {
                    'data-skillType': 'point',
                    'data-skill': name,
                    html: name + ' <span>(+' + value + ')</span>'
                }).inject(that.message.message.getElement("[skill='main']>.clear"), 'before');
            }
        });

    },
    compareHtml: function(item) {
        // PvP атака и PvP защита в доп параметры
        if (0 != item.pvp_atack) item.skills.other['PvP атака'] = item.pvp_atack;
        if (0 != item.pvp_protect) item.skills.other['PvP защита'] = item.pvp_protect;

        var html = '';
        
        html += '<div class="compare span-11">';

        // Картинка
        html += '<div style="float: left; margin-right: 10px"><img src="' + item.smallimage + '" /></div>';

        // Основные блоки
        html += '<div class="span-9">';

        // Заголовок
        html += '<div class="block">' +
            '<h3>' + item.name;

        if ([2, 3, 4, 5, 6, 12, 13].indexOf(Number.from(item.slot)) != -1) {
            html += ' <span><i>' + ((item.point) ? '+' + item.point : '0') + '</i> <button class="up"></button><button class="down"></button></span>';
        }

        html += '</h3>' +
            '<div class="type">Тип <span>' + this.armor.options.types.itemsType[item.type] + '</span></div>' +
            ((item.info) ? '<div>' + item.info + '</div>' : '') +
            '<div>Можно использовать с ' + item.lvl + '-го уровня.</div>' +
        '</div><hr />';

        // Скилы
        if (Object.getLength(item.skills.main)) {
            html += '<div class="block" skill="main">';

            var pointSkill = (item.skills.point && Object.getLength(item.skills.point)) ? Object.clone(item.skills.point) : {};

            Object.each(item.skills.main, function(value, name) {
                if (pointSkill[name]) {
                    value += ' (+' + item.skills.point[name] + ')';
                    delete(pointSkill[name]);
                }
                html += '<div class="skill" data-skillType="main" data-skill="' + name + '">' + name + ' <span>' + value + '</span></div>';
            });

            if (Object.getLength(pointSkill)) {
                Object.each(pointSkill, function(value, name) {
                    html += '<div class="skill" data-skillType="main" data-skill="' + name + '">' + name + ' <span>+' + value + '</span></div>';
                });
            }

            html += '<div class="clear"></div>' +
            '</div><hr />';
        }

        // Доп скилы
        if (Object.getLength(item.skills.other)) {
            html += '<div class="block" skill="other">';

            Object.each(item.skills.other, function(value, name) {
                html += '<div class="skill" data-skill="other[' + name + ']">' + name + ' <span>' + value + '</span></div>';
            });

            html += '<div class="clear"></div>' +
            '</div><hr />';
        }

        // Магические камни
        if (item.stoun.count) {
            html += '<div class="block magicStoun">' +
                '<div>Можно усилить магическими камнями ' + item.stoun.lvl + '-го уровня и ниже.</div><br />';

            for (var i = 0; i < item.stoun.count; i++) {
                html += '<div class="stoun" data-stoun="' + i + '"></div>';
            }

            html += '</div><hr />';
        }

        if (1 == item.longatack) {
            html += '<div class="block">Дистанция атаки увеличина.</div><hr />';
        }

        if (1 == item.magicstoun) {
            html += '<div class="block">Можно вставить божественный камень.</div><hr />';
        }

        // Комплект
        if (item.complect && Object.getLength(item.complect)) {
            html += '<div class="block"><div>' + item.complect.name + '</div>';
            Array.each(item.complect.items, function(el) {
                html += '<div class="complect-item"><a href="#">' + el + '</a></div>';
            })
            html += '</div><hr />';


            html += '<div class="block">';
            Object.each(item.complect.pieces, function(el, name) {
                html += '<div>' + name + ' Кусочки: ';
                Object.each(el, function(value, name) {
                    html += name + ' +' + value + ', ';
                })
                html += '</div>';
            })
            html += '</div>';
        }

        html += '</div></div>';
        return html;
    }
});