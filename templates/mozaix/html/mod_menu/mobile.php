<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

$tag = $params->get('tag_id');
$tag = $tag ? 'id="'.$tag.'"' : NULL;
?>
<div class="mobilebtn">
<select size="1" class="menu<?php echo $class_sfx;?> xtcmobilemenu" <?php echo $tag; ?> onchange="location.href=this.value">
<?php
	foreach ($list as $i => &$item) {
		$selected = ($item->id == $active_id) ? 'selected="selected"' : NULL;
		echo '<option value="'.$item->flink.'" '.$selected.'>'.$item->title.'</option>';
	}
?>
</select>
</div>
