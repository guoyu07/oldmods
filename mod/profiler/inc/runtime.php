<?php
/**
 * @version $Id: runtime.php 7776 2010-06-11 13:52:58Z jtickle $
 * @author Matthew McNaney <mcnaney at gmail dot com>
 */

if (!isset($_REQUEST['module'])) {
    PHPWS_Core::initModClass('profiler', 'Profiler.php');

    Profiler::view();
}

?>