<?php

/**
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id: runtime.php 7776 2010-06-11 13:52:58Z jtickle $
 */

if (!isset($_REQUEST['module']) || $_REQUEST['module'] == 'webpage') {
    PHPWS_Core::initModClass('webpage', 'User.php');
    Webpage_User::showFrontPage();
    Webpage_User::showFeatured();
}

?>