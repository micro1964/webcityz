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

class K2StoreViewTaxrates extends K2StoreView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $countries;


	function display($tpl = null)
	{

		// Get data from the model
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		// inturn calls getState in parent class and populateState() in model
		$this->state = $this->get('State');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		//get list of countries
		$this->geozones = $this->get('GeoZones');

		//generate geozone filter list
		$lists = array();
		$geozone_options = array();
		$geozone_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_GEOZONE'));
		foreach($this->geozones as $item) {
			$geozone_options[] =  JHTML::_('select.option', $item->geozone_id, $item->geozone_name);
		}

		$lists['geozone_options'] = JHTML::_('select.genericlist', $geozone_options, 'filter_geozone_options', 'onchange="this.form.submit();"', 'value', 'text', $this->state->get('filter.geozone_options'));
		$this->assignRef('lists', $lists);
		//add toolbar
		$this->addToolBar();
		$toolbar = new K2StoreToolBar();
		$toolbar->renderLinkbar();
		// Display the template
		parent::display($tpl);
		$this->setDocument();
	}

	protected function addToolBar() {
		// setting the title for the toolbar string as an argument
		JToolBarHelper::title(JText::_('K2STORE_TAXRATES'),'k2store-logo');
		$state	= $this->get('State');
		JToolBarHelper::back();
		JToolBarHelper::divider();
		// check permissions for the users
		JToolBarHelper::addNew('taxrate.add','JTOOLBAR_NEW');
		JToolBarHelper::divider();
		JToolBarHelper::editList('taxrate.edit','JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		JToolBarHelper::custom('taxrates.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('taxrates.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		if($state == '-2' ) {
			JToolBarHelper::deleteList('', 'taxrates.delete','JTOOLBAR_EMPTY_TRASH');
		} else {
			JToolBarHelper::trash('taxrates.trash', 'JTOOLBAR_TRASH');
		}
	}

	protected function setDocument() {
		// get the document instance
		$document = JFactory::getDocument();
		// setting the title of the document
		$document->setTitle(JText::_('K2STORE_TAXRATES'));

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
				'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'a.state' => JText::_('JSTATUS'),
				'g.geozone_name' => JText::_('COM_PPINVOICE_GEOZONE_NAME'),
				'a.tax_percent' => JText::_('COM_PPINVOICE_TAXRATE_PERCENT'),
				'a.taxrate_name' => JText::_('COM_PPINVOICE_TAXRATE_NAME'),
				'a.taxrate_id' => JText::_('COM_PPINVOICE_TAXRATE_ID')
		);
	}

}
