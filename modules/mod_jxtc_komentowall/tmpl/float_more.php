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
	$index=1;
	$spanClass = 'span'.floor((12/$morecols));
	$rows = ceil($moreqty/$morecols);
	$elementWidth = round(100 / $morecols,4);
	$elementHeight = round(100 / $rows,4);
	
	$moreareahtml = '<div id="wallmore'.$jxtc.'" class="wallmore columns-'.$morecols.' rows-'.$rows.'">';
	$moreareahtml .= '<div class="wallpage singlepage">';
	
	for ($r=1;$r<=$rows;$r++) {
		if (empty($items)) { continue; }
		if ($rows == 1) { $rowclass = 'singlerow'; }	// Row class
		elseif ($r == 1) { $rowclass = 'firstrow'; }
		elseif ($r == $rows) { $rowclass = 'lastrow'; }
		else { $rowclass = 'centerrow'; }
		$rowclass .= ($r%2) ? ' oddrow' : ' evenrow';
	
		for ($c=1;$c<=$morecols;$c++) {
			$item = array_shift($items);
			if (!empty($item)) {
				$itemhtml = $moretemplate;
				require JModuleHelper::getLayoutPath($module->module, 'default_parse');
				if ($morecols == 1) { $colclass = 'singlecol'; } 	// Col class
				elseif ($c == 1) { $colclass = 'firstcol'; }
				elseif ($c == $morecols) { $colclass = 'lastcol'; }
				else { $colclass = 'centercol'; }
					$colclass .= ($c%2) ? ' oddcol' : ' evencol';
	
				$moreareahtml .= '<div class="wallfloat '.$rowclass.' row-'.$r.' '.$colclass.' col-'.$c.'" style="width:'.$elementWidth.'%;height:'.$elementHeight.'%" >'.$itemhtml.'</div>';
				$index++;
			}
		}
	}
	
	$moreareahtml .='</div>';
	$moreareahtml .='</div>';
}

$modulehtml = str_replace('{morearea}', $moreareahtml, $modulehtml);
