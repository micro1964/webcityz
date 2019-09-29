<?php
/***********************************************************************************
************************************************************************************
***                                                                              ***
***   XTC Template Framework 2.1.1                                               ***
***                                                                              ***
***   Copyright (c) 2010-2011 Monev Software LLC,  All Rights Reserved           ***
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
***   See COPYRIGHT.php for more information.                                    ***
***   See LICENSE.php for more information.                                      ***
***                                                                              ***
***   www.joomlaxtc.com                                                          ***
***                                                                              ***
************************************************************************************
***********************************************************************************/

// This program	Builds CSS style cascade

define('_XTCFRAMEWORK',1);
require 'XTC.php';

$templateParameters = xtcLoadParams(); // Get params for this template

$group = cleanGet('group');
$file = basename(cleanGet('file'));
if ($group && isset($templateParameters->group->$group)) {
	$params = $templateParameters->group->$group;
	$file .= '.css';
}
else {
	$params = $templateParameters;
}
$imgpath = $xtc->templateUrl.'images';

header('Content-type: text/css');
if ($xtc->CSScompression) {
	ob_start();
	ob_implicit_flush(0);
}

if (is_readable($xtc->templatePath.'/css/'.$file)) require $xtc->templatePath.'/css/'.$file;

if ($xtc->CSScompression) {
	print_gzipped_page();
}

?>