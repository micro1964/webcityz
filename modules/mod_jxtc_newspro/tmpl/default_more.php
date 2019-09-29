<?php
/*
	JoomlaXTC Wall Renderer

	version 1.2.2

	Copyright (C) 2010,2011,2012,2013,2014  Monev Software LLC.	All Rights Reserved.

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

$moreareahtml = '';

if (!empty($items) && $morecols && $moreqty) {
	$index = 1;
	if ($morelegend) {
	    $moreareahtml .= '<a style="color:#' . $morelegendcolor . '">' . $morelegend . '</a><br/>';
	}
	$moreareahtml .= '<table class="jnp_more" border="0" cellpadding="0" cellspacing="0">';
	$c = 1;
	$cnt = 0;
	foreach ($items as $item) {
	    if ($c == 1) $moreareahtml .= '<tr>';
	    $itemhtml = $moretemplate;
	    require JModuleHelper::getLayoutPath($module->module, 'default_parse');
	    $moreareahtml .= '<td>' . $itemhtml . '</td>';
	    $c++;
	    $index++;
	    if ($c > $morecols) {
        $moreareahtml .= '</tr>';
        $c = 1;
	    }
	}
	if ($c > 1) $moreareahtml .= '</tr>';
	$moreareahtml .= '</table>';
}
$modulehtml = str_replace('{morearea}', $moreareahtml, $modulehtml);
