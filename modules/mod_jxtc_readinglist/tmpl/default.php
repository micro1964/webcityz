<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

?>
<div class="readinglist_module">
	<?php
		if (empty($items)) {
			echo JText::_('RL_NOITEMS');
		} else {
			echo '<div class="rlmod_intro">'.JText::_('RL_INTRO').'</div>';

			echo '<div class="rlmod_categories">';
			if ($params->get('showall', 1)) { // Show "All" option
				$class = ($cid == -1) ? 'selected' : '';
				$link = JRoute::_('index.php?option=com_jxtcreadinglist&view=readinglist');
				echo '<a href="'.$link.'" class="rlmodcategory '.$class.'"><span>'.JText::_('RL_ALL').'</span></a>';
			}
			$categories = array_keys($items);
			sort($categories);
			foreach ($categories as $categoryid => $category) {
				$link = JRoute::_('index.php?option=com_jxtcreadinglist&view=readinglist&cid='.$categoryid);
				$class = ($cid == $categoryid) ? 'selected' : '';
				echo '<a href="'.$link.'" class="rlmodcategory '.$class.'"><span>'.$category.'</span></a>';
			}
			echo '</div>';

			echo '<div class="rlmod_options">';
			if ($params->get('showcopy', 1)) { // Show "Copy" option
				JHTML::_('behavior.modal', 'a.rlmodal');	
				$link = JRoute::_('index.php?option=com_jxtcreadinglist&tmpl=component&view=copy&cid='.$cid);
				echo '<a class="rlmodal rlmodcopy" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 400}}"><span>'.JText::_('RL_COPY').'</span></a>';
			}
			
			if ($params->get('showemail', 1)) { // Show "Email" option
				require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';
			
				$link = jxtcrlhelper::getEmailLink($cid);
				$url	= 'index.php?option=com_mailto&tmpl=component&link='.MailToHelper::addLink($link);
				$text = JText::_('RL_EMAIL');
			
				$attribs['class']	= 'rlmodemail';
				$attribs['title']	= JText::_('JGLOBAL_EMAIL');
				$attribs['onclick'] = "window.open(this.href,'win2','width=400,height=350,menubar=yes,resizable=yes'); return false;";
			
				echo JHtml::_('link', JRoute::_($url), $text, $attribs);
			}
			echo '</div>';
		}
	?>
</div>