<?php
/**
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id: uninstall.php 7776 2010-06-11 13:52:58Z jtickle $
 */

function whodis_uninstall()
{
    PHPWS_DB::dropTable('whodis');
    PHPWS_DB::dropTable('whodis_filters');
    return true;
}

?>
