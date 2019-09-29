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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 *
 * @static
 * @package		Joomla
 * @subpackage	K2Store
 * @since 1.0
 */
class K2StoreViewAddress extends K2StoreView
{

function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';

		if($this->getLayout() == 'form') {
				$model	=& $this->getModel('');
				//get the address
				$address	=& $this->get('Data');
				$this->assignRef('address',		$address);

				$this->addToolBar();
				$toolbar = new K2StoreToolBar();
        		$toolbar->renderLinkbar();

		}

		parent::display($tpl);
	}


	function addToolBar() {

		JToolBarHelper::title(JText::_('K2STORE_EDIT_ADDRESS'),'k2store-logo');

		// Set toolbar items for the page
		$edit		= JRequest::getVar('edit',true);
		$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Address' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolBarHelper::save();
	}

}
