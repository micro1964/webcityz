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


class K2StoreShipping {

	public function getTotal( $shipping_method_id, $orderItems )
	{

		$doc = JFactory::getDocument();

        $return = new JObject();
        $return->shipping_rate_id         = '0';
        $return->shipping_rate_price      = '0.00000';
        $return->shipping_rate_handling   = '0.00000';

        $rate_exists = false;
        // cast product_id as an array
        $orderItems = (array) $orderItems;

		// determine the shipping method type
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');
		$shippingmethod = JTable::getInstance( 'ShippingMethods', 'Table' );
		$shippingmethod->load( $shipping_method_id );

		if (empty($shippingmethod->id))
		{
			// TODO if this is an object, setError, otherwise return false, or 0.000?
			$return->setError( JText::_( "Undefined Shipping Method" ) );
			return $return;
		}

		switch($shippingmethod->shipping_method_type)
		{
			case "2":
		        // 5 = per order - price based
		        // Get the total of the order, and find the rate for that
		        $total = 0;
				foreach ($orderItems as $item)
                {
                	$total += $item->orderitem_final_price;
                }
		 	           JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'models' );
				       $model = JModelLegacy::getInstance('ShippingRates', 'K2StoreModel');
				       $model->setState('filter_shippingmethod', $shipping_method_id);
				       $model->setState('filter_weight', $total); // Use weight as total

				       $items = $model->getList();

				        if (empty($items))
				        {
				            return JTable::getInstance('ShippingRates', 'Table');
				        }

				        $rate = $items[0];

				        // if $rate->shipping_rate_id is empty, then no real rate was found
                        if (!empty($rate->shipping_rate_id))
                        {
                            $rate_exists = true;
                        }

		    	break;
		    case "1":
		        // 1 = per order - quantity based
		        // first, get the total quantity of shippable items for the entire order
		        // then, figure out the rate for this number of items (use the weight range field) + geozone
		    case "0":
				// 0 = per order - flat rate
				// if any of the products in the order require shipping
				$count_shipped_items = 0;
				$order_ships = false;
				//JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
				foreach ($orderItems as $item)
				{
				    // find out if the order ships
				    // and while looping through, sum the weight of all shippable products in the order
					$pid = $item->product_id;
					require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'k2item.php');
					$product_helper = new K2StoreItem;
					$isShips = $product_helper->isShippingEnabled($pid);

		            if (!empty($isShips))
		            {
		                $product_id = $item->product_id;
		                $order_ships = true;
		                $count_shipped_items += $item->orderitem_quantity;
		            }
				}

				if ($order_ships)
				{
				        switch( $shippingmethod->shipping_method_type )
                        {
                            case "1":
                                // quantity //based get the shipping rate for the entire order using the count of all products in the order that ship
                                $rate = $this->getRate( $shipping_method_id, $product_id, '1', $count_shipped_items );
                                break;

                            default:
                            case "0":
                                // flat rate // don't use weight, just do flat rate for entire order
                                // regardless of weight and regardless of the number of items
                                $rate = $this->getRate( $shipping_method_id, $product_id );
                                break;

                        }

                        // if $rate->shipping_rate_id is empty, then no real rate was found
                        if (!empty($rate->shipping_rate_id))
                        {
                            $rate_exists = true;
                        }

				}
                break;
            default:
			//	throw new Exception(JText::_( "Invalid Shipping Method Type" ));
              //  $doc->setError( JText::_( "Invalid Shipping Method Type" ) );
	            return false;
                break;
		}

		if (!$rate_exists)
		{
           // throw new Exception( JText::_( "No Rate Found" ) );
            return false;
		}

		$shipping_tax_rates = array();
        $shipping_method_price = 0;
        $shipping_method_handling = 0;
        $shipping_method_tax_total = 0;

		// now calc for the entire method
		/*
		foreach ($geozone_rates as $geozone_id=>$geozone_rate_array)
		{
		    foreach ($geozone_rate_array as $geozone_rate)
		    {
                $shipping_method_price += ($geozone_rate->shipping_rate_price * $geozone_rate->qty);
                $shipping_method_handling += $geozone_rate->shipping_rate_handling;

		    }
		}
	*/
        // return formatted object

    //   print_r($rate);
		$return->shipping_method_price    = $rate->shipping_rate_price;
		$return->shipping_method_handling = $rate->shipping_rate_handling;
		$return->shipping_method_total = $rate->shipping_rate_price + $rate->shipping_rate_handling;
	    $return->shipping_method_id     = $shipping_method_id;
        $return->shipping_method_name   = K2StoreShipping::getShippingName($shipping_method_id);

		return $return;
	}

   public function getRate( $shipping_method_id, $product_id='', $use_weight='0', $weight='0' )
    {
        // TODO Give this better error reporting capabilities
        JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'models' );
        $model = JModelLegacy::getInstance('ShippingRates', 'K2StoreModel');
        $model->setState('filter_shippingmethod', $shipping_method_id);


        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');

        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'k2item.php');
		$product_helper = new K2StoreItem;
		$isShips = $product_helper->isShippingEnabled($product_id);

        if (empty($isShips))
        {
            // product doesn't require shipping, therefore cannot impact shipping costs
            return JTable::getInstance('ShippingRates', 'Table');
        }

        if (!empty($use_weight) && $use_weight == '1')
        {
            if(!empty($weight))
            {
                $model->setState('filter_weight', $weight);
            }
        }
        $items = $model->getList();

        if (empty($items))
        {
            return JTable::getInstance('ShippingRates', 'Table');
        }

        return $items[0];
    }

    function getShippingName($id) {
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');
		$row = JTable::getInstance('ShippingMethods', 'Table');
		$row->load($id);

		switch($row->shipping_method_type) {

			case "2":
				return JText::_('Price Based Per Order');
			break;

			case "1":
				return JText::_('Quantity Based Per Order');
			break;

			case "0":
			default:
				return JText::_('Flat Rate Per Order');
			break;

		}
	}

}
