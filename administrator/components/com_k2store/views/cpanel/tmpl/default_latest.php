<?php

?>
<?php if($this->orders):?>
<div class="k2store_latest_orders">
	<h3><?php echo JText::_('K2STORE_LATEST_ORDERS'); ?></h3>
	<table class="adminlist table table-striped table-bordered">
		<thead>
		<th><?php echo JText::_('K2STORE_DATE')?></th>
			<th><?php echo JText::_('K2STORE_ORDER_ID')?></th>
			<th><?php echo JText::_('K2STORE_EMAIL')?></th>
			<th><?php echo JText::_('K2STORE_AMOUNT')?></th>

		</thead>
		<tbody>
			<?php foreach($this->orders as $order):
			$link 	= 'index.php?option=com_k2store&view=orders&task=view&id='. $order->id;
			?>
			<tr>
				<td><?php echo JHTML::_('date', $order->created_date, $this->params->get('date_format', JText::_('DATE_FORMAT_LC1'))); ?>
				</td>
				<td><strong><a href="<?php echo $link; ?>"><?php echo $order->order_id; ?></a></strong></td>
				<td><?php echo $order->user_email; ?></td>
				<td><?php echo K2StorePrices::number( $order->orderpayment_amount, array( 'thousands'=>'' ) ); ?>
				</td>

			</tr>
			<?php endforeach;?>
		</tbody>

	</table>


</div>
<?php endif;?>
