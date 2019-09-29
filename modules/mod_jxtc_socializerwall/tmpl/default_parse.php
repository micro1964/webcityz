<?php
/*
	JoomlaXTC Socializer Wall

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


if (!empty($item)) {
	$itemhtml = str_ireplace('{icon}', '<span class="'.$class.'">'.$item['icons'][$iconType].'</span>', $itemhtml );
	$itemhtml = str_ireplace('{alias}', $item['alias'], $itemhtml );
	$itemhtml = str_ireplace('{title}', $item['title'], $itemhtml );
	$itemhtml = str_ireplace('{url}', $item['link'], $itemhtml );
	$itemhtml = str_ireplace('{index}', $index, $itemhtml );
}
