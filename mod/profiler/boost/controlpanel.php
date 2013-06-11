<?php
/**
 * @version $Id: controlpanel.php 7311 2010-03-10 13:21:15Z matt $
 * @author Matthew McNaney <mcnaney at gmail dot com>
 */

$link[] = array('label'       => dgettext('profiler', 'Profiler'),
		'restricted'  => TRUE,
		'url'         => 'index.php?module=profiler',
		'description' => dgettext('profiler', 'Create profiles on individuals for display on site.'),
		'image'       => 'profile.png',
		'tab'         => 'content'
		);

		?>