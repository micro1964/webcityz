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

class K2StoreControllerProducts extends K2StoreController {

	function __construct()
	{
		parent::__construct();

	}


	function createattribute() {
		$app = JFactory::getApplication();
		$model  = $this->getModel( 'productattributes' );
		$row = $model->getTable();
		$row->product_id = $app->input->getInt( 'id' );
		$row->productattribute_name = $app->input->getString( 'productattribute_name' );
		$row->productattribute_display_type = $app->input->getString( 'productattribute_display_type', 'select' );
		$row->productattribute_required = $app->input->getInt( 'productattribute_required', 0 );
		$row->ordering = '99';
		//  $post=JRequest::get('post');

		if ( !$row->save() )
		{
			$messagetype = 'notice';
			$message = JText::_( 'K2STORE_SAVE_FAILED' )." - ".$row->getError();
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setattributes&id={$row->product_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $message, $messagetype );

	}


	function setattributes()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('productattributes');
		$ns = 'com_k2store.productattributes';

		$filter_order		= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$id = $app->input->getInt('id', 0);

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
		$row = JTable::getInstance('K2Item', 'Table');
		$row->load($id);

		$items = $model->getData();
		$total		= $model->getTotal();
		$pagination = $model->getPagination();

		$view   = $this->getView( 'productattributes', 'html' );
		$view->set( '_controller', 'products' );
		$view->set( '_view', 'products' );
		$view->set( '_action', "index.php?option=com_k2store&view=products&task=setattributes&tmpl=component&id=".$id);
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->assign( 'items', $items );
		$view->assign( 'total', $total );
		$view->assign( 'lists', $lists );
		$view->assign( 'pagination', $pagination );
		$view->assign( 'product_id', $id );
		$view->setLayout( 'default' );
		$view->display();
	}


	function saveattributes()
	{
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';
		$app = JFactory::getApplication();
		$model = $this->getModel('productattributes');
		$row = $model->getTable();

		$id = $app->input->getInt('id', 0);
		$cids = $app->input->post->get('cid', array(0), 'ARRAY');
		$name = $app->input->post->get('name', array(0), 'ARRAY');
		$display_type = $app->input->post->get('display_type', array(0), 'ARRAY');
		$pa_required = $app->input->post->get('pa_required', array(0), 'ARRAY');
		$ordering = $app->input->post->get('ordering', array(0), 'ARRAY');

		foreach (@$cids as $cid)
		{
			$row->load( $cid );
			$row->productattribute_name = $name[$cid];
			$row->productattribute_display_type = $display_type[$cid];
			$row->productattribute_required = $pa_required[$cid];
			$row->ordering = $ordering[$cid];

			if (!$row->check() || !$row->store())
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		$row->reorder();

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setattributes&id={$id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}


	function deleteattributes()
	{
		$app = JFactory::getApplication();
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
		$product_id = $app->input->getInt('product_id');
		if (!isset($this->redirect)) {
			$this->redirect = $app->input->getString('return' )
			? base64_decode( $app->input->getString( 'return' ) )
			: 'index.php?option=com_k2store&view=products&task=setattributes&id='.$product_id.'&tmpl=component';
			$this->redirect = JRoute::_( $this->redirect, false );
		}

		$model = $this->getModel('productattributes');
		$row = $model->getTable();

		$cids = $app->input->get('cid', array (0),'ARRAY');
		foreach (@$cids as $cid)
		{
			if (!$row->delete($cid))
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_ITEMS_DELETED');
		}

		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );
	}

	function setattributeoptions()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('productattributeoptions');
		$ns = 'com_k2store.productattributeoptions';
		$filter_order		= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$id = $app->input->getInt('id', 0);

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');
		$row = JTable::getInstance('ProductAttributes', 'Table');
		$row->load($model->getId());

		$items = $model->getData();
		$total		= $model->getTotal();
		$pagination = $model->getPagination();

		$view   = $this->getView( 'productattributeoptions', 'html' );
		$view->set( '_controller', 'products' );
		$view->set( '_view', 'products' );
		$view->set( '_action', "index.php?option=com_k2store&view=products&task=setattributeoptions&tmpl=component&id=".$model->getId());
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->assign( 'items', $items );
		$view->assign( 'total', $total );
		$view->assign( 'lists', $lists );
		$view->assign( 'pagination', $pagination );
		$view->setLayout( 'default' );
		$view->display();
	}


	function createattributeoption()
	{
		$app = JFactory::getApplication();
		$model  = $this->getModel( 'productattributeoptions' );
		$row = $model->getTable();
		$row->productattribute_id = $app->input->getInt('id', 0);
		$row->productattributeoption_name = $app->input->getString('productattributeoption_name' );
		$row->productattributeoption_price = $app->input->getInt( 'productattributeoption_price' );
		$row->productattributeoption_code = $app->input->getString( 'productattributeoption_code' );
		$row->productattributeoption_prefix = $app->input->getString( 'productattributeoption_prefix' );
		$row->ordering = '99';

		if (!$row->save() )
		{
			$this->messagetype  = 'notice';
			$this->message      = JText::_( 'K2STORE_SAVE_FAILED' )." - ".$row->getError();
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setattributeoptions&id={$row->productattribute_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}


	function saveattributeoptions()
	{
		$app = JFactory::getApplication();
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';
		$model = $this->getModel('productattributeoptions');
		$row = $model->getTable();

		$productattribute_id = $app->input->get('id', 0);
		$cids = $app->input->post->get('cid', array(0), 'ARRAY');
		$name = $app->input->post->get('name', array(0), 'ARRAY');
		$prefix = $app->input->post->get('prefix', array(0), 'ARRAY');
		$price = $app->input->post->get('price', array(0), 'ARRAY');
		$code = $app->input->post->get('code', array(0), 'ARRAY');
		$ordering = $app->input->post->get('ordering', array(0), 'ARRAY');

		foreach (@$cids as $cid)
		{
			$row->load( $cid );
			$row->productattributeoption_name = $name[$cid];
			$row->productattributeoption_prefix = $prefix[$cid];
			$row->productattributeoption_price = $price[$cid];
			$row->productattributeoption_code = $code[$cid];
			$row->ordering = $ordering[$cid];

			if (!$row->check() || !$row->store())
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		$row->reorder();

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setattributeoptions&id={$productattribute_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}

	function deleteattributeoptions()
	{
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
		$app = JFactory::getApplication();
		$productattribute_id = $app->input->get( 'pa_id' );
		if (!isset($this->redirect)) {
			$this->redirect = $app->input->getString( 'return' )
			? base64_decode( $app->input->getString( 'return' ) )
			: 'index.php?option=com_k2store&view=products&task=setattributeoptions&id='.$productattribute_id.'&tmpl=component';
			$this->redirect = JRoute::_( $this->redirect, false );
		}

		$model = $this->getModel('productattributeoptions');
		$row = $model->getTable();

		$cids = $app->input->get('cid', array (0), 'ARRAY');
		foreach (@$cids as $cid)
		{
			if (!$row->delete($cid))
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_ITEMS_DELETED');
		}

		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );
	}

//product attributie option extra
	function setpaoextra()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('ProductAttributeOptions');

		$id = $app->input->getInt('id', 0);

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');
		$row = JTable::getInstance('ProductAttributeOptions', 'Table');
		$row->load($model->getId());

		$view   = $this->getView( 'paoextra', 'html' );
		$view->set( '_controller', 'products' );
		$view->set( '_view', 'products' );
		$view->set( '_action', "index.php?option=com_k2store&view=products&task=setpaoextra&tmpl=component&id=".$model->getId());
		$view->setModel( $model, true );
		$view->assign( 'row', $row );
		$view->setLayout( 'default' );
		$view->display();
	}


	function savepaoextra()
	{
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';
		$app = JFactory::getApplication();
		$model = $this->getModel('productattributeoptions');
		$row = $model->getTable();

		$productattributeoption_id = $app->input->get('id', 0);
			$row->load( $productattributeoption_id);
			$row->productattributeoption_short_desc = $app->input->getHtml('productattributeoption_short_desc', '');
			$row->productattributeoption_long_desc = $app->input->getHtml('productattributeoption_long_desc', '');
			$row->productattributeoption_ref = $app->input->getHtml('productattributeoption_ref', '');

			if (!$row->check() || !$row->store())
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setpaoextra&id={$productattributeoption_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}

	//product attributie option extra
	function setpaimport()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('paimport');
		$ns = 'com_k2store.paimport';
		$filter_order		= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'p.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$product_id = JRequest::getVar('id', 0, 'get', 'int');

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
		$row = JTable::getInstance('K2Item', 'Table');
		$row->load($model->getId());

		$items = $model->getData();
		$total		= $model->getTotal();
		$pagination = $model->getPagination();

		$view   = $this->getView( 'paimport', 'html' );
		$view->set( '_controller', 'products' );
		$view->set( '_view', 'products' );
		$view->set( '_action', "index.php?option=com_k2store&view=products&task=setpaimport&tmpl=component&id=".$model->getId());
		$view->setModel( $model, true );
		$view->assign('model', $model);
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row);
		$view->assign( 'items', $items );
		$view->assign( 'total', $total );
		$view->assign( 'lists', $lists );
		$view->assign( 'pagination', $pagination );
		$view->assign('product_id', $product_id);
		$view->setLayout( 'default' );
		$view->display();
	}

	function importattributes() {
		$app = JFactory::getApplication();
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';
		$cids = JRequest::getVar('cid', array(), 'request', 'array');
		$product_id = JRequest::getVar('id', 0, 'post', 'int');
		if(empty($cids) || count($cids) < 1) {
			$error = true;
			$this->message .= JText::_('K2STORE_PAI_SELECT_PRODUCT_TO_IMPORT');
			$this->messagetype = 'notice';
		} else {

			//get the model
			$model = $this->getModel('paimport');
			foreach($cids as $cid) {
				if(!$model->importAttributeFromProduct($cid, $product_id)){
					$this->message .= $model->getError();
					$this->messagetype = 'error';
				}
			}

		}
		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_PAI_SELECT_ATTRIBUTES_IMPORTED');
			$this->messageType = 'message';
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setpaimport&id={$product_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );

	}

	/*
	 * PA options section
	 */

	function createproductoptionvalue() {
		$app = JFactory::getApplication();
		$model  = $this->getModel( 'productoptionvalues' );
		$row = $model->getTable();
		$row->product_option_id = $app->input->getInt( 'product_option_id' );
		$row->option_id = $app->input->getInt( 'option_id' );
		$row->product_id = $app->input->getInt( 'product_id' );
		$row->optionvalue_id = $app->input->getInt( 'productoptionvalue_id' );
		$row->product_optionvalue_price = $app->input->get( 'product_optionvalue_price');
		$row->product_optionvalue_prefix = JFactory::getDbo()->escape($app->input->getString( 'product_optionvalue_prefix'));

		$row->ordering = '99';
		//  $post=JRequest::get('post');

		if ( !$row->save() )
		{
			$messagetype = 'notice';
			$message = JText::_( 'K2STORE_SAVE_FAILED' )." - ".$row->getError();
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setproductoptionvalues&product_option_id={$row->product_option_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $message, $messagetype );

	}


	function setproductoptionvalues()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('productoptionvalues');
		$ns = 'com_k2store.productoptionvalues';

		$filter_order		= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$product_option_id = $app->input->getInt('product_option_id', 0);

		//load the product options table joining general options tables
		$product_options = $model->getProductOptions();

		//load the general option values
		$lists['option_values'] = null;
		$option_values = array();
		$option_values = $model->getOptionValues($product_options->option_id);
		if(count($option_values)) {
			foreach($option_values as $option_value) {
				$options[] = JHtml::_('select.option', $option_value->optionvalue_id, $option_value->optionvalue_name);
			}
			$attribs = array('class' => 'inputbox', 'size'=>'1', 'title'=>JText::_('K2STORE_SELECT_AN_OPTION'));
			$lists['option_values'] = JHtml::_('select.genericlist', $options, 'productoptionvalue_id', $attribs, 'value', 'text', '', 'productoptionvalue_id');
		}

		$items = $model->getData();
		$total		= $model->getTotal();
		$pagination = $model->getPagination();

		$view   = $this->getView( 'productoptionvalues', 'html' );
		$view->set( '_controller', 'products' );
		$view->set( '_view', 'products' );
		$view->set( '_action', "index.php?option=com_k2store&view=products&task=setproductoptionvalues&tmpl=component&product_option_id=".$product_option_id);
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $product_options );
		$view->assign( 'items', $items );
		$view->assign( 'total', $total );
		$view->assign( 'lists', $lists );
		$view->assign( 'pagination', $pagination );
		$view->assign( 'product_option_id', $product_option_id);
		$view->setLayout( 'default' );
		$view->display();
	}


	function saveproductoptionvalues()
	{
		$error = false;
		$this->messageType  = '';
		$this->message      = '';
		$app = JFactory::getApplication();
		$model = $this->getModel('productoptionvalues');
		$row = $model->getTable();

		$product_option_id = $app->input->getInt('product_option_id', 0);
		$product_id = $app->input->getInt('product_id', 0);
		$option_id = $app->input->getInt('option_id', 0);
		$redirect = "index.php?option=com_k2store&view=products&task=setproductoptionvalues&product_option_id={$product_option_id}&tmpl=component";
		if(!$product_option_id || !$product_id || !$option_id ) {
			$this->messageType  = 'notice';
			$this->message      = JText::_('K2STORE_OPTIONVALUES_MISSING_VALUES');
			$app->redirect($redirect);
		}

		$cids = $app->input->post->get('cid', array(0), 'ARRAY');
		$optionvalue_ids = $app->input->post->get('optionvalue_id', array(0), 'ARRAY');
		$prefix = $app->input->post->get('prefix', array(0), 'ARRAY');
		$price = $app->input->post->get('price', array(0), 'ARRAY');
		$ordering = $app->input->post->get('ordering', array(0), 'ARRAY');

		foreach (@$cids as $cid)
		{
			$row->load( $cid );
			$row->optionvalue_id = $optionvalue_ids[$cid];
			$row->product_optionvalue_prefix = $prefix[$cid];
			$row->product_optionvalue_price = $price[$cid];
			$row->ordering = $ordering[$cid];

			if (!$row->check() || !$row->store())
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		$row->reorder();

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = "";
		}

		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}


	function deleteproductoptionvalues()
	{
		$app = JFactory::getApplication();
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
		$product_option_id = $app->input->getInt('po_id');
		if (!isset($this->redirect)) {
			$this->redirect = $app->input->getString('return' )
			? base64_decode( $app->input->getString( 'return' ) )
			: 'index.php?option=com_k2store&view=products&task=setproductoptionvalues&id='.$product_option_id.'&tmpl=component';
			$this->redirect = JRoute::_( $this->redirect, false );
		}

		$model = $this->getModel('productoptionvalues');
		$row = $model->getTable();

		$cids = $app->input->get('cid', array (0),'ARRAY');
		foreach (@$cids as $cid)
		{
			if (!$row->delete($cid))
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_ITEMS_DELETED');
		}

		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );
	}

	public static function removeProductOption() {

		$app = JFactory::getApplication();
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_k2store/tables');
		$result = array();
		$id = $app->input->post->get('pao_id');
		$table =  JTable::getInstance('ProductOptions', 'Table');
		if($table->delete($id)){
			$result['success'] = 1;
		} else {
			$result['success'] = 0;
		}

		echo json_encode($result);
		$app->close();
	}


	/*
	 *
	* Price range */

	/*-------------------------------------------------------------------------------*/

	function createpricerange() {
		$app = JFactory::getApplication();
		$model  = $this->getModel( 'productprices' );
		$row = $model->getTable();
		$row->product_id = $app->input->getInt( 'id' );
		$row->quantity_start = $app->input->getString( 'pricerange_quantity_start' );
		$row->condition = $app->input->getString( 'pricerange_condition', 'above' );
		$row->price = $app->input->get( 'pricerange_price', 0 );
		$row->ordering = '99';

		//$post=JRequest::get('post');

		if ( !$row->save() )
		{
			$messagetype = 'notice';
			$message = JText::_( 'K2STORE_SAVE_FAILED' )." - ".$row->getError();
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setpricerange&id={$row->product_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $message, $messagetype );

	}


	function setpricerange()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('productprices');
		$ns = 'com_k2store.productprices';

		$filter_order		= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$id = $app->input->getInt('id', 0);
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_k2/tables');
		$row = JTable::getInstance('K2Item','Table');
		$row->load($id);

		$items = $model->getData();
		$total		=  $model->getTotal();
		$pagination =  $model->getPagination();

		$view   = $this->getView( 'productprices', 'html' );
		$view->set( '_controller', 'products' );
		$view->set( '_view', 'products' );
		$view->set( '_action', "index.php?option=com_k2store&view=products&task=setpricerange&tmpl=component&id=".$id);
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->assign( 'items', $items );
		$view->assign( 'total', $total );
		$view->assign( 'lists', $lists );
		$view->assign( 'pagination', $pagination );
		$view->assign( 'product_id', $id );
		$view->setLayout( 'default' );
		$view->display();
	}


	function savepricerange()
	{
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';
		$app = JFactory::getApplication();
		$model = $this->getModel('productprices');
		$row = $model->getTable();

		$id = $app->input->getInt('id', 0);
		$cids = $app->input->post->get('cid', array(0), 'ARRAY');
		$quantity_start = $app->input->post->get('quantity_start', array(0), 'ARRAY');
		$condition = $app->input->post->get('condition', array(0), 'ARRAY');
		$price = $app->input->post->get('price', array(0), 'ARRAY');
		$ordering = $app->input->post->get('ordering', array(0), 'ARRAY');

		foreach (@$cids as $cid)
		{
			$row->load( $cid );
			$row->quantity_start = $quantity_start[$cid];
			$row->condition = $condition[$cid];
			$row->price = $price[$cid];
			$row->ordering = $ordering[$cid];

			if (!$row->check() || !$row->store())
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		$row->reorder();

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_k2store&view=products&task=setpricerange&id={$id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}


	function deletepricerange()
	{
		$app = JFactory::getApplication();
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
		$product_id = $app->input->getInt('product_id');
		if (!isset($this->redirect)) {
			$this->redirect = JRequest::getVar( 'return' )
			? base64_decode( JRequest::getVar( 'return' ) )
			: 'index.php?option=com_k2store&view=products&task=setpricerange&id='.$product_id.'&tmpl=component';
			$this->redirect = JRoute::_( $this->redirect, false );
		}

		$model = $this->getModel('productprices');
		$row = $model->getTable();

		$cids = JRequest::getVar('cid', array (0), 'request', 'array');
		foreach (@$cids as $cid)
		{
			if (!$row->delete($cid))
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}

		if ($error)
		{
			$this->message = JText::_('K2STORE_ERROR') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_ITEMS_DELETED');
		}

		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );
	}


}
