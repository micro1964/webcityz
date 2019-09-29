<?php
/*
	JoomlaXTC Reading List

	version 1.3.1
	
	Copyright (C) 2012,2013 Monev Software LLC.	All Rights Reserved.
	
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

defined( '_JEXEC' ) or die;

$categories = array_keys($items);
sort($categories);

$target = $newwindow ? 'target="_blank"' : '';
?>
<div class="readinglist_app">
	<?php
		foreach ($categories as $category) {
			echo '<div style="font-weight:bold;padding:2px 0 2px 0;margin-bottom:2px;border-bottom:1px dotted #e0e0e0">'.$category.'</div>';

			if ($maxitems and count($items[$category]) > $maxitems) { $items[$category] = array_slice($items[$category],0,$maxitems); }

			foreach ($items[$category] as $item) {
				echo '<div class="item"><a '.$target.' href="'.$item->itemUrl.'" alt="'.$item->title.'">'.$item->title.'</a></div>';
			}
		}
	?>
</div>
