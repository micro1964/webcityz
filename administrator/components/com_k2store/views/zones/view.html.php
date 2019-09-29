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

// import Joomla view library
jimport('joomla.application.component.view');

class K2StoreViewZones extends K2StoreView
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
                $this->countries = $this->get('Countries');

               //generate country filter list
                $lists = array();
                $country_options = array();
                $country_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_COUNTRY'));
                foreach($this->countries as $item) {
					$country_options[] =  JHTML::_('select.option', $item->country_id, $item->country_name);
				}

				$lists['country_options'] = JHTML::_('select.genericlist', $country_options, 'filter_country_options', 'onchange="this.form.submit();"', 'value', 'text', $this->state->get('filter.country_options'));
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
			    JToolBarHelper::title(JText::_('K2STORE_ZONES'),'k2store-logo');
				$state	= $this->get('State');
				JToolBarHelper::divider();
				 // check permissions for the users
			 	JToolBarHelper::addNew('zone.add','JTOOLBAR_NEW');
			 	JToolBarHelper::divider();
			 	JToolBarHelper::editList('zone.edit','JTOOLBAR_EDIT');
			 	JToolBarHelper::divider();
				JToolBarHelper::custom('zones.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::divider();
				JToolBarHelper::custom('zones.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			 	JToolBarHelper::divider();
				if($state == '-2' ) {
					JToolBarHelper::deleteList('', 'zones.delete','JTOOLBAR_EMPTY_TRASH');
			 	} else {
					JToolBarHelper::trash('zones.trash', 'JTOOLBAR_TRASH');
				}
		 }

		 protected function setDocument() {
			 // get the document instance
			  $document = JFactory::getDocument();
			// setting the title of the document
              $document->setTitle(JText::_('K2STORE_ZONES'));


		 }
}
