<?php
/*------------------------------------------------------------------------
# com_k2store - K2Store 3.0
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
-------------------------------------------------------------------------*/

//no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
$state = @$this->state;
$items = @$this->items;
$row = @$this->row;
$task='setpricerange';
$action = JRoute::_( 'index.php?option=com_k2store&view=products&task=setpricerange&tmpl=component&id='.$row->id);
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/popup.php');
?>
<div class="k2store">
<h1>
	<?php echo JText::_( "K2STORE_PR_SET_RANGE_FOR" ); ?>
	:
	<?php echo $row->title; ?>
</h1>

<form action="<?php echo $action; ?>" method="post" name="adminForm" enctype="multipart/form-data">

	<div class="note row-fluid">

		<h3> <?php echo JText::_('K2STORE_PR_ADD_NEW_RANGE'); ?></h3>

		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th><?php echo JText::_( "K2STORE_PR_QUANTITY_START" ); ?></th>
					<th><?php echo JText::_( "K2STORE_PR_PRICE" ); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="text-align: center;"><input
						id="pricerange_quantity_start" name="pricerange_quantity_start"
						value="" size="10"/>
						&nbsp;<?php echo JText::_('K2STORE_PR_AND_ABOVE')?>
					</td>
					<td style="text-align: center;"><input id="pricerange_price"
						name="pricerange_price" value="" size="10"/>
					</td>
					<td>
					<input class="btn btn-primary" type="button"
						onclick="document.getElementById('task').value='createpricerange'; document.adminForm.submit();"
						value="<?php echo JText::_('K2STORE_PR_CREATE_RANGE'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="note_green row-fluid">
		<h3>
			<?php echo JText::_('K2STORE_PR_CURRENT_RANGE'); ?>
		</h3>
		<div class="pull-right">
			<button class="btn btn-info"
				onclick="document.getElementById('task').value='savepricerange'; document.getElementById('checkall-toggle').checked=true; k2storeCheckAll(document.adminForm); document.adminForm.submit();">
				<?php echo JText::_('K2STORE_SAVE_CHANGES'); ?>
			</button>
		</div>

		<div class="reset"></div>
		<table class="adminlist table table-striped" style="clear: both;">
			<thead>
				<tr>
					<th style="width: 20px;"><input type="checkbox"
						id="checkall-toggle" name="checkall-toggle" value=""
						title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
						onclick="Joomla.checkAll(this)" />
					</th>
					<th style="text-align: left;"><?php echo JHTML::_('grid.sort',  'K2STORE_PR_QUANTITY_START', 'a.quantity_start',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
					</th>
					<th style="text-align: left;"><?php echo JHTML::_('grid.sort',  'K2STORE_PR_PRICE', 'a.price',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
					</th>
					<th style="text-align: left;"><?php echo JHTML::_('grid.sort',  'K2STORE_PA_PRODUCT_ID', 'a.product_id',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
					</th>
					<th style="width: 100px;"><?php echo JHTML::_('grid.sort',  'K2STORE_ORDERING', 'a.ordering',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
					</th>
					<th style="width: 100px;"></th>
				</tr>
			</thead>
			<tbody>

				<?php $i=0; $k=0; ?>
				<?php foreach (@$items as $item) :
				$checked = JHTML::_('grid.id', $i, $item->productprice_id);
				?>
				<tr class='row<?php echo $k; ?>'>
					<td style="text-align: center;"><?php
					//echo JHTML::_('grid.checkedout',   $item, $i );
					echo $checked;
					?>
					</td>
					<td style="text-align: left;"><input type="text"
						name="quantity_start[<?php echo $item->productprice_id; ?>]"
						value="<?php echo $item->quantity_start; ?>" size="5" />
						&nbsp;<?php echo JText::_('K2STORE_PR_AND_ABOVE')?>
					</td>




					<td style="text-align: left;">
						<input type="text"
						name="price[<?php echo $item->productprice_id; ?>]"
						value="<?php echo $item->price; ?>"  size="10"/>
					</td>

					<td><?php echo $item->product_id; ?></td>
					<td style="text-align: center;"><input type="text"
						name="ordering[<?php echo $item->productprice_id; ?>]"
						value="<?php echo $item->ordering; ?>" size="1" />
					</td>

					<td style="text-align: center;">[<a
						href="index.php?option=com_k2store&view=products&task=deletepricerange&product_id=<?php echo $row->id; ?>&cid[]=<?php echo $item->productprice_id; ?>&return=<?php echo base64_encode("index.php?option=com_k2store&view=products&task=setpricerange&id={$row->id}&tmpl=component"); ?>">
							<?php echo JText::_( "K2STORE_PR_DELETE_RANGE" ); ?>
					</a> ]
					</td>
				</tr>
				<?php $i=$i+1; $k = (1 - $k); ?>
				<?php endforeach; ?>

				<?php if (!count(@$items)) : ?>
				<tr>
					<td colspan="10" align="center"><?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?>
					</td>
				</tr>
				<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20"><?php echo @$this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>

		<input type="hidden" name="order_change" value="0" /> <input
			type="hidden" name="id" value="<?php echo $row->id; ?>" /> <input
			type="hidden" name="task" id="task" value="setpricerange" /> <input
			type="hidden" name="boxchecked" value="" /> <input type="hidden"
			name="filter_order" value="<?php echo $this->lists['order']; ?>" /> <input
			type="hidden" name="filter_order_Dir"
			value="<?php echo $this->lists['order_Dir']; ?>" />
	</div>
</form>
</div>