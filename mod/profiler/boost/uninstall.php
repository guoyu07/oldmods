<?php

/**
 * Uninstall file for profiles
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id: uninstall.php 7776 2010-06-11 13:52:58Z jtickle $
 */

function profiler_uninstall(&$content)
{

    PHPWS_DB::dropTable('profiles');
    PHPWS_DB::dropTable('profiler_division');
    $content[] = dgettext('profiler', 'Profiles table removed.');

    return TRUE;
}

?>
