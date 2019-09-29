<?php
/*
	JoomlaXTC HTML module

	version 1.1.0
	
	Copyright (C) 2010,2011,2012,2013  Monev Software LLC.	All Rights Reserved.
	
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

	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

if (!defined( '_JEXEC' )) die;

$html = $params->get('html');
$mode = $params->get('mode',1);
$plugins = $params->get('plugins',0);

if ($plugins) {
	JPluginHelper::importPlugin('content');
	$contentconfig = JComponentHelper::getParams('com_content');
	$dispatcher = JDispatcher::getInstance();
	$item = new stdClass();
	$item->text = $html;
	$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$contentconfig, 0 ));
	$html = $item->text;
}

switch ($mode) {
	case 2: // CSS
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration($html,'text/css');
	break;
	case 3: // JavaScript
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($html,'text/javascript');
		break;
	default: // HTML
		echo $html;
}