<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

JHTML::_('behavior.tooltip');
?>
<div class="readinglist_shared<?php echo (isset($this->pageclass_sfx) ? $this->pageclass_sfx : ''); ?>">
	<?php if ($this->params->get('show_page_heading', 1)) { ?>
		<h1>
			<?php if ($this->escape($this->params->get('page_heading'))) :?>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			<?php else : ?>
				<?php echo $this->escape($this->params->get('page_title')); ?>
			<?php endif; ?>
		</h1>
	<?php }

		if (empty($this->items)) {
			echo JText::_('RL_NOITEMS');
		}
		else {

			echo '<div class="shared_intro">'.JText::sprintf('RL_SHAREDLISTINTRO',$this->user->name).'</div>';
			$categories = array_keys($this->items);
			sort($categories);
	
			echo JHtml::_('bootstrap.startAccordion', 'readinglist');
			foreach ($categories as $category) {
				?><div class="category_title"><?php
					echo $category;
				?></div><?php
	
				foreach ($this->items[$category] as $item) {
					$itemdate = isset($item->published) ? $item->published : $item->modified; 
					$title = $item->title . '<div class="header_date">'.JHtml::_('date',$item->modified,JText::_('RL_DATE')).'</div>';
					?>
						<div class="accordion-group">
							<div class="accordion-heading">
								<div class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#readinglist" href="#entry<?php echo $item->id; ?>">
									<?php echo $title; ?>
								</div>
							</div>

							<div id="entry<?php echo $item->id; ?>" class="accordion-body collapse">
								<div class="accordion-inner">
									<div class="rlcontent">
										<?php require 'default_'.$item->component.'.php'; ?>
									</div>
								</div>
							</div>

						</div>
					<?php
				}
			}
			echo JHtml::_('bootstrap.endAccordion');
		}
	?>
</div>