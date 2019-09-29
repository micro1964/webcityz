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

jimport('joomla.application.component.controller');
//load the cart data
require_once (JPATH_COMPONENT.'/helpers/cart.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/tax.php');
class K2StoreControllerMyCart extends K2StoreController
{

	private $_data = array();

	var $tax = null;

	public function __construct($config = array())
		{
			parent::__construct($config);
			header('Content-Type: text/html; charset=utf-8');
			$this->tax = new K2StoreTax();
			//language
			$language = JFactory::getLanguage();
			/* Set the base directory for the language */
			$base_dir = JPATH_SITE;
			/* Load the language. IMPORTANT Becase we use ajax to load cart */
			$language->load('com_k2store', $base_dir, $language->getTag(), true);
		}

	function display($cachable = false, $urlparams = array()) {

		//initialist system objects
		$app = JFactory::getApplication();
		$session=  JFactory::getSession();
		$params = JComponentHelper::getParams('com_k2store');
		$view = $this->getView( 'mycart', 'html' );
		$view->set( '_view', 'mycart' );
		//get post vars
		$post = $app->input->getArray($_POST);

		$model = $this->getModel('Mycart');

        if (K2StoreHelperCart::hasProducts()) {
        	$items = $model->getDataNew();
        } else {
        	$items = array();
        }

        //coupon
        $post_coupon = $app->input->getString('coupon', '');
        //first time applying? then set coupon to session
        if (isset($post_coupon) && !empty($post_coupon)) {
        	try {
        		if($this->validateCoupon()) {
        			$session->set('coupon', $post_coupon, 'k2store');
        			$msg = JText::_('K2STORE_COUPON_APPLIED_SUCCESSFULLY');
        		}
        	} catch(Exception $e) {
        		$msg = $e->getMessage();
        	}
        	$this->setRedirect( JRoute::_( "index.php?option=com_k2store&view=mycart"), $msg);
        }

        if ($post_coupon) {
        	$view->assign( 'coupon', $post_coupon);
        } elseif ($session->has('coupon', 'k2store')) {
        	$view->assign( 'coupon', $session->get('coupon', '', 'k2store'));
        } else {
        	$view->assign( 'coupon', '');
        }

		$cartobject = $this->checkItems($items, $params->get('show_tax_total'));

		$totals = $this->getTotals();

		$view->assign( 'cartobj', $cartobject);
		$view->assign( 'totals', $totals);
		$view->assign( 'model', $model);
		$view->assign( 'params', $params );
		if(isset($post['return'])) {
			$view->assign( 'return', $post['return']);
		}
		$view->set( '_doTask', true);
		$view->set( 'hidemenu', true);
		$view->setModel( $model, true );
		$view->setLayout( 'default');
		$view->display();

	}


	function add() {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_k2store');
		$model = $this->getModel('mycart');
		$cart_helper = new K2StoreHelperCart();
		require_once(JPATH_COMPONENT.'/helpers/cart.php');
		$error = array();
		$json = array();
		//get the product id
		$product_id = $app->input->getInt('product_id', 0);

		//no product id?. return an error
		if(empty($product_id)) {
			$error['error']['product']=JText::_('K2STORE_ADDTOCART_ERROR_MISSING_PRODUCT_ID');
			echo json_encode($error);
			$app->close();
		}


		//Ok. we have a product id. so proceed.
		//get the quantity
		$quantity = $app->input->get('product_qty');
		if (isset($quantity )) {
			$quantity = $quantity;
		} else {
			$quantity = 1;
		}

		//get the product options
		$options = $app->input->get('product_option', array(0), 'ARRAY');
		if (isset($options )) {
			$options =  array_filter($options );
		} else {
			$options = array();
		}
		$product_options = $model->getProductOptions($product_id);

		//iterate through stored options for this product and validate
		foreach($product_options as $product_option) {
			if ($product_option['required'] && empty($options[$product_option['product_option_id']])) {
				$json['error']['option'][$product_option['product_option_id']] = JText::sprintf('K2STORE_ADDTOCART_PRODUCT_OPTION_REQUIRED', $product_option['option_name']);
			}
		}

		//trigger before addtocart plugin event now... send post values
		$post_data = $app->input->getArray($_POST);
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin ('k2store');
		$results = $dispatcher->trigger( "onK2StoreBeforeAddCart", array( $post_data ));
		if(isset($results) && count($results)) {
			foreach ($results as $result) {
				if(!empty($result['error'])) {
					$json['warning'] =$result['error'];
				}

			}
		}


		//validation is ok. Now add the product to the cart.
		if(!$json) {
			$cart_helper->add($product_id, $quantity, $options);

			//trigger plugin event- after addtocart
			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin ('k2store');
			$dispatcher->trigger( "onK2StoreAfterAddCart", array( $post_data));

			$product_info = K2StoreHelperCart::getItemInfo($product_id);
			$cart_link = JRoute::_('index.php?option=com_k2store&view=mycart');
			$json['success'] = true;
			$json['successmsg'] = $product_info->product_name.JText::sprintf('K2STORE_ADDTOCART_ADDED_TO_CART');

			$total =  K2StoreHelperCart::getTotal();
			$product_count = K2StoreHelperCart::countProducts();
			//get product total
			$json['total'] = JText::sprintf('K2STORE_CART_TOTAL', $product_count, K2StorePrices::number($total));
			//get cart info

			//do we have to redirect to the cart
			if($params->get('popup_style', 1)==3) {
				$json['redirect'] = $cart_link;
			}


		} else {

			//do we have to redirect
		//	$url = 'index.php?option=com_k2&view=item&id='.$product_id;
		//	$json['redirect'] = JRoute::_($url);
		}
		echo json_encode($json);
		$app->close();
	}


	function ajaxmini() {
			//initialise system objects
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_k2store');

		$model = $this->getModel('mycart');
		if(K2StoreHelperCart::hasProducts()) {
			$totals = $this->getTotals();
			$product_count = K2StoreHelperCart::countProducts();
			$html = JText::sprintf('K2STORE_CART_TOTAL', $product_count, K2StorePrices::number($totals['total']));
		} else {
			$html = JText::_('K2STORE_NO_ITEMS_IN_CART');
		}
		echo $html;
		$app->close();
	}

	 /**
     *
     * @return unknown_type
     */
    function update()
    {
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_k2store');
        $model 	= $this->getModel('mycart');

        $key = $app->input->getString('key');

        $quantities = $app->input->get('quantity', array(0), 'ARRAY');

	   	$msg = JText::_('K2STORE_CART_UPDATED');

        $remove = $app->input->get('remove');
        if ($remove)
        {
        	$model->remove($key);
        }
        else
        {
            foreach ($quantities as $key=>$value)
            {
               $model->update($key, $value);
            }
        }

        $popup = JRequest::getVar('popup');
        if($popup && $remove) {
			$items = $model->getDataNew();

			$cartobject = $this->checkItems($items, $params->get('show_tax_total'));

			$view = $this->getView( 'mycart', 'html' );
			$view->set( '_view', 'mycart' );
			$view->set( '_doTask', true);
			$view->set( 'hidemenu', true);

			if($popup==1) {
				$view->setLayout( 'ajax');
			} else {
				$view->setLayout( 'default');
			}
			$view->setModel( $model, true );
			$totals = $this->getTotals();

			$view->assign( 'cartobj', $cartobject);
			$view->assign( 'totals', $totals);
			$view->assign( 'params', $params );
			$view->assign( 'popup', $popup );
			$view->assign( 'remove', $remove);

			ob_start();
			$view->display();
			$html = ob_get_contents();
			ob_end_clean();
			echo $html;
			$app->close();
		}

        $redirect = JRoute::_( "index.php?option=com_k2store&view=mycart");
       	$this->setRedirect( $redirect, $msg);
    }

    /**
     *
     * Method to check config, user group and product state (if recurs).
     * Then get right values accordingly
     * @param array $items - cart items
     * @param boolean - config to show tax or not
     * @return object
     */
    function checkItems( &$items, $show_tax=false)
    {
    	if (empty($items)) { return array(); }
		$params = JComponentHelper::getParams('com_k2store');

    	$this->_data['products'] = array();

    	foreach ($items as $product) {
    		$product_total = 0;

    		foreach ($items as $product_2) {
    			if ($product_2['product_id'] == $product['product_id']) {
    				$product_total += $product_2['quantity'];
    			}
    		}

    		//options
    		$option_data = array();

    		foreach ($product['option'] as $option) {

    			$value = $option['option_value'];
    			$option_data[] = array(
    					'name'  => $option['name'],
    					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
    			);
    		}

    		// Display prices
    		$price = $this->tax->calculate($product['price'], $product['tax_profile_id'], $params->get('show_tax_total'));

    		$total = $this->tax->calculate($product['price'], $product['tax_profile_id'], $params->get('show_tax_total')) * $product['quantity'];

    		$tax_amount = '';
    		$this->_data['products'][] = array(
    				'key'      => $product['key'],
    				'product_id'     => $product['product_id'],
    				'product_name'     => $product['name'],
    				'product_model'    => $product['model'],
    				'product_options'   => $option_data,
    				'quantity' => $product['quantity'],
    			//	'stock'    => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
    				'tax_amount'    => $tax_amount,
    				'price'    => $price,
    				'total'    => $total
    		);

    	}
    	$cartObj = JArrayHelper::toObject($this->_data['products']);
    	return $cartObj;
    }

    function validateCoupon() {

    	$app = JFactory::getApplication();
    	$coupon_info = K2StoreHelperCart::getCoupon($app->input->getString('coupon', ''));

    	if($coupon_info ) {
    		return true;
    	} else {
			throw new Exception(JText::_('K2STORE_COUPON_INVALID'));
    		return false;
    	}


    }

    function getTotals() {
		$app = JFactory::getApplication();
    	$session = JFactory::getSession();
		$model = $this->getModel('mycart');
		$products =$model->getDataNew();
    	$total_data = array();
    	$total = 0;
    	//sub total
    	$total_data['subtotal'] = K2StoreHelperCart::getSubtotal();
    	$total +=$total_data['subtotal'];
    	//taxes
    	$tax_data = array();
    	$taxes = K2StoreHelperCart::getTaxes();

    	//coupon
		if($session->has('coupon', 'k2store')) {
	    	$coupon_info = K2StoreHelperCart::getCoupon($session->get('coupon', '', 'k2store'));

	    	if ($coupon_info) {
	    		$discount_total = 0;

	    		if (!$coupon_info->product) {
	    			$sub_total =K2StoreHelperCart::getSubTotal();
	    		} else {
	    			$sub_total = 0;
	    			foreach ($products as $product) {
	    				if (in_array($product['product_id'], $coupon_info->product)) {
	    					$sub_total += $product['total'];
	    				}
	    			}
	    		}

	    		if ($coupon_info->value_type == 'F') {
	    			$coupon_info->value = min($coupon_info->value, $sub_total);
	    		}

	    		foreach ($products as $product) {
	    			$discount = 0;

	    			if (!$coupon_info->product) {
	    				$status = true;
	    			} else {
	    				if (in_array($product['product_id'], $coupon_info->product)) {
	    					$status = true;
	    				} else {
	    					$status = false;
	    				}
	    			}

	    			if ($status) {
	    				if ($coupon_info->value_type == 'F') {
	    					$discount = $coupon_info->value * ($product['total'] / $sub_total);
	    				} elseif ($coupon_info->value_type == 'P') {
	    					$discount = $product['total'] / 100 * $coupon_info->value;
	    				}

	    				if ($product['tax_profile_id']) {

	    					$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_profile_id']);
	    					foreach ($tax_rates as $tax_rate) {
	    						//	if ($tax_rate['value_type'] == 'P') {
	    						$taxes[$tax_rate['taxrate_id']] -= $tax_rate['amount'];
	    						//	}
	    					}
	    				}
	    			}

	    			$discount_total += $discount;
	    		}

	    	$total_data['coupon'] = array(
	    			'title'      => JText::sprintf('K2STORE_COUPON_TITLE', $session->get('coupon', '', 'k2store')),
	    			'value'      => -$discount_total
	    	);

	    	//$total_data['coupon'] = $coupon_data;
	    	//less the coupon discount in the total
	    	$total -= $discount_total;
	    	}

		}

    	//taxes
    	foreach ($taxes as $key => $value) {
    		if ($value > 0) {
    			$tax_data[]= array(
    					'title'      => $this->tax->getRateName($key),
    					'value'      => $value
    			);
    			$total += $value;
    		}
    	}
    	$total_data['taxes'] = $tax_data;

    	$total_data['total'] = $total;

    	return $total_data;

    }
}
