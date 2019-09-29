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
<div class="rltoolbar">
	<span class="author">
		<?php echo JText::sprintf('RL_TBAUTHOR',$item->author); ?>
	</span>

	<span class="date">
		<?php echo JHtml::_('Date',$item->modified,JText::_('RL_TBDATE')); ?>
	</span>

	<img onclick="javascript:document.location.href='<?php echo $item->itemUrl; ?>';"
			class="gotolink hasTip"
			title="<?php echo JText::_('RL_TBGOTO'); ?>" />

	<div style="clear:both"></div>
</div>