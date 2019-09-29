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


/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.parameter' );
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/prices.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/tax.php');

class K2StoreHelperCart
{

	private $data = array();


	public function __construct() {

		$this->db = JFactory::getDbo();
		$cart = JFactory::getSession()->get('k2store_cart');
		if (!isset($cart) || !is_array($cart)) {
			JFactory::getSession()->set('k2store_cart', array());
		}
	}


public function add($product_id, $qty=1,  $options = array()) {

	$cart = JFactory::getSession()->get('k2store_cart');

	if (!$options) {
		$key = (int)$product_id;
	} else {
		$key = (int)$product_id . ':' . base64_encode(serialize($options));
	}

	if ((int)$qty && ((int)$qty > 0)) {
		if (!isset($cart[$key])) {
			$cart[$key] = (int)$qty;
		} else {
			$cart[$key] += (int)$qty;
		}
		JFactory::getSession()->set('k2store_cart', $cart);
	}

	$this->data = array();

}

public static function getProducts() {
	JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
	$model = JModelLegacy::getInstance( 'Mycart', 'K2StoreModel');

	//get the products from the cart
	$cartitems = $model->getDataNew();

	//now we have to prepare this data for adding into order table object
	$productitems = array();

	$cartitem= array();


	foreach ($cartitems as $cartitem)
	{
		if ($productItem = K2StoreHelperCart::getItemInfo($cartitem['product_id']))
		{

			//base price
			$price = $productItem->price;

			//now get special price or discounted prices, if any
			$price_override = K2StorePrices::getPrice($productItem->product_id, $cartitem['quantity']);

			if(isset($price_override) && !empty($price_override)) {
				$price = $price_override->product_price;
			}

			//$productItem->price = $productItem->product_price = $cartitem->product_price;

			// TODO Push this into the orders object->addItem() method?
			$orderItem = JTable::getInstance('OrderItems', 'Table');
			$orderItem->product_id                    = $productItem->product_id;
			$orderItem->orderitem_sku                 = $productItem->product_sku;
			$orderItem->orderitem_name                = $productItem->product_name;
			$orderItem->orderitem_quantity            = $cartitem['quantity'];
			//original price
			$orderItem->orderitem_price               = $price;

			//save product options in the json format
			$product_options = K2StoreHelperCart::getReadableProductOptions($cartitem['option']);

			$orderItem->orderitem_attributes          = $product_options->product_option_json;
			$orderItem->orderitem_attribute_names     = $product_options->product_option_names;
			$orderItem->orderitem_attributes_price    = $cartitem['option_price'];
			$orderItem->orderitem_final_price         = ($orderItem->orderitem_price + $orderItem->orderitem_attributes_price) * $orderItem->orderitem_quantity;

			array_push($productitems, $orderItem);
		}
	}
	return $productitems;

}

public static function getReadableProductOptions($product_options) {

	//initialise values
	$item = new JObject();
	$item->product_option_json = '';
	$item->product_option_names = '';
	$json = '';
	$registry = new JRegistry;
	//load product options into array
	$registry->loadArray($product_options);
	//convert to json
	$json = $registry->toString('JSON');
	$item->product_option_json = $json;
	$option_data = array();
	//now just get the option names
	foreach ($product_options as $option) {

		$value = $option['option_value'];
		$option_data[] = array(
				'name'  => $option['name'],
				'value' => $value
		);
	}
	$registry->loadArray($option_data);
	$item->product_option_names = $registry->toString('JSON');
	return $item;
}


public static function getAjaxCart($item) {

		require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/helpers/strapper.php');
		K2StoreStrapper::addJS();
		K2StoreStrapper::addCSS();
		$app = JFactory::getApplication();
		$html = '';
		JLoader::register( "K2StoreViewMyCart", JPATH_SITE."/components/com_k2store/views/mycart/view.html.php" );
		$layout = 'addtocart';
		$view = new K2StoreViewMyCart( );
		//$view->_basePath = JPATH_ROOT.DS.'components'.DS.'com_k2store';
		$view->addTemplatePath(JPATH_SITE.'/components/com_k2store/views/mycart/tmpl');
		$view->addTemplatePath(JPATH_SITE.'/templates/'.$app->getTemplate().'/html/com_k2store/mycart');
		require_once (JPATH_SITE.'/components/com_k2store/models/mycart.php');
		$model =  new K2StoreModelMyCart();
		$product_id = $item->product_id = $item->id;
		$view->assign( '_basePath', JPATH_SITE.DS.'components'.DS.'com_k2store' );
		$view->set( '_controller', 'mycart' );
		$view->set( '_view', 'mycart' );
		$view->set( '_doTask', true );
		$view->set( 'hidemenu', true );
		$view->setModel( $model, true );
		$view->setLayout( $layout );
		$view->assign( 'product_id', $product_id);
		$config = JComponentHelper::getParams('com_k2store');
		$show_tax = $config->get('show_tax_total','1');
	//	$show_attributes = $config->get( 'show_product_attributes', 1);
		$view->assign( 'show_tax', $show_tax );
		$view->assign( 'params', $config );
		//$view->assign( 'show_attributes', $show_attributes );

		//get attributes
		//$attributes = $model->getAttributes($product_id);
		$attributes = $model->getProductOptions($product_id);
		$item->attributes = $attributes;

		//quantity
		$item->product_quantity = 1;
		//base price
		$price = K2StorePrices::getItemPrice($product_id);


		$item->price = $price;

		//tax


		$t = new K2StoreTax();

		//assign tax class to the view. so that we dont have to call it everytime.
		$view->assign( 'tax_class', $t);

		$tax = $t->getProductTax($item->price,$item->product_id);
		$item->tax = 0;

			if($tax) {
				$item->tax = $tax;
			}

		//now get the special price
		$special_price = K2StorePrices::getSpecialPrice($product_id);
		$item->special_price =$special_price;
		$sp_tax = $t->getProductTax($item->special_price,$item->product_id);
		$item->sp_tax = 0;
		if($sp_tax) {
			$item->sp_tax = $sp_tax;
		}

		//now get the stock
		$item->product_stock = -1;

		$view->assign( 'attributes', $attributes );
		$view->assign( 'params', $config );


		//trigger onBeforeAddtocart plugin event
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin ('k2store');
		$results = $dispatcher->trigger( "onK2StoreBeforeCartDisplay", array( $item));
		$item->event->K2StoreBeforeCartDisplay = trim(implode("\n", $results));

		$view->assign( 'item', $item );
		ob_start( );
		$view->display( );
		$html = ob_get_contents( );
		ob_end_clean( );

		return $html;

}

public static function dispayPriceWithTax( $price = '0', $tax = '0', $plus='1')
	{
		$txt = '';
		//price+tax
		//first calculate tax
		if ( $plus==2 && $tax )
		{
			$txt .= K2StorePrices::number( $price+$tax );
			//$txt .= JText::sprintf('SHOW_TAX_WITH_PRICE', K2StorePrices::number($tax) );

		}elseif( $plus==3 && $tax )
		{
			$txt .= K2StorePrices::number( $price );
			$txt .= JText::sprintf('K2STORE_SHOW_TAX_WITH_PRICE', K2StorePrices::number($tax) );

		}
		else
		{
			$txt .= K2StorePrices::number( $price );
		}

		return $txt;
	}


	public static function getItemInfo($id) {
		$db		=JFactory::getDBO();
		$query= "SELECT * FROM #__k2_items WHERE id=".$id;
		$db->setQuery($query);
		$row = $db->loadObject();

		//create an object and return
		$item = new JObject;
		$item->product_id = $row->id;
		$item->product_name = $row->title;
		$item->price = K2StorePrices::getItemPrice($row->id);
		$item->product_sku = K2StorePrices::getItemSku($row->id);
		$item->tax_profile_id = K2StorePrices::getTaxProfileId($row->id);
		return $item;

	}

	/**
	 * Given an order_id, will remove the order's items from the user's cart
	 *
	 * @param $order_id
	 * @return unknown_type
	 */
	public static function removeOrderItems( $orderpayment_id )
	{
		// load the order to get the user_id
		JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
		$model = JModelLegacy::getInstance( 'MyCart', 'K2StoreModel' );
		$model->clear();
		return;
	}


	public static function getTaxes() {
		$tax_data = array();
		JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
		$model = JModelLegacy::getInstance( 'Mycart', 'K2StoreModel');
		$products = $model->getDataNew();
		$t = new K2StoreTax();
		foreach ($products as $product) {
			if ($product['tax_profile_id']) {
				$tax_rates = $t->getRates($product['price'], $product['tax_profile_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['taxrate_id']])) {
						$tax_data[$tax_rate['taxrate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['taxrate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public static function getTotal() {
		$total = 0;
		JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
		$model = JModelLegacy::getInstance( 'Mycart', 'K2StoreModel');
		$products = $model->getDataNew();
		$t = new K2StoreTax();
		$tax_amount = 0;
		foreach ($products as $product) {
			//calculate the tax
			$tax_amount = $t->getProductTax($product['price'], $product['product_id']);
			if($tax_amount) {
				$total += ($product['price']+$tax_amount)*$product['quantity'];
			} else {
				$total += $product['price']*$product['quantity'];
			}
		}

		return $total;
	}

	public static function taxCalculate($price, $taxrate) {
		$tax_amount = 0;
		if($taxrate > 0) {
			$tax_amount = $price*$taxrate;
		}
		//add tax to price
		$price = $price+$tax_amount;

		return $price;
	}

	public static function getSubTotal() {
		$total = 0;
		JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
		$model = JModelLegacy::getInstance( 'Mycart', 'K2StoreModel');

		$products = $model->getDataNew();
		foreach ($products as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public static function countProducts() {
		$product_total = 0;

		JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
		$model = JModelLegacy::getInstance( 'Mycart', 'K2StoreModel');

		$products = $model->getDataNew();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}
		return $product_total;
	}

	public static function hasProducts() {
		$cart_items = JFactory::getSession()->get('k2store_cart');
		return count($cart_items);
	}

	public static function getCoupon($code) {
		$status = true;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__k2store_coupons')->where('coupon_code='.$db->q($code))->where('state=1');
		$query->where("((valid_from = '0000-00-00' OR valid_from < NOW()) AND (valid_to = '0000-00-00' OR valid_to > NOW()))");
		$db->setQuery($query);
		$row = $db->loadObject();

		//now get coupon history total
		$query = $db->getQuery(true);
		$query->select('COUNT(*) AS total')->from('#__k2store_order_coupons')->where('coupon_id='.$db->q($row->coupon_id));
		$db->setQuery($query);
		$coupon_history = $db->loadResult();

		$product_data = array();

		//now validate
		if($row) {
			//is used coupons count exceeds max use?
			if($row->max_uses > 0 && ($coupon_history >= $row->max_uses)) {
				$status = false;
			}

			//is customer loged
			if ($row->logged && !$user->id) {
				$status = false;
			}

			if ($user->id) {
				$query = $db->getQuery(true);
				$query->select('COUNT(*) AS total')->from('#__k2store_order_coupons')
				->where('coupon_id='.$db->q($row->coupon_id))
				->where('customer_id='.$db->q($user->id));
				$db->setQuery($query);
				$customer_total = $db->loadResult();
					if ($row->max_customer_uses > 0 && ($customer_total  >= $row->max_customer_uses)) {
					$status = false;
				}
			}

			//categories
			if($row->product_category) {
				$coupon_categories = explode(',', $row->product_category);
				if(is_array($coupon_categories )) {
				JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );
				$model = JModelLegacy::getInstance( 'Mycart', 'K2StoreModel');
				$products = $model->getDataNew();
				$product_data = array();
					foreach ($products as $product) {
						foreach($coupon_categories as $category_id) {
							$query = $db->getQuery(true);
							$query->select('COUNT(*) AS total')->from('#__k2_items')
							->where('id='.$db->q($product['product_id']))
							->where('catid='.$db->q($category_id));

							$db->setQuery($query);
							if($db->loadResult()) {
								$product_data[]=$product['product_id'];
							}
							continue;
						}
					}

					if (!$product_data) {
						$status = false;
					}
				}
			}
		} else {
			$status = false;
		}

		//if true
		if ($status) {
			$data = $row;
			if($product_data) {
				$data->product = $product_data;
			} else {
				$data->product = array();
			}
			return $data;
		}

		return false;
	}
 }