<?php

/**
 * Uninstall file for webpage
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id: uninstall.php 7776 2010-06-11 13:52:58Z jtickle $
 */

function webpage_uninstall(&$content)
{
    PHPWS_DB::dropTable('webpage_volume');
    PHPWS_DB::dropTable('webpage_page');
    PHPWS_DB::dropTable('webpage_featured');
    $content[] = dgettext('webpage', 'Web Page tables removed.');
    return TRUE;
}

?>