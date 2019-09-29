<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

if (empty($this->items)) {
	echo JText::_('RL_NOITEMS');
	return;
}

$categories = array_keys($this->items);
sort($categories);

echo '<div class="copyintro">'.JText::_('RL_SHAREINTRO').'</div>';
echo '<div class="copycode">';
foreach ($categories as $category) {
	echo htmlentities($category).'<br/>';
	foreach ($this->items[$category] as $item) {
		echo htmlentities('<a href="'.$item->itemUrl.'">'.$item->title.'</a>')."<br/>";
	}
}
echo '</div>';