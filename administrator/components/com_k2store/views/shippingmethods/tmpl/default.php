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
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'popup.php');
?>
<div class="k2store">
<h3><?php echo JText::_('K2STORE_SHIPPING_METHODS');?></h3>
<form action="index.php?option=com_k2store&view=shippingmethods" method="post" name="adminForm" id="adminForm">
<table class="adminlist table table-striped">
<tr>
	<td align="left" width="100%">
		<?php echo JText::_( 'K2STORE_FILTER_SEARCH' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
		<button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_GO' ); ?></button>
		<button class="btn btn-inverse" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_RESET' ); ?></button>
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
				<?php echo JHTML::_('grid.sort',  'K2STORE_SHIPM_ID', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'K2STORE_SHIPM_NAME', 'shipping_method_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'K2STORE_SHIPM_STATE', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>

		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
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

		$link 	= JRoute::_( 'index.php?option=com_k2store&view=shippingmethods&task=edit&cid[]='. $row->id );

		//$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$checked = JHTML::_('grid.id', $i, $row->id );

		$published 	= JHTML::_('grid.published', $row, $i );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<?php echo $this->escape($row->id); ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'K2STORE_SHIPM_VIEW_SHIPPING_METHOD' );?>::<?php echo $this->escape($row->shipping_method_name); ?>">
				<a href="<?php echo $link ?>" >
				<?php echo $this->escape($row->shipping_method_name); ?>
				</a>
				</span>
				<br/>
				<b><?php echo JText::_('K2STORE_SHIPM_TYPE')?></b>:
				<?php
				$model = $this->getModel('shippingmethods');
				$row->shipping_method_type = $model->getShippingMethodType($row->shipping_method_type);
				echo $this->escape($row->shipping_method_type); ?>

				<span style="float:right">
					[<?php echo K2StorePopup::popup( "index.php?option=com_k2store&view=shippingmethods&task=setrates&id=".$row->id."&tmpl=component", JText::_( "K2STORE_SHIPM_SET_RATES" ) ); ?>]
				</span>
			</td>

			<td align="center">
				<?php echo $published;?>
			</td>

		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
	<input type="hidden" name="option" value="com_k2store" />
	<input type="hidden" name="view" value="shippingmethods" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>