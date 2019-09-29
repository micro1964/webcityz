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

class K2StoreViewAddresses extends K2StoreView
{

	function display($tpl = null) {

		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.addresses';
		$db		=JFactory::getDBO();
		$uri	=JFactory::getURI();

		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		$search				= $mainframe->getUserStateFromRequest( $ns.'search',			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// Get data from the model
		$items		= $this->get( 'Data');
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

		$params = JComponentHelper::getParams('com_k2store');

		$this->addToolBar();
		$toolbar = new K2StoreToolBar();
        $toolbar->renderLinkbar();
		parent::display($tpl);
	}

	public function addToolBar() {
		JToolBarHelper::title(JText::_('K2STORE_SHOPPER_ADDRESSES'),'k2store-logo');
		//JToolBarHelper::addNew();
		//JToolBarHelper::editList();
		JToolBarHelper::deleteList();

	}

}