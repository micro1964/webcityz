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

class K2StoreViewOption extends K2StoreView
{

	function display($tpl = null) {

		$db		=JFactory::getDBO();
		$uri	=JFactory::getURI();
		$model		= $this->getModel('option');
		$params = JComponentHelper::getParams('com_k2store');
		// get order data
		$data	= $this->get('Data');
		$isNew		= ($data->option_id < 1);

		if($isNew) {
			$data->state = 1;

		}

		$lists = array();
		$arr = array(JHTML::_('select.option', '0', JText::_('No') ),
					JHTML::_('select.option', '1', JText::_('Yes') )	);
		$lists['published'] = JHTML::_('select.genericlist', $arr, 'state', null, 'value', 'text', $data->state);

		$this->assignRef('data',	$data);
		$this->assignRef('lists',	$lists);
		$this->assignRef('params',	$params);

		$this->addToolBar();
		$toolbar = new K2StoreToolBar();
        $toolbar->renderLinkbar();

		parent::display($tpl);
	}

	function addToolBar() {

		JToolBarHelper::title(JText::_('K2STORE_EDIT_PRODUCT_OPTION'),'k2store-logo');

		// Set toolbar items for the page
		$edit		= JRequest::getVar('edit',true);
		$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'K2STORE_PRODUCT_OPTION' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if (!$edit)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

	}

}
