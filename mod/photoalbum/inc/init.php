<?php

/**
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id: init.php 8128 2012-04-18 13:32:14Z matt $
 */
PHPWS_Core::requireConfig('photoalbum');
PHPWS_Core::initModClass('photoalbum', 'Album.php');
PHPWS_Core::initModClass('photoalbum', 'AlbumManager.php');

?>