CKEDITOR.plugins.add('highlight',
    {    
        requires: ['dialog'],
        lang : ['tr'],
        init:function(editor) {
            var plugin="highlight";
            var command=editor.addCommand(plugin,new CKEDITOR.dialogCommand(plugin));
            command.modes={
                wysiwyg:1,
                source:0
            };
            command.canUndo=false;
            editor.ui.addButton("highlight",{
                label:editor.lang.highlight.title,
                command:plugin,
                icon:this.path+"highlight.png"
            });
            CKEDITOR.dialog.add(plugin,this.path+"dialogs/highlight.js")
        }
    });