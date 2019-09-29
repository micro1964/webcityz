<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/


// no direct access
defined('_JEXEC') or die('Restricted access');

?>



<form action="index.php?option=com_k2store&view=addresses" method="post" name="adminForm" id="adminForm">
<table>
<tr>
<div class="alert alert-block">
<?php echo JText::_('K2STORE_ADDRESS_REFERENCE');?>
</div>
</tr>

<tr>
	<td align="left" width="100%">
		<?php echo JText::_( 'K2STORE_FILTER_SEARCH' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_RESET' ); ?></button>
	</td>
</tr>
</table>

	<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'K2STORE_NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_FIRSTNAME', 'a.first_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_LASTNAME', 'a.last_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_USER_ID', 'a.user_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_USERNAME', 'u.username', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="15%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_LINE1', 'a.address_1', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="15%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_LINE2', 'a.address_2', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="10%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_CITY', 'a.city', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="5%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_ZIP', 'a.zip', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="5%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_STATE', 'a.state', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="5%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_COUNTRY', 'a.country', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="5%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_PHONE', 'a.phone_1', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

			<th width="5%" class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_ADDRESS_MOBILE', 'a.phone_2', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="15">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = $this->items[$i];

		$link 	= JRoute::_( 'index.php?option=com_k2store&view=address&task=edit&cid[]='. $row->id );

		//$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$checked = JHTML::_('grid.id', $i, $row->id );
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td align="center">
				<?php echo $this->escape($row->first_name); ?>
			</td>
			<td align="center">
				<?php echo $this->escape($row->last_name); ?>
			</td>

			<td align="center">
				<?php if($row->user_id == 0):?>
				<?php echo JText::_('K2STORE_NA'); ?>
				<?php else: ?>
				<?php echo $this->escape($row->user_id); ?>
				<?php endif; ?>
			</td>

			<td align="center">
				<?php if($row->user_id == 0):?>
					<?php echo JText::_('K2STORE_GUEST'); ?>
				<?php else: ?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'K2STORE_ADDRESS_EDIT' );?>::<?php echo $this->escape($row->username); ?>">
				<a href="<?php echo $link ?>" >
				<?php echo $this->escape($row->username); ?>
				</a>
				</span>
				<?php endif; ?>
			</td>

			 <td>
				<?php echo $this->escape($row->address_1); ?>
			</td>

			<td>
				<?php echo $this->escape($row->address_2); ?>
			</td>

			<td align="center">
				<?php echo $row->city; ?>
			</td>
			<td align="center">
				<?php echo $row->zip; ?>
			</td>
			<td align="center">
			<?php if(empty($row->zone_name)):?>
				<?php echo $row->state; ?>
			<?php else: ?>	
			<?php echo $row->zone_name; ?>
			<?php endif; ?>	
			</td>
			<td align="center">
			<?php if(empty($row->country_name)):?>
				<?php echo $row->country; ?>
			<?php else: ?>	
				<?php echo $row->country_name; ?>
			<?php endif; ?>
			</td>
			<td align="center">
				<?php echo $row->phone_1; ?>
			</td>
			<td align="center">
				<?php echo $row->phone_2; ?>
			</td>
			</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>


	<input type="hidden" name="option" value="com_k2store" />
	<input type="hidden" name="view" value="addresses" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<div class="clr"></div>

