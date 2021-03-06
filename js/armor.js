var Armor = new Class({
    Implements: [Options],

    options: {
        skills: {},
        types: {},
        manastone: {},

        searchItems: {},

        man: {}
    },

    searchItems: null,

    manastone: null,

    man: null,

    initialize: function(options) {
        this.setOptions(options);

        if (this.options.searchItems) {
            this.searchItems = new SearchItems(this.options.searchItems);
            this.searchItems.armor = this;
        }

        if (this.options.manastone) {
            this.manastone = new Manastone(this.options.manastone);
            this.manastone.armor = this;
        }

        if (this.options.man) {
            this.man = new Man(this.options.man);
            this.man.armor = this;
        }
    }
});

var Manastone = new Class({
    Implements: [Options],
    options: {},

    armor: null,

    initialize: function(options) {
        this.setOptions(options);
    },

    hide: function() {
        if ($('manastone')) $('manastone').addClass('hide');
    },

    /** Generate manastone dialog box */
    generateDialogBox: function(manastoneBlock, itemContainer) {
        var that = this;
        var item = itemContainer.options;

        var di = $('manastone');
        var lvl = item.manastoneLvl;

        var position = manastoneBlock.getPosition();
        position.x += 50;

        if (di) {
            di.position(position);
            di.set('html', '');
        } else {
            var di = new Element('div#manastone.dialog.hide');
            di.position(position);
        }

        var diUl = new Element('ul');
        Object.each(this.options, function(manastone, manastoneName) {
            var diUlLi = new Element('li', {html: manastoneName + '<span>►</span>'});
            var diUlLiUl = new Element('ul.dialog');
            Object.each(manastone, function(el) {
                var li = new Element('li', {
                    text: el.name,
                    class: el.icon,
                    'data-lvl': el.lvl,
                    events: {
                        click: function(e) {
                            // set skills
                            item.skills.manastone = item.skills.manastone || {};
                            item.skills.manastone[manastoneBlock.get('data-manastone')] = item.skills.manastone[manastoneBlock.get('data-manastone')] || {}
                            item.skills.manastone[manastoneBlock.get('data-manastone')][el.skillName] = el.skillValue;

                            console.log(manastoneBlock, manastoneBlock.get('data-manastone'));

                            // update skill
                            itemContainer.calculateItemSkills();

                            // set icon and text
                            manastoneBlock.set('text', el.name);
                            if (!manastoneBlock.hasClass(el.icon)) {
                                manastoneBlock.addClass(el.icon);
                                manastoneBlock.removeClass(('green' == el.icon) ? 'white' : 'green');
                            }

                            that.hide();

                            if (e) e.stop();
                            return false;
                        }
                    }
                });
                li.inject(diUlLiUl);
            })
            diUlLiUl.inject(diUlLi); diUlLi.inject(diUl);
        });

        new Element('li', {
            text:'Очистить',
            events:{
                click:function (e) {
                    var el = manastoneBlock;

                    // delete skills
                    item.skills.manastone = item.skills.manastone || {};
                    delete item.skills.manastone[manastoneBlock.get('data-manastone')];

                    // update skill
                    itemContainer.calculateItemSkills();

                    // set icon and text
                    manastoneBlock.set('text', '');
                    manastoneBlock.removeClass('white').removeClass('green');

                    that.hide();

                    if (e) e.stop();
                    return false;
                }
            }
        }).inject(diUl);

        new Element('li', {
            text: 'Отмена',
            class: 'close',
            events: {
                click: function(e) {
                    di.addClass('hide');

                    if (e) e.stop();
                    return false;
                }
            }
        }).inject(diUl);

        diUl.inject(di);


        return di;
    }
})

var Item = new Class({
    Implements: [Options, Events],
    options: {
        id: null,
        name: '',
        lvl: 0,
        type: 0,
        slot: 0,
        q: 0,
        skills: {},
        manastoneLvl: 0,
        manastoneCount: 0,
        godstone: false,
        longattack: false,
        complect: {},
        price: {},
        icon: '',
        image: '',
        textBlock: {},

        /** Точка */
        point: 0
    },

    armor: null,

    childItem: null,

    initialize: function(options) {
        this.setOptions(options);

        if (this.options.manastoneCount > 6) this.options.manastoneCount = 6;
    },

    /**
     * @return Element
     */
    createCompareDialog: function() {
        var that = this;
        var item = this.options;
        var compare = new Element('div#compare-' + item.id + '.compare.hide');
        if (item.icon) compare.setStyle('background-image', 'url("/images/items/icons/' + item.icon + '")');

        var closeButton = new Element('button', {
            events: {
                click: function(e) {
                    compare.addClass('hide');

                    that.armor.manastone.hide();
                    
                    if (e) e.stop();
                    return false;
                },
                show: function(e) {
                    that.show();

                    if (e) e.stop();
                    return false;
                },
                hide: function(e) {
                    that.hide();

                    if (e) e.stop();
                    return false;
                }
            }
        }).inject(new Element('div.close').inject(compare));

        /* Start block Name */
        var blockName = new Element('div.block');
        var title = new Element('h3.q' + item.q, {html: item.name});
        var titleSpan = new Element('span');
        var titleSpanI = new Element('i', {html: item.point}).inject(titleSpan);
        var titleSpanPointUp = new Element('button.up', {
            events: {
                click: function () {
                    that.calculatePoint(1, titleSpanI);
                }
            }
        }).inject(titleSpan);
        var titleSpanPointDown = new Element('button.down', {
            events:{
                click: function() {
                    that.calculatePoint(-1, titleSpanI);
                }
            }
        }).inject(titleSpan);
        titleSpan.inject(title);

        title.inject(blockName);

        new Element('div.type', {html: '<span>Тип: </span>' + this.armor.options.types[item.type]}).inject(blockName);
        new Element('div', {html: 'Можно использовать с ' + item.lvl + '-го уровня.'}).inject(blockName);

        blockName.inject(compare);
        /* End block Name */

        var armorSkills = this.armor.options.skills;

        /* start Skills Block */
        var compareSkills = this.calculateSkills(true);
        Object.each(compareSkills, function(skills, skillType) {
            var blockSkills = new Element('div#compare-' + item.id + '-skills-' + skillType + '.block'/*, {html: 'Type=' + skillType}*/);

            if ('set' == skillType) {
                Object.each(skills, function(setSkills, setPiece) {
                    var skillSetPiece = new Element('div', {html: setPiece + '&nbsp;&mdash;&nbsp;'});
                    Object.each(setSkills, function (value, name) {
                        skillSetPiece.set('html', skillSetPiece.get('html') + armorSkills[name] + ':&nbsp;' + value + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
                    });
                    skillSetPiece.inject(blockSkills);
                });
            } else {
                Object.each(skills, function(value, name) {
                    new Element('div.skills', {'data-skill': name, html: '<span>' + armorSkills[name] + '</span> ' + value}).inject(blockSkills);
                });
            }

            new Element('div.clear').inject(blockSkills);
            blockSkills.inject(compare);
        });
        /* end Skills Block */

        /* start Manastone Block */
        if (item.manastoneCount) {
            var blockManastone = new Element('div.block');
            new Element('div.title', {html: 'Можно усилить магическими камнями ' + item.manastoneLvl + '-го уровня и ниже.'}).inject(blockManastone);
            for (var i = 0; i < item.manastoneCount; i++) {
                new Element('div.manastone', {
                    'data-manastone': i,
                    events: {
                        click: function(e) {
                            var di = that.armor.manastone.generateDialogBox(this, that);
                            di.inject(document.body).removeClass('hide');

                            if (e) e.stop();
                            return false;
                        }
                    }
                }).inject(blockManastone);
            }
            new Element('div.clear').inject(blockManastone);
            blockManastone.inject(compare);
        }
        /* end Manastone Block */

        /* start Godstone */
        if (item.godstone) {
            new Element('div.block', {html: 'Можно вставить божественный камень.'}).inject(compare);
        }
        /* end Godstone */

        /* start ButtonPanel */
        var buttonPanel = new Element('div.block.buttonsPanel');
        new Element('button.button', {
            text: 'Очистить',
            events: {
                click: function(e) {

                    if (e) e.stop();
                    return false;
                }
            }
        }).inject(buttonPanel);
        new Element('button.button', {
            text: 'Одень | Снять',
            events: {
                click: function(e) {
                    var itemContainer = $$('.item.active')[0];
                    if (!itemContainer) return false;

                    var itemId = itemContainer.get('id');

                    // @todo Сравнение и переодевание итемов (если итем уже одет, нужно переодеть его, а не снять)

                    if (that.armor.man.hasItem(itemId)) {
                        that.armor.man.clearItem(itemId);
                        itemContainer.setStyle('background-image', '');
                    } else {
                        that.armor.man.setItem(itemId, that);
                        itemContainer.setStyle('background-image', compare.getStyle('background-image'));
                    }

                    if (e) e.stop();
                    return false;
                }
            }
        }).inject(buttonPanel);

        new Element('button.button', {
            text: 'Отмена',
            events: {
                click: function(e) {
                    that.hide();

                    that.armor.manastone.hide();
                    
                    if (e) e.stop();
                    return false;
                }
            }
        }).inject(buttonPanel);
        buttonPanel.inject(compare);
        /* end ButtonPanel */

        return compare;
    },

    /** Show item compare dialog */
    show: function() {
        var item = (null == $('compare-' + this.options.id)) ? this.createCompareDialog().inject(document.body) : $('compare-' + this.options.id);
        item.removeClass('hide');
        return item;
    },
    /** Hide item compare dialog */
    hide: function() {
        var item = $('compare-' + this.options.id);
        if (item) item.addClass('hide');
        return item;
    },

    /** Calculate point */
    calculatePoint: function(point, pointTitle) {
        var item = this.options;
        item.point += Number.from(point);
        if (item.point < 0) item.point = 0;
        if (item.point > 15) item.point = 15;

        var slot = Number.from(item.slot);
        var type = Number.from(item.type);

        if (0 == item.point) {
            item.skills['point'] = {};
            pointTitle.set('text', item.point);
            this.calculateSkills(false);
            return true;
        }

        // Оружие и щиты
        if ([12, 13].indexOf(slot) != -1) {
            // 7: 'Копья', 8: 'Двуручные мечи', 13: 'Луки',
            if ([7, 8, 13].indexOf(type) != -1) {
                this.options.skills['point'] = {1: 4 * this.options.point}; // Атака х4
            } else

            // 11: Булавы, 12: Посохи
            if ([11, 12].indexOf(type) != -1) {
                this.options.skills['point'] = {
                    1: 3 * this.options.point, // Атака х3
                    10: 20 * this.options.point // Сила магии х20
                };
            } else

            // 9: Мечи, 10: Кинжалы
            if ([9, 10].indexOf(type) != -1) {
                this.options.skills['point'] = {1: 3 * this.options.point}; // Атака х3
            } else

            // 14: Орбы, 15: Гримуары
            if ([14, 15].indexOf(type) != -1) {
                this.options.skills['point'] = {
                    1: 3 * this.options.point, // Атака х3
                    10: 20 * this.options.point // Сила магии х20
                };
            } else

            // 5: Щиты
            if (5 == type) {
                if (point <= 10) {
                    this.options.skills['point'] = {15: 2 * this.options.point}; // Блок урона x2
                } else {
                    this.options.skills['point'] = {
                        15: 2 * this.options.point, // Блок урона x2
                        16: 30 * (this.options.point - 10) // Блок щитом x30 (1-5)
                    };
                }
            }
        } else {
            // Тканые доспехи
            if (1 == type) {
                // Тело
                if (2 == slot) {
                    item.skills['point'] = {
                        19: 3 * this.options.point,      // Физ. защита x3
                        29: 14 * this.options.point,     // Макс. HP x14
                        17: 4 * this.options.point       // Блок ф. крит. x4
                    };
                } else

                // Штаны
                if (3 == slot) {
                    item.skills['point'] = {
                        19: 2 * this.options.point,     // Физ. защита x2
                        29: 12 * this.options.point,    // Макс. HP x12
                        17: 3 * this.options.point      // Блок ф. крит. x3
                    };
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    item.skills['point'] = {
                        19: 1 * this.options.point,     // Физ. защита x1
                        29: 10 * this.options.point,    // Макс. HP x10
                        17: 2 * this.options.point      // Блок ф. крит. x2
                    };
                }

            } else

            // Кожаные доспехи
            if (2 == type) {
                // Тело
                if (2 == slot) {
                    item.skills['point'] = {
                        19: 4 * this.options.point,     // Физ. защита x4
                        29: 12 * this.options.point,    // Макс. HP x12
                        17: 4 * this.options.point      // Блок ф. крит. x4
                    };
                } else

                // Штаны
                if (3 == slot) {
                    item.skills['point'] = {
                        19: 3 * this.options.point,     // Физ. защита x3
                        29: 10 * this.options.point,    // Макс. HP x10
                        17: 3 * this.options.point      // Блок ф. крит. x3
                    };
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    item.skills['point'] = {
                        19: 2 * this.options.point,     // Физ. защита x2
                        29: 10 * this.options.point,    // Макс. HP x10
                        17: 2 * this.options.point      // Блок ф. крит. x2
                    };
                }
            } else

            // Кольчужные доспехи
            if (3 == type) {
                // Тело
                if (2 == slot) {
                    item.skills['point'] = {
                        19: 5 * this.options.point,     // Физ. защита x5
                        29: 10 * this.options.point,    // Макс. HP x10
                        17: 4 * this.options.point      // Блок ф. крит. x4
                    };
                } else

                // Штаны
                if (3 == slot) {
                    this.options.skills['point'] = {
                        19: 4 * this.options.point,     // Физ. защита x4
                        29: 8 * this.options.point,     // Макс. HP x8
                        17: 3 * this.options.point      // Блок ф. крит. x3
                    };
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    item.skills['point'] = {
                        19: 3 * this.options.point,     // Физ. защита x3
                        29: 6 * this.options.point,     // Макс. HP x6
                        17: 2 * this.options.point      // Блок ф. крит. x2
                    };
                }
            } else

            // Латные доспехи
            if (4 == type) {
                // Тело
                if (2 == slot) {
                    item.skills['point'] = {
                        19: 6 * this.options.point,     // Физ. защита x6
                        29: 8 * this.options.point,     // Макс. HP x8
                        17: 4 * this.options.point      // Блок ф. крит. x4
                    };
                } else

                // Штаны
                if (3 == slot) {
                    this.options.skills['point'] = {
                        19: 5 * this.options.point,     // Физ. защита x5
                        29: 6 * this.options.point,     // Макс. HP x6
                        17: 3 * this.options.point      // Блок ф. крит. x3
                    };
                } else

                // Ботинки, Наплечники, Перчатки
                if ([4, 5, 6].indexOf(slot) != -1) {
                    item.skills['point'] = {
                        19: 4 * this.options.point,     // Физ. защита x4
                        29: 4 * this.options.point,     // Макс. HP x4
                        17: 2 * this.options.point      // Блок ф. крит. x2
                    };
                }
            }
        }

        pointTitle.set('text', '+' + item.point);
        this.calculateSkills(false);
    },

    calculateSkills: function(r) {
        var item = this.options;
        var compareSkills = Object.clone(item.skills);

        if (compareSkills.point) {
            Object.each(compareSkills.point, function (value, name) {
                if (undefined == compareSkills.main[name]) {
                    compareSkills.main[name] = '(+' + value + ')';
                } else {
                    compareSkills.main[name] += '(+' + value + ')'
                }
            });
        }

        if (r) return compareSkills;

        var armorSkills = this.armor.options.skills;

        var dialogSkills = $$('#compare-' + item.id + '-skills-main>div');
        Array.each(dialogSkills, function(e) {
            var name = e.get('data-skill');
            if (compareSkills[name]) {
                e.set('html', '<span>' + armorSkills[name] + '</span> ' + compareSkills[name]);
                compareSkills[name] = null;
            } else {
                if (!e.hasClass('clear')) e.destroy();
            }
        });

        Object.each(compareSkills.main, function (value, name) {
            new Element('div.skills', {'data-skill':name, html:'<span>' + armorSkills[name] + '</span> ' + value}).inject($$('#compare-' + item.id + '-skills-main>div.clear')[0], 'before');
        });
    },

    /** Calculate all item skills */
    calculateItemSkills: function() {
        var skills = {}; skills['set'] = {};
        var self = this;
        Object.each(self.options.skills, function(value, name) {
            if ('set' == name) {
                Object.each(value, function(pieceSkills, pieceName) {
                    Object.each(pieceSkills, function (skillValue, skillName) {
                        if (!skills['set'][pieceName]) skills['set'][pieceName] = {};
                        if (!skills['set'][pieceName][skillName]) skills['set'][pieceName][skillName] = 0;
                        if (Number.from(skillValue)) skills['set'][pieceName][skillName] += Number.from(skillValue);
                    });
                })
            } else {
                Object.each(value, function(skillValue, skillName) {
                    if (!skills[skillName]) skills[skillName] = 0;
                    if (Number.from(skillValue)) skills[skillName] += Number.from(skillValue);
                });
            }
        });

        return skills;
    }

})

var SearchItems = new Class({
    Implements:[Options],
    options: {
        panel: null,
        request: null,
        filterForm: null,
        loader: null,
        container: null,
        spy: {
            start: 0,
            step: 50,
            spy: null
        },

        filterFormData: '',
        items: null
    },
    initialize: function(options) {
        this.setOptions(options);

        var self = this;

        self.options.panel.getElement('.close').getElement('button').addEvent('click', function(e) {
            self.options.panel.addClass('hide');

            if (e) e.stop();
            return false;
        })

        self.options.request = new Request.JSON({
            url: self.options.filterForm.get('action'),
            method: 'post',
            noCache: true,
            onRequest: function() {
                // show loading
                self.options.loader.removeClass('hide');
            },
            onSuccess: function(jsonItems) {
                self.options.spy.start += self.options.spy.step;

                // show items;
                self.showItemsList(jsonItems);

                self.spyAct();

                self.options.loader.addClass('hide');
            },
            onFailure: function () {self.options.loader.addClass('hide');},
            onError: function () {self.options.loader.addClass('hide');}
        });

        self.options.filterForm.addEvent('submit', function(e) {
            var data = this.serialize(true);
            // Проверка на изменения в форме

            if (self.options.filterFormData != Object.toQueryString(data)) {
                // Очистка данных
                self.options.container.set('html', '');
                self.options.spy.start = 0;
            }
            self.options.filterFormData = Object.toQueryString(data);

            data['start'] = self.options.spy.start;
            data['count'] = self.options.spy.step;

            //console.log(data);

            self.options.request.send({
                data: {
                    data: data
                }
            })

            if (e) e.stop();
            return false;
        });

        self.options.items.addEvent('click', function(e) {
            self.options.filterForm.getElementById('slot').set('value', this.get('data-slot'));
            var activeItem = $$('.item.active');
            if (activeItem && activeItem[0] != this) {
                activeItem.removeClass('active');
            }
            this.addClass('active');

            self.open();

            if (e) e.stop();
            return false;
        })
    },

    showItemsList: function(jsonItems) {
        var self = this;
        Object.each(jsonItems, function(itemData, i) {
            var item = new Item(itemData);
            item.armor = self.armor;

            new Element('div', {
                html: '<img src="/images/items/icons/' + item.options.icon + '"> ' + item.options.name,
                class: 'q' + item.options.q,
                events: {
                    click: function() {
                        console.log(item);
                        item.show();
                    }
                }
            }).inject(self.options.container);
        })
    },

    spyAct: function() {
        var self = this;
        var spyContainer = self.options.container;
        var filter = self.options.filterForm;

        var min = spyContainer.getScrollSize().y - spyContainer.getSize().y;
        self.options.spy.spy = new ScrollSpy({
            container: spyContainer,
            min: min,
            onEnter: function() {
                filter.fireEvent('submit');
            }
        })
    },

    open: function() {
        this.options.panel.removeClass('hide');
        this.options.filterForm.fireEvent('submit');
    }
});

var Man = new Class({
    Implements:[Options],
    options: {
        items: {},
        container: null
    },
    initialize: function(options) {
        this.setOptions(options);
    },

    setItem: function(itemName, item) {
        this.options.items[itemName] = item;
        this.calculateSkills();
        return this;
    },
    clearItem: function(itemName) {
        delete this.options.items[itemName];
        this.calculateSkills();
        return this;
    },
    hasItem: function(itemName) {
        var self = this;

        return (Object.keys(self.options.items).indexOf(itemName) >= 0) ? true : false;
    },
    calculateSkills: function() {
        var skills = {};
        var self = this;
        var armorSkills = self.armor.options.skills;

        var setSkills = {};

        Object.each(this.options.items, function(item, itemName) {
            skills = Object.merge(skills, item.calculateItemSkills());

            // Сет шмоток
            if (item.skills['set']) {
                var piece = 1;
                Object.each(self.options.items, function(i, iName) {
                    if (item.complect.items.indexOf(i.name)) {
                        // Подсчет кусочков сета
                        piece++;
                    }
                });

                // Подсчет скилов сета
                for (var i = 1; i <= piece; i++) {
                    if (item.skills['set'][i]) {
                        if (!setSkills[item.complect.name]) setSkills[item.complect.name] = {};
                        setSkills[item.complect.name] = Object.compare(setSkills[item.complect.name], item.skills['set'][i]);
                    }
                }
                // Подсчет всего сета
                if (piece == Array.length) {
                    if (!setSkills[item.complect.name]) setSkills[item.complect.name] = {};
                    setSkills[item.complect.name] = Object.compare(setSkills[item.complect.name], item.skills['set']['all']);
                }
            }
        });

        console.log('setSkills', setSkills);

        self.options.container.set('html', '');

        Object.each(skills, function(skillValue, skillName) {
            if ('set' != skillName) {
                new Element('div', {html: armorSkills[skillName] + ': ' + skillValue}).inject(self.options.container);
            }
        });
    }
})