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

class K2StoreControllerOrders extends K2StoreController {

    function display($cachable = false, $urlparams = array()) {
        JRequest::setVar('view', 'orders');
       parent::display($cachable = false, $urlparams = array());
    }


    function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('orders');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_k2store&view=orders', 'Deleted item(s)' );
	}


	function view() {
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables' );

		require_once( JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'models'.DS.'orders.php' );
		$orders_model = new K2StoreModelOrders;
		$id = JRequest::getVar('id');
		$orders_model  = $this->getModel('orders');
		$orders_model->setId($id);
		$order = $orders_model->getTable( 'orders' );
        $row = $order->load($orders_model->getId());
        $orderitems = $order->getItems();
        $row = $orders_model->getItem();
        $view = $this->getView( 'orders', 'html' );

        $view->set( '_controller', 'orders' );
        $view->set( '_view', 'orders' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        //$view->setModel( $orders_model, true );
        $view->assign( 'row', $row );
		$params = JComponentHelper::getParams('com_k2store');
		$show_tax = $params->get('show_tax_total');
        $view->assign( 'show_tax', $show_tax );

        foreach ($orderitems as &$item)
        {
      		$item->orderitem_price = $item->orderitem_price + floatval( $item->orderitem_attributes_price );
        	$taxtotal = 0;
            if($show_tax)
            {
            	$taxtotal = ($item->orderitem_tax / $item->orderitem_quantity);
            }
            $item->orderitem_price = $item->orderitem_price + $taxtotal;
            $item->orderitem_final_price = $item->orderitem_price * $item->orderitem_quantity;
            $order->order_subtotal += ($taxtotal * $item->orderitem_quantity);
        }

        //get the select list;
        $status_values = array(1 => JText::_('K2STORE_CONFIRMED'), 3 => JText::_('K2STORE_FAILED'), 4 => JText::_('K2STORE_PENDING'));
        $options = array();
        $options[] = JHTML::_('select.option', '', JText::_('K2STORE_ORDER_SELECT_STATE'));
        foreach($status_values as $key=>$value) :
			$options[] = JHTML::_('select.option', $key, $value);
		endforeach;

		$order_state = JHTML::_('select.genericlist', $options, 'order_state_id', 'class="inputbox"', 'value', 'text', $order->order_state_id);

         //print_r($order);     exit;
        $view->assign( 'order', $order );
        $view->assign( 'order_state', $order_state );
        $view->assign( 'params', $params );
        $view->setLayout( 'view' );
        $this->_setModelState();
        $view->display();
    }


    function printOrder() {

		require_once( JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'models'.DS.'orders.php' );
		$orders_model = new K2StoreModelOrders;
		$id = JRequest::getVar('id');
		$orders_model  = $this->getModel('orders');
		$orders_model->setId($id);
		$order = $orders_model->getTable( 'orders' );
        $row = $order->load($orders_model->getId());
        $orderitems = $order->getItems();
        $row = $orders_model->getItem();
        $view = $this->getView( 'orders', 'html' );

        if(!empty($row->shipping_addr_id)){
        	$ship_address = JTable::getInstance('Address','Table');
        	$ship_address->load($row->shipping_addr_id);
        	$view->assign( 'ship_address', $ship_address );
        }

        if(!empty($row->billing_addr_id)){
        	$bill_address = JTable::getInstance('Address','Table');
        	$bill_address->load($row->billing_addr_id);
        	$view->assign( 'bill_address', $bill_address );
        }

        $view->set( '_controller', 'orders' );
        $view->set( '_view', 'orders' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        //$view->setModel( $orders_model, true );
        $view->assign( 'row', $row );
		$params = JComponentHelper::getParams('com_k2store');
		$show_tax = $params->get('show_tax_total');
        $view->assign( 'show_tax', $show_tax );

        foreach ($orderitems as &$item)
        {
      		$item->orderitem_price = $item->orderitem_price + floatval( $item->orderitem_attributes_price );
        	$taxtotal = 0;
            if($show_tax)
            {
            	$taxtotal = ($item->orderitem_tax / $item->orderitem_quantity);
            }
            $item->orderitem_price = $item->orderitem_price + $taxtotal;
            $item->orderitem_final_price = $item->orderitem_price * $item->orderitem_quantity;
            $order->order_subtotal += ($taxtotal * $item->orderitem_quantity);
        }
         //print_r($order);     exit;
        $view->assign( 'order', $order );
        $view->assign( 'params', $params );
        $view->setLayout( 'print' );
        $this->_setModelState();
        $view->display();
    }

    function viewtxnlog() {

    	$app = JFactory::getApplication();
    	if(!$app->isAdmin()) {
    		echo JText::_('K2STORE_ERROR_NO_PERMISSION');
    		$app->close();
    		return;
    	}

    	require_once( JPATH_SITE.'/components/com_k2store/models/orders.php' );
    	$orders_model = new K2StoreModelOrders;
    	$id = $app->input->get('id');
    	$orders_model->setId($id);
    	$row = $orders_model->getItem();
    	$view = $this->getView( 'orders', 'html' );
    	$view->assign( 'row', $row );
    	$view->setLayout( 'txnlog' );
    	$view->display();
    }



	   function _setModelState()
    {
	    $app = JFactory::getApplication();
	    $params = JComponentHelper::getParams('com_k2store');
        $model = $this->getModel('orders');
        $ns = 'com_k2store.orders';

		$state = array();
		$state['limit']  	= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$state['limitstart'] = $app->getUserStateFromRequest($ns.'limitstart', 'limitstart', 0, 'int');
		$state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.'.$model->getTable()->getKeyName(), 'cmd');
		$state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'ASC', 'word');
		$state['filter']    = $app->getUserStateFromRequest($ns.'.filter', 'filter', '', 'string');
		$state['filter_enabled'] 	= $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', '');
		$state['id']        = JRequest::getVar('id', JRequest::getVar('id', '', 'get', 'int'), 'post', 'int');


        // adjust offset for when filter has changed
        if (
            $app->getUserState( $ns.'orderstate' ) != $app->getUserStateFromRequest($ns.'orderstate', 'filter_orderstate', '', '')
        )
        {
            $state['limitstart'] = '0';
        }

        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.created_date', 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'DESC', 'word');

        $state['filter_orderstate'] = $app->getUserStateFromRequest($ns.'orderstate', 'filter_orderstate', '', 'string');

      //  $state['filter_userid']     = JFactory::getUser()->id;
      //  $filter_userid = $app->getUserStateFromRequest($ns.'userid', 'filter_userid', JFactory::getUser()->id, 'int');

        $state['filter_total']      = $app->getUserStateFromRequest($ns.'total', 'filter_total', '', 'float');

        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );
        }
        return $state;

	}

	function orderstatesave() {

		$id = JRequest::getVar('id', 0, 'post', 'int');
		$order_state_id = JRequest::getVar('order_state_id', 0, 'post', 'int');

		// $status_values = array(1 => JText::_('K2STORE_Confirmed'), 3 => JText::_('K2STORE_Failed'), 4 => JText::_('K2STORE_Pending'));
		if($order_state_id == 4) {
			$order_state = JText::_('K2STORE_PENDING');
		} elseif ($order_state_id == 3) {
			$order_state = JText::_('K2STORE_FAILED');
		} elseif ($order_state_id == 1) {
			$order_state = JText::_('K2STORE_CONFIRMED');
		}

		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables' );
        $order = JTable::getInstance('Orders', 'Table');
        $order->load($id);
        //lets change the status
        $order->order_state = $order_state;
        $order->order_state_id = $order_state_id;

        if ($order->store()) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'helpers'.DS.'orders.php');
			K2StoreOrdersHelper::sendUserEmail($order->user_id, $order->order_id, $order->transaction_status, $order->order_state, $order->order_state_id);
			$msg = JText::_('K2STORE_ORDER_STATUS_UPDATED_SUCCESSFULLY');
		} else {
			$msg = JText::_('K2STORE_ORDER_ERROR_UPDATING_STATUS');
		}
		$link = 'index.php?option=com_k2store&view=orders&task=view&id='.$order->id;

		$this->setRedirect($link, $msg);
	}


}
