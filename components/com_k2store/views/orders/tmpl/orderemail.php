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



//no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/prices.php');
require_once (JPATH_SITE.'/components/com_k2store/helpers/orders.php');
$row = @$this->row;
$order = @$this->order;
$items = @$order->getItems();
if(JFactory::getUser()->id && empty($row->billing_first_name)) {
	$recipient_name = JFactory::getUser()->name;
}else {
	$recipient_name = $row->billing_first_name.'&nbsp;'.$row->billing_last_name;
}
?>

<div class="k2store_ordermail_header">
<?php echo JText::sprintf('K2STORE_ORDER_PLACED_HEADER', $recipient_name, $this->sitename, $row->order_id); ?>
</div>

<div>
	<h3 style='text-align: center;'>
		<?php echo JText::_( "K2STORE_ORDER_DETAIL" ); ?>
	</h3>
</div>

<div id="k2store_order_info">
	<table class="orders">
		<tr class="order_info">
			<td>
				<h3>
					<?php echo JText::_("K2STORE_ORDER_INFORMATION"); ?>
				</h3>
			</td>
			<td>
				<div>
					<table class="orderInfoTable">
						<tr>
							<td style="width: 90px"></td>
							<td></td>
						</tr>
						<tr>
							<td><strong><?php echo JText::_("K2STORE_ORDER_ID"); ?> </strong>
							</td>
							<td><?php echo @$row->order_id; ?>
							</td>
						</tr>
						<?php if($this->isGuest):?>
						<tr>
							<td><strong><?php echo JText::_("K2STORE_ORDER_TOKEN"); ?> </strong>
							</td>
							<td><?php echo @$row->token; ?>
							</td>
						</tr>
						<?php endif;?>
						<tr>
							<td><strong><?php echo JText::_("K2STORE_INVOICE_NO"); ?> </strong>
							</td>
							<td><?php echo @$row->id; ?>
							</td>
						</tr>
						<tr>
							<td><strong><?php echo JText::_("K2STORE_ORDER_DATE"); ?> </strong>
							</td>
							<td><?php echo JHTML::_('date', $row->created_date, $this->params->get('date_format', JText::_('DATE_FORMAT_LC1'))); ?>
							</td>
						</tr>
						<tr>
							<td><strong><?php echo JText::_("K2STORE_ORDER_STATUS"); ?> </strong>
							</td>
							<td><?php echo JText::_((@$row->order_state=='')?'':@$row->order_state); ?>
							</td>
						</tr>

					</table>
				</div>
			</td>
		</tr>

		<tr class="payment_info" style="background-color: #CEE0E8;">
			<td>
				<h3>
					<?php echo JText::_("K2STORE_ORDER_PAYMENT_INFORMATION"); ?>
				</h3>
			</td>
			<td>
				<div>
					<table class="paymentTable">
						<tr>
							<td></td>
						</tr>
						<tr>
							<td><strong><?php echo JText::_("K2STORE_ORDER_PAYMENT_AMOUNT"); ?> </strong>
							</td>
							<td><?php echo K2StorePrices::number( $row->order_total ); ?>
							</td>
						</tr>

								<tr>
								<td valign="top"><strong><?php echo JText::_("K2STORE_BILLING_ADDRESS"); ?> </strong></td>
								<td>


							<?php //TODO: legacy mode compatability. Those who do not have the order info will see this
							if(empty($row->billing_first_name)) {
								$billAddr =  K2StoreOrdersHelper::getAddress($row->user_id);
								echo $billAddr->first_name." ".$billAddr->last_name."<br/>";
								echo $billAddr->address_1.", ";
								echo $billAddr->address_2 ? $billAddr->address_2.", " : "<br/>";
								echo $billAddr->city.", ";
								echo $billAddr->state ? $billAddr->state." - " : "";
								echo $billAddr->zip." <br/>";
								echo $billAddr->country." <br/> ".JText::_('K2STORE_TELEPHONE').":";
								echo $billAddr->phone_1." , ";
								echo $billAddr->phone_2 ? $billAddr->phone_2.", " : "<br/> ";
								echo '<br/> ';
								echo $row->email;

							} else {
								echo $row->billing_first_name." ".$row->billing_last_name."<br/>";
								echo $row->billing_address_1.", ";
								echo $row->billing_address_2 ? $row->billing_address_2.", " : "<br/>";
								echo $row->billing_city.", ";
								echo $row->billing_zone_name ? $row->billing_zone_name." - " : "";
								echo $row->billing_zip." <br/>";
								echo $row->billing_country_name." <br/> ".JText::_('K2STORE_TELEPHONE').":";
								echo $row->billing_phone_1." , ";
								echo $row->billing_phone_2 ? $row->billing_phone_2.", " : "<br/> ";
								echo '<br/> ';
								echo $row->user_email;
							}
							?>
							</td>
						</tr>
						<?php if($this->params->get('show_shipping_address') ): ?>
						<tr>
							<td valign="top"><strong><?php echo JText::_("K2STORE_SHIPPING_ADDRESS"); ?> </strong> 							</td>
							<td>
							<?php //TODO: legacy mode compatability. Those who do not have the order info will see this
							if(empty($row->shipping_first_name)) {
								$shipAddr =  K2StoreOrdersHelper::getAddress($row->user_id);
								echo $shipAddr->first_name." ".$shipAddr->last_name."<br/>";
								echo $shipAddr->address_1.", ";
								echo $shipAddr->address_2 ? $shipAddr->address_2.", " : "<br/>";
								echo $shipAddr->city.", ";
								echo $shipAddr->state ? $shipAddr->state." - " : "";
								echo $shipAddr->zip." <br/>";
								echo $shipAddr->country." <br/> ".JText::_('K2STORE_TELEPHONE').":";
								echo $shipAddr->phone_1." , ";
								echo $shipAddr->phone_2 ? $shipAddr->phone_2.", " : "<br/> ";

							} else {
								echo $row->shipping_first_name." ".$row->shipping_last_name."<br/>";
								echo $row->shipping_address_1.", ";
								echo $row->shipping_address_2 ? $row->shipping_address_2.", " : "<br/>";
								echo $row->shipping_city.", ";
								echo $row->shipping_zone_name ? $row->shipping_zone_name." - " : "";
								echo $row->shipping_zip." <br/>";
								echo $row->shipping_country_name;

								echo $row->shipping_phone_1." , ";
								echo $row->shipping_phone_2 ? $row->shipping_phone_2.", " : "<br/> ";
							}
							?>
							</td>
						</tr>
					<?php endif; ?>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_("K2STORE_ORDER_ASSOCIATED_PAYMENT_RECORDS"); ?> </strong><br />
			</td>
			<td>
				<div>
					<table class="paymentTable">

						<tr>
							<td><strong><?php echo JText::_('K2STORE_ORDER_PAYMENT_TYPE'); ?> </strong></td>
							<td><?php echo JText::_($row->orderpayment_type); ?>
							</td>
						</tr>

						<?php if ($row->orderpayment_type == 'payment_offline') { ?>
						<tr>
							<td><strong><?php echo JText::_('K2STORE_ORDER_PAYMENT_MODE'); ?> </strong></td>
							<td><?php echo JText::_($row->transaction_details); ?>
							</td>
						</tr>
						<?php } ?>

						<tr>
							<td><strong><?php echo JText::_('K2STORE_ORDER_TRANSACTION_ID'); ?> </strong></td>
							<td><?php echo $row->transaction_id; ?>
							</td>
						</tr>
						<!--
						<tr>
							<td><strong><?php echo JText::_('K2STORE_ORDER_PAYMENT_STATUS'); ?> </strong></td>
							<td><?php echo JText::_($row->transaction_status); ?></td>
						</tr>
						 -->
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_("K2STORE_ORDER_CUSTOMER_NOTE"); ?> </strong><br />
			</td>
			<td>
				<table class="paymentTable">
					<tr>
						<td colspan="2"><?php echo $row->customer_note; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<div id="items_info">
	<h3>
		<?php echo JText::_("K2STORE_ITEMS_IN_ORDER"); ?>
	</h3>

	<table class="cart_order" style="clear: both;">
		<thead>
			<tr>
				<th style="text-align: left;"><?php echo JText::_("K2STORE_CART_ITEM"); ?></th>
				<th style="width: 150px; text-align: center;"><?php echo JText::_("K2STORE_CART_ITEM_QUANTITY"); ?>
				</th>
				<th style="width: 150px; text-align: right;"><?php echo JText::_("K2STORE_ITEM_PRICE"); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0; $k=0; ?>
			<?php foreach (@$items as $item) : ?>

			<tr class='row<?php echo $k; ?>'>
				<td> <?php echo JText::_( $item->orderitem_name ); ?> <br />

				<!-- start of orderitem attributes -->

						<!-- backward compatibility -->
						<?php if(!K2StoreOrdersHelper::isJSON($item->orderitem_attribute_names)): ?>

							<?php if (!empty($item->orderitem_attribute_names)) : ?>
								<span><?php echo $item->orderitem_attribute_names; ?></span>
							<?php endif; ?>
						<br />
						<?php else: ?>
						<!-- since 3.1.0. Parse attributes that are saved in JSON format -->
						<?php if (!empty($item->orderitem_attribute_names)) : ?>
                            <?php
                            	//first convert from JSON to array
                            	$registry = new JRegistry;
                            	$registry->loadString($item->orderitem_attribute_names, 'JSON');
                            	$product_options = $registry->toObject();
                            ?>
                            	<?php foreach ($product_options as $option) : ?>
             				   - <small><?php echo $option->name; ?>: <?php echo $option->value; ?></small><br />
            				   <?php endforeach; ?>
                            <br/>
                        <?php endif; ?>
					<?php endif; ?>
					<!-- end of orderitem attributes -->

					<?php if (!empty($item->orderitem_sku)) : ?> <b><?php echo JText::_( "K2STORE_SKU" ); ?>:</b>
					<?php echo $item->orderitem_sku; ?> <br /> <?php endif; ?> <b><?php echo JText::_( "K2STORE_CART_ITEM_UNIT_PRICE" ); ?>:</b>
					<?php echo K2StorePrices::number( $item->orderitem_price); ?>
				</td>
				<td style="text-align: center;"><?php echo $item->orderitem_quantity; ?>
				</td>
				<td style="text-align: right;"><?php echo K2StorePrices::number( $item->orderitem_final_price ); ?>
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>

			<?php if (empty($items)) : ?>
			<tr>
				<td colspan="10" align="center"><?php echo JText::_('K2STORE_NO_ITEMS'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2" style="text-align: right;"><?php echo JText::_( "K2STORE_CART_SUBTOTAL" ); ?>
				</th>
				<th style="text-align: right;"><?php echo K2StorePrices::number($order->order_subtotal); ?>
				</th>
			</tr>

			<?php if($row->order_shipping > 0):?>
			<tr>
				<th colspan="2" style="text-align: right;">
				<?php echo "(+)";?>
				<?php echo JText::_( "K2STORE_SHIPPING" ); ?>
				</th>
				<th style="text-align: right;"><?php echo K2StorePrices::number($row->order_shipping); ?>
				</th>
			</tr>
			<?php endif;?>

			<?php if($order->order_discount > 0):?>

			<tr>
				<th colspan="2" style="text-align: right;">
				<?php
				if (!empty($order->order_discount ))
                    	{
                            echo "(-)";
                            echo JText::_("K2STORE_CART_DISCOUNT");
                    	}
                   ?>
				</th>

				<th style="text-align: right;">
				<?php
				if (!empty($order->order_discount )) {
					echo K2StorePrices::number($order->order_discount);
				}
				?>
				</th>
			</tr>

			<?php endif; ?>

			<?php if($row->order_tax > 0):?>

			<tr>
				<th colspan="2" style="text-align: right;"><?php
				if (!empty($this->show_tax)) {
					echo JText::_("K2STORE_CART_PRODUCT_TAX_INCLUDED");
				}
				else { echo JText::_("K2STORE_CART_PRODUCT_TAX");
				}
				?>
				</th>
				<th style="text-align: right;"><?php echo K2StorePrices::number($row->order_tax); ?>
				</th>
			</tr>

			<?php endif; ?>

			<tr>
				<th colspan="2" style="font-size: 120%; text-align: right;"><?php echo JText::_( "K2STORE_CART_GRANDTOTAL" ); ?>
				</th>
				<th style="font-size: 120%; text-align: right;"><?php echo K2StorePrices::number($row->order_total); ?>
				</th>

			</tr>
		</tfoot>
	</table>
</div>

<?php if(!$this->isGuest): //show only if the buyer is not a guest. Because a guest cannot access the stored order information ?>
<div class="k2store_ordermail_footer">
 <?php echo JText::sprintf('K2STORE_ORDER_PLACED_FOOTER', $this->siteurl.'index.php?option=com_k2store&view=orders&task=view&id='.$row->id); ?>
</div>
<?php else:?>
<div class="k2store_ordermail_footer">
 <?php echo JText::sprintf('K2STORE_ORDER_GUEST_TOKEN', $this->siteurl.'index.php?option=com_k2store&view=orders&task=view', $order->token); ?>
</div>

<?php endif; ?>