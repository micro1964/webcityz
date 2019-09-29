<?php
/*
	JoomlaXTC Komento Wall

	version 1.3.0

	Copyright (C) 2008-2017 Monev Software LLC.	All Rights Reserved.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.

	See COPYRIGHT.txt for more information.
	See LICENSE.txt for more information.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;


// Load constants and helpers
if (!is_dir(JPATH_ROOT . '/components/com_komento/')) {
    echo JText::_('To use JXTC Komento Wall you must install Komento on your site too, you can get it <a href="http://stackideas.com/komento.html" target="_blank">here</a>');
    return;
}

require_once( JPATH_ROOT . '/components/com_komento/bootstrap.php' );

// Require the base controller
if (!class_exists("KomentoController"))
    require_once( KOMENTO_ADMIN_ROOT . '/controllers/controller.php' );

// Include the KomentoUser class to can get the correct avatars and urls:
require_once( KOMENTO_CLASSES . '/profile.php' );

//Core calls
$live_site = JURI::base();
$doc = JFactory::getDocument();
$moduleDir = 'mod_jxtc_komentowall';
$db = JFactory::getDBO();

// Include the syndicate functions only once
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.html.parameter');
require_once 'helper.php';

//Parameters
$component_id = $params->get('component_id', 0); if (!is_array($component_id)) { $component_id = array($component_id); }
$authorid = $params->get('authorid', -1); if (!is_array($authorid)) { $authorid = array($authorid); }

$issticked = $params->get('issticked', 0);
$onlyreplies = $params->get('onlyreplies', 0);
$group = $params->get('group', 0);

$compat = $params->get('compat');
$avatarw = $params->get('avatarw');
$avatarh = $params->get('avatarh');

$sortfield = $params->get('sortfield', 0);
$sortorder = $params->get('sortorder', 1);

$template = $params->get('template', '');
$moduletemplate = trim($params->get('modulehtml', '{mainarea}'));
$itemtemplate = trim($params->get('html', '{intro}'));
$columns = $params->get('columns', 1);
$rows = $params->get('rows', 1);
$pages = $params->get('pages', 1);
$offset = $params->get('offset', 0);

$dateformat = trim($params->get('dateformat', 'Y-m-d'));

$moreqty = $params->get('moreqty', 0);
$morecols = trim($params->get('morecols', 1));
$morelegend = trim($params->get('moretext', ''));
$morelegendcolor = $params->get('morergb', 'cccccc');
$moretemplate = $params->get('moretemplate', '');

if ($template && $template != -1) {
    $moduletemplate = file_get_contents(JPATH_ROOT . '/modules/' . $moduleDir . '/templates/' . $template . '/module.html');
    $itemtemplate = file_get_contents(JPATH_ROOT . '/modules/' . $moduleDir . '/templates/' . $template . '/element.html');
    $moretemplate = file_get_contents(JPATH_ROOT . '/modules/' . $moduleDir . '/templates/' . $template . '/more.html');
    if (file_exists(JPATH_ROOT . '/modules/' . $moduleDir . '/templates/' . $template . '/template.css')) {
        $doc->addStyleSheet($live_site . 'modules/' . $moduleDir . '/templates/' . $template . '/template.css', 'text/css');
    }
}

$varaux = $columns * $rows * $pages;
$varaux += $moreqty;

$items = mod_jxtc_komentowallHelper::getData($component_id, $authorid, $group, $offset, $varaux, $sortfield, $sortorder, $issticked, $onlyreplies);

if (count($items) == 0)
    return; // Return if empty

$cloneditems = $items;

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));

// Build More Area
$items = $params->get('moreclone', 0)
	? array_slice($items,0,$moreqty)
	: array_slice($items,($columns * $rows * $pages),$moreqty);

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default').'_more');

echo '<div id="' . $jxtc . '">' . $modulehtml . '</div>';

