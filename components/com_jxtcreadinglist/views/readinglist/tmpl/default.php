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
<div class="readinglist-list<?php echo (isset($this->pageclass_sfx) ? $this->pageclass_sfx : ''); ?>">
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
			echo '<div class="readinglist_options">';
			?>
			<div class="row-fluid rlactionbar" style="vertical-align:top">
				<form method="post" name="readingListForm" id="readingListForm" class="span6">
				  <input type="hidden" id="rlid" name="id" value="" />
				  <input type="hidden" name="task" value="" />
				  <input type="hidden" name="view" value="readinglist" />
				  <input type="hidden" name="option" value="com_jxtcreadinglist" />
					<?php echo $this->categorySelector; ?>
				  <?php echo JHTML::_('form.token'); ?>
				</form>
				<?php
					if ($this->params->get('showshare',1)) {
						$onclick = "window.open(this.href,'rlcopy','width=700,height=400,menubar=no,resizable=yes'); return false;";
						$link = 'index.php?option=com_jxtcreadinglist&tmpl=component&view=copy&cid='.$this->cid;
		
						echo '<a class="rl_link rlcopy" href="'.$link.'" title="'.JText::_('RL_COPYTIP').'" onclick="'.$onclick.'">';
						echo '<i class="rlicon-paste"></i> '.JText::_('RL_COPY');
						echo '</a>';
					}
		
					if ($this->params->get('showemail',1)) {
						require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';
		
						$mailLink = jxtcrlhelper::getEmailLink($this->cid);
						$onclick = "window.open(this.href,'rlemail','width=400,height=350,menubar=no,resizable=yes'); return false;";
						$link	= 'index.php?option=com_mailto&tmpl=component&link='.MailToHelper::addLink($mailLink);
		
						echo '<a class="rl_link rlemail" href="'.$link.'" title="'.JText::_('RL_EMAILTIP').'" onclick="'.$onclick.'">';
						echo '<i class="rlicon-mail"></i> '.JText::_('RL_EMAIL');
						echo '</a>';
						
					}
				?>
			</div>
			<div style="clear:both"></div>
			<?php
			$categories = array_keys($this->items);
			sort($categories);

			echo JHtml::_('bootstrap.startaccordion', 'readinglist');
			foreach ($categories as $category) {
				?><div class="category_title"><?php
					echo $category;
				?></div><?php
	
				foreach ($this->items[$category] as $this->item) {
					$itemdate = isset($this->item->published) ? $this->item->published : $this->item->modified; 
					$title = $this->item->title.'<i class="rlicon-toggle pull-right"></i><div class="header_date"><span class="author">'.JText::sprintf('RL_TBAUTHOR',$this->item->author).'</span><span class="date">'.JHtml::_('Date',$this->item->modified,JText::_('RL_TBDATE')).'</span></div>';
					?>
						<div class="accordion-group">
							<div class="accordion-heading">
								<div class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#readinglist" href="#entry<?php echo $this->item->id; ?>">
									<?php echo $title; ?>
								</div>
							</div>

							<div id="entry<?php echo $this->item->id; ?>" class="accordion-body collapse">
								<div class="accordion-inner">
									<div class="rlcontent">
										<?php echo $this->loadTemplate($this->item->component); ?>
									</div>
								</div>
							</div>

						</div>
					<?php
				}
			}
			echo JHtml::_('bootstrap.endaccordion');
		}
	?>
</div>