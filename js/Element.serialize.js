/*
---
description:

license: MIT-style

authors:
- Arieh Glazer

requires:
- core/1.2.4 : [Element]

provides: [Element.serialize]

...
*/

/*!
Copyright (c) 2011 Arieh Glazer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE
*/
(function(window,$,undef){

Element.implement({
    serialize : function serialize(deep){
        var results = {}, inputs = this.getElements('input, select, textarea');

        inputs.each(function(el){
            var type = el.type, names =[], name = el.name, current;
            if (!el.name || el.disabled || type == 'submit' || type == 'reset' || type == 'file' || type == 'image') return;

            var value = (el.get('tag') == 'select') 
                ? ((el.getSelected().length == 1) ? el.getSelected()[0].get('value') : el.getSelected().get('value'))
                : ((type == 'radio' || type == 'checkbox') && !el.checked) ? null : el.get('value');
            
            if (!value || value.length < 1) return;
            
            if (deep){
                names = name.split('[');
                if (names.length == 1) results[name] = value;
                else{
                    current = results;
                    for (var i=0,l=names.length-1; i<l;i++){
                        name = names[i].replace(']','');
                        if (!current[name]) current[name] = current = {};
                        else current = current[name];
                    }
                    current[names[names.length-1].replace(']','')] = value;
                }
            }else results[el.name] = value;
        });
        
        return results;
    }
});

})(this,document.id);
