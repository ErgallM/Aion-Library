var Armor = new Class({
    Implements: [Options],

    options: {
        skills: {},
        types: {},

        searchItems: {}
    },

    searchItems: null,

    initialize: function(options) {
        this.setOptions(options);

        if (this.options.searchItems) {
            this.searchItems = new SearchItems(this.options.searchItems);
            this.searchItems.armor = this;
        }
    }
});

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

            Object.each(skills, function(value, name) {
                new Element('div.skills', {'data-skill': name, html: '<span>' + armorSkills[name] + '</span> ' + value}).inject(blockSkills);
            });

            new Element('div.clear').inject(blockSkills);
            blockSkills.inject(compare);
        });
        /* end Skills Block */

        /* start Manastone Block */
        if (item.manastoneCount) {
            var blockManastone = new Element('div.block');
            new Element('div.title', {html: 'Можно усилить магическими камнями ' + item.manastoneLvl + '-го уровня и ниже.'}).inject(blockManastone);
            for (var i = 0; i < item.manastoneCount; i++) {
                new Element('div.manastone').inject(blockManastone);
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
    }
})

var SearchItems = new Class({
    Implements: [Options],
    options: {
        panel: null,
        items: null,

        container: null,
        filterForm: null,

        scrollLoader: null,
        request: null
    },

    initialize: function(options) {
        this.setOptions(options);

        this.options.panel.addEvents({
            show: function() {
                console.log('show');
                this.setStyle('left', 320);
            },
            hide: function() {
                console.log('hide');
                this.setStyle('left', '');
            }
        });

        var that = this;

        this.options.items.addEvent('click', function() {
            var searchPanel = that.options.panel;
            if (0 >= searchPanel.getStyle('left').toInt()) {
                searchPanel.fireEvent('show');
            } else {
                searchPanel.fireEvent('hide');
            }
        });

        this.options.scrollLoader = new ScrollSpy({
            container: that.options.container,
            min: that.options.container.getScrollSize().y - that.options.container.getSize().y - 150,
            onEnter: function() {
                console.log('enter');
            }
        });

        this.options.request = new Request.JSON({
            url: that.options.filterForm.get('action'),
            method: 'post',
            noCache: true,
            onRequest: function() {
                console.log('request start');
            },
            onSuccess: function(responseJSON) {
                console.log('request success', responseJSON);

                that.postHeader(responseJSON);
                //reset the message
                //loadMore.set('text','Load More');
                //increment the current status
                //start += desiredPosts;
                //add in the new posts
                //postHandler(responseJSON);
                //spy calc!
                //spyAct();
            },
            onFailure: function() {
                console.log('request failure');
            },
            onComplete: function() {
                console.log('request complete');
            }
        });

        this.options.filterForm.addEvent('submit', function(e) {
            that.options.request.send({
                data: this.serialize(true)
            });
            
            if (e) e.stop();
            return false;
        })
    },

    // Построение и вывод списка итемов в поисковое окно
    postHeader: function(postsJSON) {
        var that = this;
        Object.each(postsJSON, function(post,i) {
            var item = new Item(post);
            item.armor = that.armor;

            new Element('div', {
                html: '<img src="/images/items/icons/' + item.options.icon + '"> ' + item.options.name,
                class: 'q' + item.options.q,
                events: {
                    click: function() {
                        $$('.compare').fireEvent('hide');
                        
                        item.show();
                    }
                }
            }).inject(that.options.container);
        });
    }
})