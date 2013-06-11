<?php

/**
 * @version $Id: runtime.php 7776 2010-06-11 13:52:58Z jtickle $
 * @author Matthew McNaney <mcnaney at appstate dot edu>
 */

if (!isset($_SESSION['Whodis'])) {
    PHPWS_Core::initModClass('whodis', 'Whodis.php');
    Whodis::record();
    $_SESSION['Whodis'] = true;
}

?>