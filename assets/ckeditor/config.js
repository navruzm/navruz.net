/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
 */


CKEDITOR.editorConfig = function(config)
{
    config.language = 'tr';
    config.uiColor = '#e6e4e4';
    config.toolbar_POST = [
    ['Source','Preview', 'highlight'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Maximize', 'ShowBlocks','-','About'],
    ['Image','Table','HorizontalRule','SpecialChar'],
    ['Link','Unlink','Anchor'],
    '/',
    ['Styles','Format','Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['TextColor','BGColor'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
    ];
    config.toolbar = 'POST';
    config.height = '500px';
    config.entities = false;
    config.entities_additional = '#39';
    config.entities_latin = false;
    config.entities_greek = false;
    config.entities_processNumerical = false;
    config.stylesCombo_stylesSet = 'my_styles';
    config.filebrowserBrowseUrl = 'extra.php/admin/manager/index';
    config.filebrowserUploadUrl = 'extra.php/admin/uploader/index';
    config.filebrowserImageWindowWidth = '640';
    config.filebrowserImageWindowHeight = '480';
    config.contentsCss = 'assets/css/styles.css';
    config.extraPlugins='highlight';

    /*
     * Core styles.
     */
    config.coreStyles_bold	= {
        element : 'span',
        attributes : {
            'class': 'bold'
        }
    };
    config.coreStyles_italic	= {
        element : 'span',
        attributes : {
            'class': 'italic'
        }
    };
    config.coreStyles_underline	= {
        element : 'span',
        attributes : {
            'class': 'underline'
        }
    };
    config.coreStyles_strike	= {
        element : 'span',
        attributes : {
            'class': 'strikethrough'
        },
        overrides : 'strike'
    };

    config.coreStyles_subscript = {
        element : 'span',
        attributes : {
            'class': 'subscript'
        },
        overrides : 'sub'
    };
    config.coreStyles_superscript = {
        element : 'span',
        attributes : {
            'class': 'superscript'
        },
        overrides : 'sup'
    };

    config.font_names = 'Comic Sans MS/font-comic;Courier New/font-courier;Times New Roman/font-times',

    config.font_style =
    {
        element		: 'span',
        attributes	: {
            'class' : '#(family)'
        },
        overrides	: [ {
            element : 'span',
            attributes : {
                'class' : /^font-(?:comic|courier|times)$/
            }
        } ]
    };

    /*config.fontSize_sizes = 'Büyük/s1;Larger/s2;8pt/s3;14pt/s4;Double Size/s5';
    config.fontSize_style =
    {
        element		: 'span',
        attributes	: {
            'class' : '#(size)'
        },
        overrides	: [ {
            element : 'span',
            attributes : {
                'class' : /^(?:s1|s2|s3|s4|s5)$/
            }
        } ]
    } ;*/

    config.colorButton_enableMore = false;

    config.colorButton_colors = 'red/C00000,blue/1F497D,green/76923C,orange/E36C09,violett/5F497A,turquise/31859B,grey/6F6F6F,white/FFFFFF';
    config.colorButton_foreStyle =
    {
        element : 'span',
        attributes : {
            'class' : '#(color)'
        },
        overrides	: [ {
            element : 'span',
            attributes : {
                'class' : /^(?:red|blue|green|orange|violett|turquise|grey|white)$/
            }
        } ]
    };

    config.colorButton_backStyle =
    {
        element : 'span',
        attributes : {
            'class' : '#(color)-bg'
        },
        overrides	: [ {
            element : 'span',
            attributes : {
                'class' : /^(?:red|blue|green|orange|violett|turquise|grey|white)-bg$/
            }
        } ]
    };

    /*
     * Indentation.
     */
    config.indentClasses = ['indent1', 'indent2', 'indent3'];

    /*
     * Paragraph justification.
     */
    config.justifyClasses = [ 'text-left', 'text-center', 'text-right', 'text-full' ];

    /*
     * Styles combo.
     */
    config.stylesSet =
    [
    {
        name : 'Strong Emphasis',
        element : 'strong'
    },
    {
        name : 'Emphasis',
        element : 'em'
    },

    {
        name : 'Computer Code',
        element : 'code'
    },
    {
        name : 'Keyboard Phrase',
        element : 'kbd'
    },
    {
        name : 'Sample Text',
        element : 'samp'
    },
    {
        name : 'Variable',
        element : 'var'
    },

    {
        name : 'Deleted Text',
        element : 'del'
    },
    {
        name : 'Inserted Text',
        element : 'ins'
    },

    {
        name : 'Cited Work',
        element : 'cite'
    },
    {
        name : 'Inline Quotation',
        element : 'q'
    }
    ];

//config.extraPlugins='highlight';
//config.extraPlugins='syntaxhighlight';

};
CKEDITOR.addStylesSet('my_styles',
    [
    {
        name : 'Uyarı kutusu',
        element : 'p',
        attributes : {
            'class' : 'warning'
        }
    },
    {
        name : 'Bilgi kutusu',
        element : 'p',
        attributes : {
            'class' : 'info'
        }
    },
    {
        name : 'Hata kutusu',
        element : 'p',
        attributes : {
            'class' : 'error'
        }
    },
    {
        name : 'İndirme kutusu',
        element : 'p',
        attributes : {
            'class' : 'download'
        }
    },
    {
        name : 'Sola Yasla',
        element : 'img',
        attributes : {
            'class' : 'float_left'
        }
    },
    {
        name : 'Sağa Yasla',
        element : 'img',
        attributes : {
            'class' : 'float_right'
        }
    }
    ]);

