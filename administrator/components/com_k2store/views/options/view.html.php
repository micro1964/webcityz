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

class K2StoreViewOptions extends K2StoreView
{

	function display($tpl = null) {

		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';

		$ns='com_k2store.options';

		$db		=JFactory::getDBO();
		$uri	=JFactory::getURI();
		$params = JComponentHelper::getParams('com_k2store');

		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.option_id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'ASC',				'word' );
		$filter_orderstate	= $mainframe->getUserStateFromRequest( $ns.'filter_orderstate',	'filter_orderstate',	'', 'string' );

		$search				= $mainframe->getUserStateFromRequest( $ns.'search',			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		// Get data from the model
		$items		=  $this->get( 'Data');
		$total		=  $this->get( 'Total');
		$pagination =  $this->get( 'Pagination' );

		$javascript 	= 'onchange="document.adminForm.submit();"';

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);

		$ordering = (($this->lists['order'] == 'a.ordering' ));
		$this->assignRef('ordering', $ordering);

		// Joomla! 3.0 drag-n-drop sorting variables
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			JHtml::_('bootstrap.tooltip');
			if ($ordering)
			{
				JHtml::_('sortablelist.sortable', 'k2ItemsList', 'adminForm', strtolower($this->lists['order_Dir']), 'index.php?option=com_k2store&view=options&task=saveorder&format=raw');
			}
			$document = JFactory::getDocument();
			$document->addScriptDeclaration('
					Joomla.orderTable = function() {
					table = document.getElementById("sortTable");
					direction = document.getElementById("directionTable");
					order = table.options[table.selectedIndex].value;
					if (order != \''.$this->lists['order'].'\') {
					dirn = \'asc\';
		} else {
					dirn = direction.options[direction.selectedIndex].value;
		}
					Joomla.tableOrdering(order, dirn, "");
		}');
		}



		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);

		$model = $this->getModel();

		$this->addToolBar();
		$toolbar = new K2StoreToolBar();
        $toolbar->renderLinkbar();

		parent::display($tpl);
	}

	function addToolBar() {
		JToolBarHelper::title(JText::_('K2STORE_PRODUCT_OPTION'),'k2store-logo');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();

	}

}
