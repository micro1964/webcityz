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



 defined('_JEXEC') or die('Restricted access');
 $state = @$this->state;
 $items = @$this->items;
 $row = @$this->row;
 $action = JRoute::_( 'index.php?option=com_k2store&view=shippingmethods&task=setrates&tmpl=component&id='.$row->id);
  require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'popup.php');
 ?>
<div class="k2store">
<h3><?php echo JText::_( "K2STORE_SRATE_SET_RATE_FOR" ); ?>:
<?php 	echo $row->shipping_method_name; ?></h3>

<div class="note" style="width: 95%; text-align: center; margin-left: auto; margin-right: auto;">
	<?php echo JText::_( "K2STORE_SAVE_WORK" ); ?>:
	<!--
	<button onclick="document.adminForm.toggle.checked=true; Joomla.checkAll(this); document.getElementById('task').value='saverates'; document.adminForm.submit();"><?php echo JText::_('K2STORE_SAVE_CHANGES'); ?></button>
	 -->
</div>

<form action="<?php echo $action; ?>" method="post" name="adminForm" enctype="multipart/form-data">

    <table class="adminlist table table-striped">
        <tr>
            <td align="left" width="100%">
            </td>
            <td nowrap="nowrap">
            	<table class="adminlist table table-striped">
            	<thead>
            	<tr>
            		<th><?php echo JText::_( "K2STORE_SRATE_RANGE" ); ?></th>
            		<th><?php echo JText::_( "K2STORE_SRATE_PRICE" ); ?></th>
            		<th><?php echo JText::_( "K2STORE_SRATE_HANDLING_FEE" ); ?></th>
            		<th></th>
            	</tr>
            	</thead>
            	<tbody>
            	<tr>
					<td>
            			<input id="shipping_rate_weight_start" name="shipping_rate_weight_start" value="" size="5" />
            			<?php echo JText::_("K2STORE_SRATE_TO"); ?>
                		<input id="shipping_rate_weight_end" name="shipping_rate_weight_end" value="" size="5" />
                	</td>
            		<td>
            			<input id="shipping_rate_price" name="shipping_rate_price" value="" />
            		</td>
                    <td>
                        <input id="shipping_rate_handling" name="shipping_rate_handling" value="" />
                    </td>
            		<td>
            		<input type="hidden" name="shipping_method_id" value="<?php echo $row->shipping_method_id; ?>" />
            			<input type="button" class="btn btn-primary" onclick="document.getElementById('task').value='createrate'; document.adminForm.submit();" value="<?php echo JText::_('K2STORE_SRATE_CREATE_RATE'); ?>" class="button" />
            		</td>
            	</tr>
            	</tbody>
            	</table>
            </td>
        </tr>
    </table>
    <div class="pull-right">
    	<button class="btn btn-primary" onclick="document.getElementById('checkall-toggle').checked=true; k2storeCheckAll(document.adminForm); document.getElementById('task').value='saverates'; document.adminForm.submit();"><?php echo JText::_('K2STORE_SAVE_CHANGES'); ?></button>
    </div>
	<table class="adminlist table table-striped">
		<thead>
            <tr>
                <th style="width: 20px;">
                	<input type="checkbox" id="checkall-toggle" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th style="text-align: center;">
                	<?php echo JHTML::_('grid.sort',  'K2STORE_SRATE_RANGE', 'tbl.shipping_rate_weight_start',  $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <th style="text-align: center;">
					<?php echo JHTML::_('grid.sort',  'K2STORE_SRATE_PRICE', 'tbl.shipping_rate_price',  $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>

                <th style="text-align: center;">
                	<?php echo JHTML::_('grid.sort',  'K2STORE_SRATE_HANDLING_FEE', 'tbl.shipping_rate_handling',  $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
				<th>
				</th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $key=>$item) :
        $checked = JHTML::_('grid.id', $i, $item->shipping_rate_id);
        ?>
            <tr class='row<?php echo $k; ?>'>
				<td style="text-align: center;">
				<?php
					echo $checked;

					//echo K2StoreGrid::checkedout( $item, $i, 'shipping_rate_id' ); ?>
				</td>

				<td style="text-align: center;">
				    <input type="text" name="weight_start[<?php echo $item->shipping_rate_id; ?>]" value="<?php echo $item->shipping_rate_weight_start; ?>" />
				    <?php echo JText::_("K2STORE_SRATE_TO"); ?>
				    <input type="text" name="weight_end[<?php echo $item->shipping_rate_id; ?>]" value="<?php echo $item->shipping_rate_weight_end; ?>" />
				</td>
				<td style="text-align: center;">
					<input type="text" name="price[<?php echo $item->shipping_rate_id; ?>]" value="<?php echo $item->shipping_rate_price; ?>" />
				</td>
				<td style="text-align: center;">
					<input type="text" name="handling[<?php echo $item->shipping_rate_id; ?>]" value="<?php echo $item->shipping_rate_handling; ?>" />
				</td>
				<td style="text-align: center;">
					[<a href="index.php?option=com_k2store&view=shippingmethods&task=deleterates&id=<?php echo $row->id; ?>&cid[]=<?php echo $item->shipping_rate_id; ?>&return=<?php echo base64_encode("index.php?option=com_k2store&view=shippingmethods&task=setrates&id={$row->id}&tmpl=component"); ?>">
						<?php echo JText::_( "K2STORE_SRATE_DELETE_RATE" ); ?>
					</a>
					]
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>

			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="20">
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="task" id="task" value="setrates" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

	<?php //echo $this->form['validate']; ?>
</form>
</div>