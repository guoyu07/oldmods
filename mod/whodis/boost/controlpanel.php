<?php

/**
 * @version $Id: controlpanel.php 7311 2010-03-10 13:21:15Z matt $
 * @author Matthew McNaney <mcnaney at appstate dot edu>
 */

$link[] = array('label'       => 'Whodis?',
		'restricted'  => TRUE,
		'url'         => 'index.php?module=whodis',
		'description' => dgettext('whodis', 'Records the href referrers from who is visiting your site.'),
		'image'       => 'whodis.png',
		'tab'         => 'admin'
		);

		?>