<?php

if (! defined('PT_SWITCH_NAME'))
{
	define('PT_SWITCH_NAME', 'P&amp;T Switch');
	define('PT_SWITCH_VER',  '1.0.4');
}

$config['name']    = PT_SWITCH_NAME;
$config['version'] = PT_SWITCH_VER;
$config['nsm_addon_updater']['versions_xml'] = 'http://pixelandtonic.com/ee/releasenotes.rss/pt_switch';
