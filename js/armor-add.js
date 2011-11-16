var ArmorAdd = new Class({
    Implements: [Options],
    options: {
        skillTables: null,

        skills: {}
    },

    initialize: function(options) {
        this.setOptions(options);

    },
    addSkillRow: function(table) {
        var select = new Element('select#id', {

        });
        Object.each(this.options.skills, function(value, i) {
            new Element('option', {
                'value': i,
                'html': value
            }).inject(select);
        }).inject(table.getElement('tbody tr td:last-child'));
        //table.getElement('tbody tr td:last-child')
    }
})