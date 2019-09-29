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

class K2StoreControllerOptions extends K2StoreController {

	function __construct($config = array())
	{
		parent::__construct($config);
	//	print_r(JRequest::get('post')); exit;
		// Register Extra tasks
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
	}

    function display($cachable = false, $urlparams = array()) {

        switch($this->getTask())
		{
			case 'add'     :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'edit'  );
				JRequest::setVar( 'view'  , 'option');
				JRequest::setVar( 'edit', false );

			} break;
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'edit'  );
				JRequest::setVar( 'view'  , 'option');
				JRequest::setVar( 'edit', true );

			} break;
		}
	    parent::display($cachable = false, $urlparams = array());
    }

    function save() {

		$post	= JRequest::get('post');
		JRequest::checkToken() or jexit('Invalid Token');
		$model = $this->getModel('option');
		if ($model->store($post)) {
			$msg = JText::_( 'K2STORE_OPTION_SAVED' );
			$link = 'index.php?option=com_k2store&view=options';
		} else {
			$msg = $model->getError();
			if($post['option_id']) {
				$link = 'index.php?option=com_k2store&view=options&task=edit&cid[]='.$post['option_id'];
			} else {
				$link = 'index.php?option=com_k2store&view=options&task=add';
			}
		}

		$this->setRedirect($link, $msg);
	}


	function saveorder() {

		$db = JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$total = count($cid);
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		JArrayHelper::toInteger($order, array(0));
		$model =  $this->getModel('option');
		$row = $model->getTable();
		$groupings = array();
		for ($i = 0; $i < $total; $i++) {
			$row->load((int) $cid[$i]);
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
		}
		$cache = JFactory::getCache('com_k2store');
		$cache->clean();
		$msg = JText::_('K2STORE_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_k2store&view=options', $msg);
	}


	function orderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = JRequest::getVar('cid');
		$model = $this->getModel('option');
		$row = $model->getTable();
		$row->load($cid[0]);
		$row->move(-1);
		$row->reorder();
		$cache = JFactory::getCache('com_k2store');
		$cache->clean();
		$msg = JText::_('K2STORE_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_k2store&view=options', $msg);
	}

	function orderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$cid = JRequest::getVar('cid');
		$model = $this->getModel('option');
		$row = $model->getTable();
		$row->load($cid[0]);
		$row->move(1);
		$row->reorder();
		$cache = JFactory::getCache('com_k2store');
		$cache->clean();
		$msg = JText::_('K2STORE_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_k2store&view=options', $msg);
	}

    function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'K2STORE_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel('option');
		if(!$model->delete($cid)) {
			$msg = $model->getError();
		} else {
			$msg = JText::_('K2STORE_DELETED_ITEMS');
		}

		$this->setRedirect( 'index.php?option=com_k2store&view=options', $msg);
	}


	function publish()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'K2STORE_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel('option');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_k2store&view=options' );
	}


	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'K2STORE_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel('option');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_k2store&view=options' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Checkin the weblink


		$this->setRedirect( 'index.php?option=com_k2store&view=options' );
	}

	/*
	 *
	 * option values */

	function setoptionvalues(){

		$app = JFactory::getApplication();
		$ns = 'com_k2store.optionvalues';
		$model = $this->getModel('optionvalues');
		$filter_order		= $app->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'asc',				'word' );
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$option_id = $app->input->get('option_id', 0);

		//check if option id is present.
		if(!$option_id) {
			$errors[] = JText::_('K2STORE_OPTIONVALUES_OPTION_ID_MISSING');

		}

		//option id present. But only if option is select, checkbox or radio, we need to get values.
		//Check it
		//options modal.
		$option_model = $this->getModel('option');
		$model->setId($option_id);
		$table = $option_model->getTable();
		$table->load($model->getId());

		$type_array = array('select', 'radio', 'checkbox');
		if(!in_array($table->type, $type_array)) {
			$errors[] = JText::_('K2STORE_OPTIONVALUES_OPTION_TYPE_INVALID');
		}

		$model->setState('filter_option_id', $model->getId());
		$items =$model->getList();

		$total		= $model->getTotal();
		$pagination = $model->getPagination();

		$view   = $this->getView( 'optionvalues', 'html' );
		$view->set( '_controller', 'options' );
		$view->set( '_view', 'options' );
		$view->set( '_action', "index.php?option=com_k2store&view=options&task=setoptionvalues&id=".$model->getId()."&tmpl=component" );
		$view->setModel( $model, true );
		$view->assign( 'row', $table );
		$view->assign( 'items', $items );
		$view->assign( 'total', $total );
		$view->assign( 'pagination', $pagination );
		$view->assign( 'lists', $lists );
		$view->assign( 'option_id', $option_id);
		$view->setLayout( 'default' );
		$view->display();
	}

	/**
	 * Saves the properties for all prices in list
	 *
	 * @return unknown_type
	 */
	function saveoptionvalues()
	{
		$error = false;
		$this->messagetype  = '';
		$this->message      = '';
		$app = JFactory::getApplication();

		$model = $this->getModel('optionvalues');
		$row = $model->getTable();
		$option_id = $app->input->getInt('id', 0);
		$cids = $app->input->get('cid', array(0), 'ARRAY');
		$names = $app->input->get('name', array(0), 'ARRAY');
		$orderings = $app->input->get('ordering', array(0), 'ARRAY');
		foreach (@$cids as $cid)
		{
			$row->load( $cid );

			$row->optionvalue_name = $names[$cid];
			$row->ordering = $orderings[$cid];

			if (!$row->store())
			{
				$this->message .= $row->getError();
				$this->messageType = 'notice';
				$error = true;
			}
		}

		if ($error)
		{
			$this->message = JText::_('Error') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_ALL_CHANGES_SAVED');
		}

		$redirect = "index.php?option=com_k2store&view=options&task=setoptionvalues&id={".$option_id ."}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messageType );
	}

	/**
	 * Creates a rate and redirects
	 *
	 * @return unknown_type
	 */
	function createoptionvalue()
	{
		$app = JFactory::getApplication();
		$model  = $this->getModel('optionvalues');
		$row = $model->getTable();
		$option_id = $app->input->get( 'id' );
		$optionvalue_name = $app->input->getString( 'optionvalue_name' );
		if(!$option_id || !empty($optionvalue_name)) {

			$row->option_id = $app->input->get( 'id' );
			$row->optionvalue_name = $app->input->getString( 'optionvalue_name' );
			$row->ordering = 99;
			if ( !$row->store() )
			{
				$this->messageType = 'notice';
				$this->message = JText::_( 'K2STORE_SAVE_FAILED' )." - ".$row->getError();
			}

			$row->reorder();

		} else {
			$this->messageType = 'notice';
			if(empty($optionvalue_name)) {
				$this->message = JText::_('K2STORE_OPTIONVALUES_OPTION_NAME_MISSING');
			} else {
				$this->message = JText::_('K2STORE_OPTIONVALUES_OPTION_ID_MISSING');
			}
		}

		$redirect = "index.php?option=com_k2store&view=options&task=setoptionvalues&id={$option_id}&tmpl=component";
		$redirect = JRoute::_( $redirect, false );

		$this->setRedirect( $redirect, $this->message, $this->messageType );
	}

	function deleteoptionvalue(){

		$error = false;
		$app = JFactory::getApplication();
		$this->messagetype	= '';
		$this->message 		= '';
		$option_id = $app->input->getInt( 'option_id' );
		if(empty($option_id)) {

			$this->message .= JText::_('K2STORE_OPTIONVALUES_OPTION_NAME_MISSING');
			$this->messagetype = 'notice';
			$error = true;
		}

		if (!isset($this->redirect)) {
			$this->redirect = $app->input->getString( 'return' )
			? base64_decode( $app->input->getString( 'return' ) )
			: 'index.php?option=com_k2store&view=options&task=setoptionvalues&id='.$option_id.'&tmpl=component';
			$this->redirect = JRoute::_( $this->redirect, false );
		}

		$model = $this->getModel('optionvalues');
		$row = $model->getTable();

		$cids = $app->input->get('cid', array (0), 'ARRAY');
		foreach ($cids as $cid)
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
			$this->message = JText::_('Error') . " - " . $this->message;
		}
		else
		{
			$this->message = JText::_('K2STORE_ITEMS_DELETED');
		}

		$this->setRedirect( $this->redirect, $this->message, $this->messagetype );


	}

	public static function getOptions() {
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$q = $app->input->post->get('q');
		$query = $db->getQuery(true);
		$query->select('option_id, option_unique_name, option_name');
		$query->from('#__k2store_options');
		$query->where('LOWER(option_unique_name) LIKE '.$db->Quote( '%'.$db->escape( $q, true ).'%', false ));
		$query->where('state=1');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		echo json_encode($result);
		$app->close();
	}

}