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
$state = @$this->state;
$order = @$this->order;
$items = @$this->orderitems;
$cart_edit_link = JRoute::_('index.php?option=com_k2store&view=mycart');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/prices.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/k2item.php');
?>
		  <h3><?php echo JText::_('K2STORE_CARTSUMMARY'); ?></h3>
           <table id="cart" class="adminlist table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th><?php echo JText::_( "K2STORE_CARTSUMMARY_PRODUCTS" ); ?></th>
                    <th><?php echo JText::_( "K2STORE_CARTSUMMARY_TOTAL" ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php $i=0; $k=0;?>
            <?php foreach ($items as $item) : ?>
				<?php
				$link = K2StoreItem::getK2Link($item->product_id);

				?>

                <tr class="row<?php echo $k; ?>">
                    <td>
                        <a href="<?php echo JRoute::_($link); ?>"><?php echo $item->orderitem_name; ?></a>
                        x <?php echo $item->orderitem_quantity; ?>
                        <br/>

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

                            <?php echo JText::_( "K2STORE_ITEM_PRICE" ); ?>:
                            <?php echo K2StorePrices::number($item->orderitem_price); ?>

                    </td>
                    <td style="text-align: right;">
                        <?php echo K2StorePrices::number($item->orderitem_final_price); ?>

                    </td>
                </tr>
            <?php ++$i; $k = (1 - $k); ?>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
               	<tr class="cart_subtotal">
                    <td style="font-weight: bold;">
                        <?php echo JText::_( "K2STORE_CART_SUBTOTAL" ); ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo K2StorePrices::number($order->order_subtotal); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
        <table class="adminlist table table-bordered">
                <tr>
                    <td style="text-align: right;">
                    <?php
                   		if (!empty($this->showShipping))
                    	{
                            echo JText::_("K2STORE_CART_SHIPPING_AND_HANDLING").":<br>";
                    	}

                    	if ($order->order_discount > 0)
                    	{
                            //echo JText::_("Discount")."&nbsp;(".$this->params->get('global_discount')."%) :";
                            echo "(-)";
                            echo JText::_("K2STORE_CART_DISCOUNT").":<br>";
                    	}

                    	if( $order->order_tax )
                    	{
                    		if (!empty($this->show_tax)) {
                    			echo JText::_("K2STORE_CART_PRODUCT_TAX_INCLUDED").":<br>";
                    		}
                    		else { echo JText::_("K2STORE_CART_PRODUCT_TAX").":<br>";
                    		}
                    	}

                    ?>
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                     <?php

                        if (!empty($this->showShipping))
                        {
                            echo K2StorePrices::number($order->order_shipping) . "<br>";
                        }
                        if ($order->order_discount > 0)
                    	{
                        	echo "(-)";
                        	echo K2StorePrices::number($order->order_discount);
                        	echo "<br />";
                    	}
                    	if( $order->order_tax )
                    		echo K2StorePrices::number($order->order_tax);

                    ?>
                    </td>
                </tr>
                <tr>
                	<td style="font-weight: bold; white-space: nowrap;">
                        <?php echo JText::_( "K2STORE_CART_GRANDTOTAL" ); ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo K2StorePrices::number($order->order_total); ?>
                    </td>
                </tr>
        </table>