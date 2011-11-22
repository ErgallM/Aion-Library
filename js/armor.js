var Armor = new Class({
    Implements: [Options],

    options: {
        skills: {},

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
        price: {},
        icon: '',
        image: '',

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
                    that.calculatePoint(1);
                }
            }
        }).inject(titleSpan);
        var titleSpanPointDown = new Element('button.down', {
            events:{
                click: function() {
                    that.calculatePoint(-1);
                }
            }
        }).inject(titleSpan);
        titleSpan.inject(title);

        title.inject(blockName);

        new Elements({
            'div.type': {html: '<span>Тип: </span>' + item.type},
            'div': {html: 'Можно использовать с ' + item.lvl + '-го уровня.'}
        }).inject(blockName);

        blockName.inject(compare);
        /* End block Name */

        var armorSkills = this.armor.options.skills;

        /* start Skills Block */
        Object.each(item.skills, function(skills, skillType) {
            var blockSkills = new Element('div.block'/*, {html: 'Type=' + skillType}*/);

            Object.each(skills, function(skill) {
                new Element('div.skills', {html: '<span>' + armorSkills[skill.name] + '</span> ' + skill.value}).inject(blockSkills);
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
    calculatePoint: function(point) {
        this.options.point += Number.from(point);
        if (this.options.point < 0) this.options.point = 0;
        if (this.options.point > 15) this.options.point = 15;

        console.log(this.options.point);
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
                html: '<img src="' + item.options.icon + '"> ' + item.options.name,
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