var Armor = new Class({
    Implements: [Options],

    options: {

    }
});

var SearchItems = new Class({
    Implements: [Options],
    options: {
        panel: null,
        items: null
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
    }
})