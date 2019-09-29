<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

JToolBarHelper::title('JoomlaXTC Reading List');
JToolBarHelper::publish('publish','Enable');
JToolBarHelper::unpublish('unpublish','Disable');
JToolBarHelper::Preferences( 'com_jxtcreadinglist', 280, 450 );

JSubMenuHelper::addEntry( JText::_('RL_MENU_ENTRIES'), 'index.php?option=com_jxtcreadinglist&view=entries', true );
JSubMenuHelper::addEntry( JText::_('RL_MENU_ABOUT'), 'index.php?option=com_jxtcreadinglist&view=about', false );
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'RL_FILTER' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'RL_APPLY' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.getElementById('filter_component').value='';this.form.getElementById('filter_user').value='';this.form.getElementById('filter_category').value='';this.form.submit();"><?php echo JText::_( 'RL_RESET' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['user']; ?>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['component']; ?>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['category']; ?>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['state']; ?>
		</td>
	</tr>
</table>
<div id="tablecell">
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="title" style="white-space:nowrap;width:1%">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_DATE'), 'entry_date', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" style="white-space:nowrap;width:1%">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_USERNAME'), 'username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" style="white-space:nowrap;width:5%;text-align:center">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_CONTENTTYPE'), 'component', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" style="white-space:nowrap">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_CATEGORY'), 'category', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" style="white-space:nowrap">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_TITLE'), 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" style="white-space:nowrap;width:5%;text-align:center">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_STATUS'), 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="title" style="white-space:nowrap;width:1%">
					<?php echo JHTML::_('grid.sort',   JText::_('RL_HDR_ENTRYID'), 'item_id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$k = 0;
				foreach ($this->items as $i => $item) {
					?>
						<tr class="<?php echo "row$k"; ?>">
							<td>
								<?php echo JHTML::_('grid.checkedout',$item, $i ); ?>
							</td>
							<td style="white-space:nowrap">
								<?php echo $item->entry_date; ?>
							</td>
							<td style="white-space:nowrap">
								<?php echo $item->username; ?>
							</td>
							<td align="center">
								<?php echo JText::_('RL_COMPONENT_'.strtoupper($item->component)); ?>
							</td>
							<td>
								<?php echo $item->category; ?>
							</td>
							<td>
								<?php echo $item->title; ?>
							</td>
							<td align="center">
								<?php echo JHTML::_('grid.published', $item, $i ); ?>
							</td>
							<td align="right">
								<?php echo $item->item_id; ?>
							</td>
						</tr>
					<?php
					$k = 1 - $k;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

	<input type="hidden" name="option" value="com_jxtcreadinglist" />
	<input type="hidden" name="view" value="entries" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="listtype" value="p" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>