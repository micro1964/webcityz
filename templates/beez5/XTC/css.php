<?php
/*

XTC Template Framework 3.3.0

Copyright (c) 2010-2014 Monev Software LLC,  All Rights Reserved

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA

See COPYRIGHT.txt for more information.
See LICENSE.txt for more information.

www.joomlaxtc.com

*/

define('_JEXEC', 1);

$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root . '/defines.php')) {
	include_once $root . '/defines.php';
}
if (!defined('_JDEFINES')) {
	define('JPATH_BASE', $root);
	require_once JPATH_BASE.'/includes/defines.php';
}
require_once JPATH_BASE.'/includes/framework.php';

$app = JFactory::getApplication('site');
$app->initialise();

// Get CSS parameter values
$template = JRequest::getWord('tp');
$id = JRequest::getInt('id');

// load XTC
require 'XTC.php';
$templateParameters = xtcLoadParams($id); // Get params for this template layout

// Process CSS
// set document mode
header('Content-type: text/css');

// Setup compression
if ($xtc->CSScompression) {
	ob_start();
	ob_implicit_flush(0);
}

$imgpath = $xtc->templateUrl.'images'; // a helper var for CSS scripts

$file = JRequest::getCmd('file');
$group = JRequest::getCmd('group');
$groups = JRequest::getVar('groups');

if ($file) {
	$params = $templateParameters;
	require $xtc->templatePath.'/css/'.$file.'.css';
}

if ($group) {
	$prefix = $templateParameters->prefix[$group];
	$params = $templateParameters->group->$group;
	if (is_readable($xtc->templatePath.'/css/'.$group.'.css')) {
		require $xtc->templatePath.'/css/'.$group.'.css';
	}
	else {
		require $xtc->templatePath.'/css/'.$prefix.'.css';
	}
}

if ($groups) {
	$params = $templateParameters;
	require $xtc->templatePath.'/css/default.css';
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
}


// finish compression
if ($xtc->CSScompression) {
  if ( strpos($_SERVER["HTTP_ACCEPT_ENCODING"], 'x-gzip') !== false ) { $encoding = 'x-gzip'; }
  elseif ( strpos($_SERVER["HTTP_ACCEPT_ENCODING"],'gzip') !== false ) { $encoding = 'gzip'; }
  else { $encoding = false; }

  if ( $encoding ) {
    $contents = ob_get_contents();
    ob_end_clean();
    header('Content-Encoding: '.$encoding, false);
    print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
    $size = strlen($contents);
    $contents = gzcompress($contents, 9);
		//$contents = substr($contents, 0, $size);
    print($contents);
	}
	else {
    ob_end_flush();
  }
}