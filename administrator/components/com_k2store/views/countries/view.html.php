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

class K2StoreViewCountries extends K2StoreView
{
   	protected $items;
	protected $pagination;
	protected $state;
     function display($tpl = null)
        {

                // Get data from the model
                $this->items = $this->get('Items');
             	 // inturn calls getState in parent class and populateState() in model
               $this->state = $this->get('State');
				$this->pagination = $this->get('Pagination');

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
				$this->setDocument();
        }

         protected function addToolBar() {
			 // setting the title for the toolbar string as an argument
			    JToolBarHelper::title(JText::_('K2STORE_COUNTRIES'),'k2store-logo');

				JToolBarHelper::addNew('country.add','JTOOLBAR_NEW');
				JToolBarHelper::editList('country.edit','JTOOLBAR_EDIT');
				JToolBarHelper::custom('countries.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('countries.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
				$state	= $this->state->get('filter.state');
				if($state == '-2' ) {
					JToolBarHelper::deleteList('', 'countries.delete','JTOOLBAR_EMPTY_TRASH');
				} else {
					JToolBarHelper::trash('countries.trash', 'JTOOLBAR_TRASH');
				}

		 }

		 protected function setDocument() {
			 // get the document instance
			  $document = JFactory::getDocument();
			// setting the title of the document
              $document->setTitle(JText::_('K2STORE_COUNTRIES'));

		 }
}
