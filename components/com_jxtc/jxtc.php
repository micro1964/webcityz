<?php
/***********************************************************************************
************************************************************************************
***                                                                              ***
***   XTC Template Framework helper   3.4.0                                      ***
***                                                                              ***
***   Copyright (c) 2010-2017                                                    ***
***   Monev Software LLC,  All Rights Reserved                                   ***
***                                                                              ***
***   This program is free software; you can redistribute it and/or modify       ***
***   it under the terms of the GNU General Public License as published by       ***
***   the Free Software Foundation; either version 2 of the License, or          ***
***   (at your option) any later version.                                        ***
***                                                                              ***
***   This program is distributed in the hope that it will be useful,            ***
***   but WITHOUT ANY WARRANTY; without even the implied warranty of             ***
***   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              ***
***   GNU General Public License for more details.                               ***
***                                                                              ***
***   You should have received a copy of the GNU General Public License          ***
***   along with this program; if not, write to the Free Software                ***
***   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA   ***
***                                                                              ***
***   See COPYRIGHT.txt for more information.                                    ***
***   See LICENSE.txt for more information.                                      ***
***                                                                              ***
***   www.joomlaxtc.com                                                          ***
***                                                                              ***
************************************************************************************
***********************************************************************************/

defined('_JEXEC') or die;

// init XTC
$app = JFactory::getApplication();
$template = $app->getTemplate(true);
$templateName = $template->template; 
require JPATH_ROOT.'/templates/'.$templateName.'/XTC/XTC.php';

// set document mode
$document = JFactory::getDocument();
$document->setMimeEncoding('text/css');
if ($xtc->CSScompression) {
	ob_start();
	ob_implicit_flush(0);
}

// Get CSS parameter values
$id = JRequest::getInt('id');
$templateParameters = xtcLoadParams($id); // Get params for this template layout
$imgpath = $xtc->templateUrl.'images'; // a helper var

// Process CSS
switch (JRequest::getVar( 'mode' )) {
	case 'file':
		$file = JRequest::getCmd('file');
		$params = $templateParameters;
		require $xtc->templatePath.'/css/'.$file.'.css';
	break;

	case 'group':
		$group = JRequest::getCmd('group');
		$prefix = $templateParameters->prefix[$group];
		$params = $templateParameters->group->$group;
		if (is_readable($xtc->templatePath.'/css/'.$group.'.css')) {
			require $xtc->templatePath.'/css/'.$group.'.css';
		}
		else {
			require $xtc->templatePath.'/css/'.$prefix.'.css';
		}
	break;

	case 'single':
		$params = $templateParameters;
		require $xtc->templatePath.'/css/default.css';
		$groups = JRequest::getVar('groups');
		foreach (explode(',',$groups) as $group) {
			$prefix = $templateParameters->prefix[$group];
			$params = $templateParameters->group->$group;
			if (is_readable($xtc->templatePath.'/css/'.$group.'.css')) {
				require $xtc->templatePath.'/css/'.$group.'.css';
			}
			else {
				require $xtc->templatePath.'/css/'.$prefix.'.css';
			}
		}
		$params = $templateParameters;
		require JPATH_ROOT.'/templates/system/css/system.css';
		require JPATH_ROOT.'/templates/system/css/general.css';
		require $xtc->templatePath.'/css/template.css';
	break;
}

// finish document output
if ($xtc->CSScompression) {
	print_gzipped_page();
}
?>