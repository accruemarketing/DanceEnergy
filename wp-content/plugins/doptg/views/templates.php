<?php

/*
* Title                   : Thumbnail Gallery (WordPress Plugin)
* Version                 : 2.4
* File                    : templates.php
* File Version            : 2.0
* Created / Last Modified : 01 October 2013
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Thumbnail Gallery Templates Class.
*/

    if (!class_exists("DOPTGTemplates")){
        class DOPTGTemplates{
            function DOPTGTemplates(){// Constructor.
            }

            function galleriesList(){// Return Template         
                global $blog_id;         
?>
    <script type="text/JavaScript">
        var DOPTG_curr_page = "Galleries List",
        DOPTG_plugin_url = "<?php echo DOPTG_Plugin_URL?>",
        DOPTG_plugin_abs = "<?php echo DOPTG_Plugin_AbsPath?>",
<?php
    global $DOPTG_lang;
    
    for ($i=0; $i<count($DOPTG_lang); $i++){
        echo $DOPTG_lang[$i]['key']." = '".$DOPTG_lang[$i]['text']."', ";
    }
?>
        DOPTG_END_TRANSLATION_LIST = 'End translation.';
    </script>
    <div class="wrap DOPTG-admin">
        <h2><?php echo DOPTG_TITLE?></h2>
        <div id="DOPTG-admin-message"></div>
        <a href="http://envato-help.dotonpaper.net/thumbnail-gallery-wordpress-plugin.html#faq" target="_blank" class="DOPTG-help"><?php echo DOPTG_HELP_FAQ ?></a>
        <a href="http://envato-help.dotonpaper.net/thumbnail-gallery-wordpress-plugin.html" target="_blank" class="DOPTG-help"><?php echo DOPTG_HELP_DOCUMENTATION ?></a>
        
        <input type="hidden" id="blog_id" value="<?php echo $blog_id; ?>" />
        <input type="hidden" id="gallery_id" value="" />
        <br class="DOPTG-clear" />
        <div class="main">
            <div class="column column1">
                <div class="column-header">
                    <div class="add-button">
                        <a href="javascript:doptgAddGallery()" title="<?php echo DOPTG_ADD_GALLERY_SUBMIT?>"></a>
                    </div>
                    <div class="edit-button">
                        <a href="javascript:doptgShowDefaultSettings()" title="<?php echo DOPTG_EDIT_GALLERIES_SUBMIT?>"></a>
                    </div>
                    <a href="javascript:void()" class="header-help"><span><?php echo DOPTG_GALLERIES_HELP?>"</span></a>
                </div>
                <div class="column-content-container">
                    <div class="column-content">
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="column-separator"></div>
            <div class="column column2">
                <div class="column-header"></div>
                <div class="column-content-container">
                    <div class="column-content">
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="column-separator"></div>
            <div class="column column3">
                <div class="column-header"></div>
                <div class="column-content-container">
                    <div class="column-content">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <br class="DOPTG-clear" />
    </div>
<?php
            }
        }
    }
?>