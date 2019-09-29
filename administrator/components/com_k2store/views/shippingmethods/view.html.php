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

jimport('joomla.application.component.view');

class K2StoreViewShippingmethods extends K2StoreView
{

	function display($tpl = null) {

		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = 'com_k2store.shippingmethods';
		$task = JRequest::getVar('task');

		if($task == 'add'|| $task == 'edit') {
			$this->showForm($tpl);
		} else {


		$db		=JFactory::getDBO();
		$uri	=JFactory::getURI();
		$params = JComponentHelper::getParams('com_k2store');

		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_orderstate	= $mainframe->getUserStateFromRequest( $ns.'filter_orderstate',	'filter_orderstate',	'', 'string' );

		$search				= $mainframe->getUserStateFromRequest( $ns.'search',			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// Get data from the model
		$items		= $this->get( 'Item');
		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		$javascript 	= 'onchange="document.adminForm.submit();"';

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		//adding toolbar
		JToolBarHelper::title(JText::_('K2STORE_SHIPPING_METHODS'),'k2store-logo');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		}
		//$this->addToolBar();
		$toolbar = new K2StoreToolBar();
        $toolbar->renderLinkbar();




		parent::display($tpl);
	}


	function showForm($tpl) {

		$item		= $this->get('Data');
		$this->assignRef('item',		$item);

		$isNew		= ($item->id < 1);

		if($isNew) {
			$item->published = 1;

		}

		$lists = array();
		//$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class=""', $item->published );
		$arr = array(JHTML::_('select.option', '0', JText::_('No') ),
				JHTML::_('select.option', '1', JText::_('Yes') )	);
		$lists['published'] = JHTML::_('select.genericlist', $arr, 'published', null, 'value', 'text', $item->published );


		$this->assignRef('shippingmethods',	$item);
		$this->assignRef('lists',	$lists);


		//adding toolbar
		JToolBarHelper::title(JText::_('K2STORE_SHIPPING_METHODS'),'k2store-logo');

		// Set toolbar items for the page
		$edit		= JRequest::getVar('edit',true);
		$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'K2STORE_SHIPPING_METHODS' ).': <small><small>[ ' . $text.' ]</small></small>','k2store-logo' );
		JToolBarHelper::save();
		if (!$edit)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

	}

}
