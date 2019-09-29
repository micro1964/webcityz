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

class K2StoreViewMigrate extends K2StoreView
{

	function display($tpl = null) {

		$app = JFactory::getApplication();
		$option = 'com_k2store';

		$model = $this->getModel();

		//is it ok to migrate
		if(!$model->canMigrate() || K2StoreVersion::getPreviousVersion() != '3.0.3' || K2STORE_ATTRIBUTES_MIGRATED==1){
			$msg = JText::_('K2STORE_MIGRATE_CURRENT_VERSION');
			$app->redirect('index.php?option=com_k2store&view=cpanel', $msg);
		}

		$db		=JFactory::getDBO();
		$params = JComponentHelper::getParams('com_k2store');

		// Get data from the model
		$items		=  $this->get( 'Data');
		$total = count($items);
		$this->assignRef('items',		$items);
		$this->assignRef('total',		$total);

		$this->addToolBar();
		$toolbar = new K2StoreToolBar();
        $toolbar->renderLinkbar();

		parent::display($tpl);
	}

	function addToolBar() {
		JToolBarHelper::title(JText::_('K2STORE_MIGRATE'),'k2store-logo');
	}

}
