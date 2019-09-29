<?php
/*------------------------------------------------------------------------
 # com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the K2Store Component
*/
class K2StoreViewTaxprofile extends K2StoreView
{
	protected $form;
	protected $item;
	protected $state;

	function display($tpl = null)
	{
		$baseURL = JURI::root();
		$document = JFactory::getDocument();

		$this->form	= $this->get('Form');
		// Get data from the model
		$this->item = $this->get('Item');
		$this->taxrules = $this->get('TaxRules');
		// inturn calls getState in parent class and populateState() in model
		$this->state = $this->get('State');


		$lists['taxrate']= $this->get('TaxRateList');
		$lists['address']= $this->get('AddressList');

		$this->lists = $lists;


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		//add toolbar
		$this->addToolBar();
		$toolbar = new K2StoreToolBar();
		$toolbar->renderLinkbar();
		// Display the template
		parent::display($tpl);
		// Set the document
		$this->setDocument();
	}

	protected function addToolBar() {
		// setting the title for the toolbar string as an argument
		JToolBarHelper::title(JText::_('K2STORE_TAXPROFILES'),'k2store-logo');
		JToolBarHelper::apply('taxprofile.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('taxprofile.save', 'JTOOLBAR_SAVE');

		if (empty($this->item->taxprofile_id))  {
			JToolBarHelper::cancel('taxprofile.cancel','JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('taxprofile.cancel', 'JTOOLBAR_CLOSE');
		}
	}

	protected function setDocument() {
		// get the document instance
		$document = JFactory::getDocument();
		// setting the title of the document
		$document->setTitle(JText::_('K2STORE_TAXPROFILE'));
	}
}
