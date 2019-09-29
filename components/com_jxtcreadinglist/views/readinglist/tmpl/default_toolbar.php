<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

$Itemid = JRequest::getInt('Itemid');
?>
<div class="rltoolbar">
	<a href="javascript:void(0);" onclick="document.getElementById('rlid').value=<?php echo $this->item->id; ?>; Joomla.submitform('delete', document.getElementById('readingListForm'));" class="rl_link hasTip" title="<?php echo JText::_('RL_TBDELETE'); ?>::" ><i class="rlicon-trash"></i></a>
	<a <?php echo ($this->params->get('newwindow', 0) == 0 ? '' : 'target="_blank"'); ?> href="<?php echo $this->item->itemUrl; ?>" class="rl_link hasTip" title="<?php echo JText::_('RL_TBGOTO'); ?>::" ><i class="rlicon-link-ext"></i></a>
	<div style="clear:both"></div>
</div>