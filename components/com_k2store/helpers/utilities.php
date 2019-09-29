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


function getOrderSummary()
	{

	    $params = JComponentHelper::getParams('com_k2store');
		$model = $this->getModel('mycart');
		$view = $this->getView( 'mycart', 'html' );
		$view->set( '_view', 'mycart' );
		$view->set( '_doTask', true);
		$view->set( 'hidemenu', true);
		$view->setModel( $model, true );
		$view->assign( 'items', $model->get('Data'));
		$view->assign( 'params', $params );
		$view->assign( 'summary', true );
		$view->setLayout( 'ajax' );

		ob_start();
		$view->display();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

Class K2StoreUtilities {

	public static function number($amount, $options='')
    {
        // default to whatever is in config
		$config = JComponentHelper::getParams('com_k2store');
        $options = (array) $options;
        $post = '';
        $pre = '';

        $default_currency = $config->get('currency_code', 'USD');
        $num_decimals = isset($options['num_decimals']) ? $options['num_decimals'] : $config->get('currency_num_decimals', '2');
        $thousands = isset($options['thousands']) ? $options['thousands'] : $config->get('currency_thousands', ',');
        $decimal = isset($options['decimal']) ? $options['decimal'] : $config->get('currency_decimal', '.');
        $currency_symbol = isset($options['currency']) ? $options['currency'] : $config->get('currency', '$');
        $currency_position = isset($options['currency_position']) ? $options['currency_position'] : $config->get('currency_position', 'pre');
        if($currency_position == 'post') {
			$post = $currency_symbol;
		} else {
			$pre = $currency_symbol;
		}

        //$return = $pre.number_format($amount, $num_decimals, $decimal, $thousands).$post;
        $return = number_format($amount, $num_decimals, $decimal, $thousands);
        return $return;
    }

	/**
	 * getItemName() - Get the name of an item.
	 *
	 * @param string $order_code The order code of the item.
	 */
	function getItemName($order_code) {
		$article_tbl=JTable::getInstance('K2Item','JTable');
		$article_tbl->load($order_code);
		return $article_tbl->title;
	}


}


?>

