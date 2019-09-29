<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
if (!function_exists('parseXmenu')) {
	function parseXmenu($temp) {
		while (($ini = strpos($temp,"{xm ")) !== false) {
		
			$fin = strpos($temp,"}",$ini);
			$parms=substr($temp,$ini+4,$fin-$ini-4);
			list($position,$width,$height)=explode(',',$parms);
			if (!empty($position)) {
				settype($width,'integer');
				settype($height,'integer');
				$width = $width==0 ? '' : 'width:'.$width.'px;';
				$height = $height==0 ? '' : 'height:'.$height.'px;';
				$modules = JModuleHelper::getModules($position);
				$attribs = array('style'=>'xhtml');
				$positionhtml = "<div class=\"xmenu_position $position\" style=\"$width $height\">";
				foreach ($modules as $module) {
					$positionhtml .= JModuleHelper::renderModule( $module, $attribs );
				}
				$positionhtml .= "</div>";
			}
			else {
				$positionhtml = '';
			}
			$temp = substr_replace($temp,$positionhtml,$ini,$fin-$ini+1);
		}
		// Do double lines
		$temp = str_replace( '{xm}', '<span class=\'xmenu\'>', $temp );
		$temp = str_replace( '{/xm}', '</span>', $temp );
		
		return $temp;
	}

	function cleanXmenu($temp,$keepdropline=false) {
		while (($ini = strpos($temp,"{xm ")) !== false) { // Clean module positions
			$fin = strpos($temp,"}",$ini);
			$temp = substr_replace($temp,'',$ini,$fin-$ini+1);
		}
	
		if ($keepdropline) {	// make droplines as simple text
			$temp = str_replace( '{xm}', ' ', $temp );
			$temp = str_replace( '{/xm}', ' ', $temp );
		}
		else {	// (Delete droplines)
			while (($ini = strpos($temp,"{xm}")) !== false) {
				if (($fin = strpos($temp,"{/xm}",$ini)) !== false) {
					$temp = substr_replace($temp,'',$ini,$fin-$ini+5);
				}
			}
		}
		
		return $temp;
	}
}
?>

<ul class="menu<?php echo $class_sfx;?> xtcdefaultmenu"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
$xyz = '';
foreach ($list as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent) {
		$class .= ' parent';
	}
        
	if (!empty($class)) {
		$class = ' class="'.trim($class) .' subcol'.($xyz++%2).'"';
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul>';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>
