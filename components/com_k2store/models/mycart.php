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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filter.filterinput' );
jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
JLoader::register('K2StoreModel',  JPATH_ADMINISTRATOR.'/components/com_k2store/models/model.php');

class K2StoreModelMyCart extends K2StoreModel {

	private $_product_id;
	private $_data = array();


	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function getDataNew()
	{
		require_once (JPATH_SITE.'/components/com_k2store/helpers/cart.php');

		$session = JFactory::getSession();

		// Lets load the content if it doesn't already exist
		if (empty($this->_data) && count($session->get('k2store_cart')))
		{

			foreach ($session->get('k2store_cart') as $key => $quantity) {

				$product = explode(':', $key);
				$product_id = $product[0];
				$stock = true;

				// Options
				if (isset($product[1])) {
					$options = unserialize(base64_decode($product[1]));
				} else {
					$options = array();
				}

				//now get product details
				$product_info = K2StoreHelperCart::getItemInfo($product_id);

				//now get product options
				if($product_info) {
					$option_price = 0;
					$option_data = array();

					foreach ($options as $product_option_id => $option_value) {

						$product_option = $this->getCartProductOptions($product_option_id , $product_id);

						if ($product_option) {
							if ($product_option->type == 'select' || $product_option->type == 'radio') {

								//ok now get product option values
								$product_option_value = $this->getCartProductOptionValues($product_option->product_option_id, $option_value );

								if ($product_option_value) {
									if ($product_option_value->product_optionvalue_prefix == '+') {
										$option_price += $product_option_value->product_optionvalue_price;
									} elseif ($product_option_value->product_optionvalue_prefix == '-') {
										$option_price -= $product_option_value->product_optionvalue_price;
									}

									$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_optionvalue_id' => $option_value,
											'option_id'               => $product_option->option_id,
											'optionvalue_id'         => $product_option_value->optionvalue_id,
											'name'                    => $product_option->option_name,
											'option_value'            => $product_option_value->optionvalue_name,
											'type'                    => $product_option->type,
											'price'                   => $product_option_value->product_optionvalue_price,
											'price_prefix'            => $product_option_value->product_optionvalue_prefix

									);
								}
							}
						}
					}

					//get the product price

					//base price
					$price = $product_info->price;

					//we may have special price or discounts. so check
					$price_override = K2StorePrices::getPrice($product_info->product_id, $quantity);

					if(isset($price_override) && !empty($price_override)) {
						$price = $price_override->product_price;
					}

					$this->_data[$key] = array(
							'key'             => $key,
							'product_id'      =>  $product_info->product_id,
							'name'            =>  $product_info->product_name,
							'model'           =>  $product_info->product_sku,
							'option'          => $option_data,
							'option_price'    => $option_price,
							'quantity'        => $quantity,
							'tax_profile_id'  => $product_info->tax_profile_id,
							'price'           => ($price + $option_price),
							'total'           => ($price + $option_price) * $quantity

					);

				} // end of product info if
				else {
					$this->remove($key);
				}
			}
		}
		return $this->_data;
	}

	 public function getShippingIsEnabled()
    {
	   	$model = JModelLegacy::getInstance( 'MyCart', 'K2StoreModel');
		$list = $model->getDataNew();

    	// If no item in the list, return false
        if ( empty( $list ) )
        {
          	return false;
        }

        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'k2item.php');
        $product_helper = new K2StoreItem();
        foreach ($list as $item)
        {
           	$shipping = $product_helper->isShippingEnabled($item['product_id']);
        	if ($shipping)
        	{
        	    return true;
        	}
        }

        return false;
    }

    function getProductOptions($product_id) {

    	//first get the product options
    	$db = JFactory::getDbo();
    	$product_option_data = array();
    	$query = $db->getQuery(true);
    	$query->select('po.*');
    	$query->from('#__k2store_product_options AS po');
    	$query->where('po.product_id='.$product_id);

    	//join the options table to get the name
    	$query->select('o.option_name, o.type');
    	$query->join('LEFT', '#__k2store_options AS o ON po.option_id=o.option_id');
    	$query->where('o.state=1');
    	$query->order('po.product_option_id ASC');

    	$db->setQuery($query);
    	$product_options = $db->loadObjectList();
		//now prepare to get the product option values
    	foreach($product_options as $product_option) {

    		//if multiple choices available, then we got to get them
    		if ($product_option->type == 'select' || $product_option->type == 'radio' || $product_option->type == 'checkbox') {

    			$product_option_value_data = array();

    			$product_option_values = $this->getProductOptionValues($product_option->product_option_id, $product_option->product_id);

    			foreach ($product_option_values as $product_option_value) {
    				$product_option_value_data[] = array(
    						'product_optionvalue_id' 		=> $product_option_value->product_optionvalue_id,
    						'optionvalue_id'         		=> $product_option_value->optionvalue_id,
    						'optionvalue_name'       		=> $product_option_value->optionvalue_name,
    						'product_optionvalue_price' 	=> $product_option_value->product_optionvalue_price,
    						'product_optionvalue_prefix'	=> $product_option_value->product_optionvalue_prefix
    				);
    			}

    			$product_option_data[] = array(
    					'product_option_id' => $product_option->product_option_id,
    					'option_id'         => $product_option->option_id,
    					'option_name'		=> $product_option->option_name,
    					'type'              => $product_option->type,
    					'optionvalue'       => $product_option_value_data,
    					'required'          => $product_option->required
    			);

    		} else {

    			//if no option values are present, then
    			$product_option_data[] = array(
    					'product_option_id' => $product_option->product_option_id,
    					'option_id'         => $product_option->option_id,
    					'option_name'		=> $product_option->option_name,
    					'type'              => $product_option->type,
    					'optionvalue'       => '',
    					'required'          => $product_option->required
    			);
    		} //endif
    	} //end product option foreach

    	return $product_option_data;
    }

    function getProductOptionValues($product_option_id, $product_id) {

    	//first get the product options
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('pov.*');
    	$query->from('#__k2store_product_optionvalues AS pov');
    	$query->where('pov.product_id='.$product_id);
    	$query->where('pov.product_option_id='.$product_option_id);

    	//join the optionvalues table to get the name
    	$query->select('ov.optionvalue_id, ov.optionvalue_name');
    	$query->join('LEFT', '#__k2store_optionvalues AS ov ON pov.optionvalue_id=ov.optionvalue_id');
    	$query->order('pov.ordering ASC');

    	$db->setQuery($query);
    	$product_option_values = $db->loadObjectList();
    	return $product_option_values;
    }

    function getCartProductOptions($product_option_id , $product_id) {

    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('po.*');
    	$query->from('#__k2store_product_options AS po');
    	$query->where('po.product_option_id='.$product_option_id);
    	$query->where('po.product_id='.$product_id);

    	//join the options table to get the name
    	$query->select('o.option_name, o.type');
    	$query->join('LEFT', '#__k2store_options AS o ON po.option_id=o.option_id');
    	$query->order('o.ordering ASC');
    	$db->setQuery($query);

    	$product_option = $db->loadObject();
    	return $product_option;
    }

    function getCartProductOptionValues($product_option_id, $option_value ) {

    	//first get the product options
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('pov.*');
    	$query->from('#__k2store_product_optionvalues AS pov');
    	$query->where('pov.product_optionvalue_id='.$option_value);
    	$query->where('pov.product_option_id='.$product_option_id);

    	//join the optionvalues table to get the name
    	$query->select('ov.optionvalue_id, ov.optionvalue_name');
    	$query->join('LEFT', '#__k2store_optionvalues AS ov ON pov.optionvalue_id=ov.optionvalue_id');
    	$query->order('pov.ordering ASC');

    	$db->setQuery($query);
    	$product_option_value = $db->loadObject();
    	return $product_option_value;
    }


    public function update($key, $qty) {
    	$cart = JFactory::getSession()->get('k2store_cart');
    	if ((int)$qty && ((int)$qty > 0)) {
    		$cart[$key] = (int)$qty;
    	} else {
    		$this->remove($key);
    	}
    	JFactory::getSession()->set('k2store_cart', $cart);
    	$this->_data = array();
    }


    public function remove($key) {
    	$cart = JFactory::getSession()->get('k2store_cart');

    	if (isset($cart[$key])) {
    		unset($cart[$key]);
    	}
    	JFactory::getSession()->set('k2store_cart', $cart);
    	$this->_data = array();
    }

    public function clear() {
    	JFactory::getSession()->set('k2store_cart', array());
    	$this->_data = array();
    }

}
