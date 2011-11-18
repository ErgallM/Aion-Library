var Armor = new Class({
    Implements: [Options],

    options: {

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
        image: ''
    },

    initialize: function(options) {
        this.setOptions(options);
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
            console.log(item);
            new Element('div', {
                html: '<i>' + i + '</i><br>' + post,
                events: {
                    click: function() {
                        console.log(i, post);
                    }
                }
            }).inject(that.options.container);
        });
    }
})