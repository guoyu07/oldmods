<?php

/**
 * @version $Id: uninstall.php 7776 2010-06-11 13:52:58Z jtickle $
 */

if (!Current_User::isDeity()){
    header("location:index.php");
    exit();
}

function photoalbum_uninstall(&$content) {
    PHPWS_DB::dropTable('mod_photoalbum_albums');
    PHPWS_DB::dropTable('mod_photoalbum_photos');
    $content[] = 'Table uninstalled.';
    return TRUE;
}

?>