<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.cleanXmenu($item->title).'" /><span class="image-title">'.cleanXmenu($item->title).'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.cleanXmenu($item->title).'" />';
}
else { $linktype = parseXmenu($item->title);
}

?><span class="separator"><?php echo $title; ?><?php echo $linktype; ?></span>
