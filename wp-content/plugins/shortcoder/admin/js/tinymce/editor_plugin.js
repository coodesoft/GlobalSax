/*
 * Shortcoder TinyMCE plugin for inserting Shortcodes
*/

(function() {
    
    tinymce.create( 'tinymce.plugins.SCButton',{
    
        init : function( ed, url ){  
            ed.addButton( 'shortcoder', {
                title : 'Insert shortcodes created using Shortcoder',
                image : url + '/icon.png',
                onclick : function() {
                    if( typeof sc_show_insert !== 'undefined' ) sc_show_insert();
                }
            }); 
        },
        
        getInfo : function() {
            return {
                longname : 'Shortcoder',
                author : 'Aakash Chakravarthy',
                authorurl : 'https://www.aakashweb.com/',
                infourl : 'https://www.aakashweb.com/',
                version : '1.3'
            };
        }

    });
    
    tinymce.PluginManager.add( 'shortcoder', tinymce.plugins.SCButton );
    
})();