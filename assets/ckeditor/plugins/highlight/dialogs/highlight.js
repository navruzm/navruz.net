CKEDITOR.dialog.add("highlight",function(editor){
    prep = function(str){
        str=str.replace(/<br>/g,"\n");
        str=str.replace(/<br\/>/g,"\n");
        str=str.replace(/<br \/>/g,"\n");
        str=str.replace(/&amp;/g,"&");
        str=str.replace(/&lt;/g,"<");
        str=str.replace(/&gt;/g,">");
        str=str.replace(/&quot;/g,'"');
        return str;
    };
    return{
        title:editor.lang.highlight.title,
        minWidth: 700,
        minHeight: 400,
        onShow: function() {
            var editor = this.getParentEditor();
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            var pre_elem = element && element.getAscendant('pre', true);
            var code_elem = pre_elem && pre_elem.getFirst();
            var data = new Object();
            data.code_type = (code_elem) ? code_elem.getAttribute('class') : '';
            var code_html = (code_elem) ? code_elem.getHtml() : '';
            data.code_html = prep(code_html);
            this.setupContent(data);
        },

        onOk:function(){
            var editor = this.getParentEditor();
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            var pre_elem = element && element.getAscendant('pre', true);
            var code_elem = pre_elem && pre_elem.getFirst();
            var data = new Object();
            this.commitContent(data);

            var new_pre = new CKEDITOR.dom.element('pre');
            var new_code = new CKEDITOR.dom.element('code');
            if (code_elem) {
                new_code.setAttribute('class', data.code_type);
                new_code.setText(data.code_html);
                new_pre.append(new_code);
                pre_elem.remove();
                editor.insertElement(new_pre);
                
            } else {
                new_code.setAttribute('class', data.code_type);
                new_code.setText(data.code_html);
                new_pre.append(new_code);
                editor.insertElement(new_pre);
            }
        },
        contents:[
        {
            id:"info",
            name:'info',
            elements:[{
                type:'vbox',
                padding:0,
                children:[
                {
                    id: 'lang',
                    type: 'select',
                    labelLayout: 'horizontal',
                    label:editor.lang.highlight.code_lang,
                    'default': '',
                    widths : [ '20%','80%' ],
                    items: [["PHP","php"],["HTML","html"],["Javascript","javascript"],["CSS","css"]],
                    setup: function(data) {
                        if (data.code_type)
                            this.setValue(data.code_type);
                    },
                    commit: function(data) {
                        data.code_type = this.getValue();
                    }
                },
                {
                    type:'html',
                    html:'<span>'+editor.lang.highlight.desc+'</span>'
                },
                {
                    type:'textarea',
                    id:'insertcode_area',
                    label:'',
                    cols:80,
                    rows:20,
                    setup:function(data){
                        if(data.code_html){
                            this.setValue(data.code_html)
                        }
                    },
                    commit:function(data){
                        data.code_html=this.getValue();
                    }
                }]
            }]
        }
        ]
    };
});