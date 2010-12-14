<?php

if (! defined('PT_PILL_NAME'))
{
	define('PT_PILL_NAME', 'P&amp;T Pill');
	define('PT_PILL_VER',  '1.0.3');
}

$config['name']    = PT_PILL_NAME;
$config['version'] = PT_PILL_VER;
$config['nsm_addon_updater']['versions_xml'] = 'http://pixelandtonic.com/ee/releasenotes.rss/pt_pill';
