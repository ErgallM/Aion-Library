var ArmorAdd = new Class({
    Implements: [Options],
    options: {
        skillTables: null,
        skillsEnchantment: null,
        addItemForm: null,

        skills: {}
    },

    initialize: function(options) {
        this.setOptions(options);

        var that = this;

        this.options.skillTables.each(function(table) {
            table.getElement('.add').addEvent('click', function(e) {
                that.addSkillRow(table);
                if (e) e.stop();
                return false;
            })
        });

        // Зачарование
        this.options.skillsEnchantment.addEvent('click', function(e) {
            var table = this.getParent().getParent().getElement('table');
            table.toggleClass('hide');

            if (e) e.stop();
            return false;
        });

        this.options.addItemForm.addEvent('submit', function(e) {
            var data = this.serialize(true);

            

            if (e) e.stop();
            return false;
        })
    },
    // Создание строки с указанием скилов
    addSkillRow: function(table) {
        var i = Number.from(table.get('data-items')) + 1;
        var type = table.get('data-type');

        var tr = new Element('tr');
        var td1 = new Element('td').inject(tr);
        var td2 = new Element('td').inject(tr);
        var td3 = new Element('td').inject(tr);

        var select = new Element('select', {
            id: 'skills-' + type + '-' + i + '-name',
            name: 'skills[' + type + '][' + i + '][name]'
        });
        Object.each(this.options.skills, function(value, i) {
            new Element('option', {
                'value': i,
                'html': value
            }).inject(select);
        });

        var input = new Element('input', {
            type: 'text',
            id: 'skills-' + type + '-' + i + '-value',
            name: 'skills[' + type + '][' + i + '][value]'
        });

        var del = new Element('a.button', {
            text: 'Удалить',
            events: {
                click: function() {
                    tr.destroy();
                }
            }
        });

        select.inject(td1);
        input.inject(td2);
        del.inject(td3);

        tr.inject(table.getElement('tbody tr:last-child'), 'before');
        table.set('data-items', i);
    }
})