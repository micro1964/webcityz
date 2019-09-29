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

jimport('joomla.application.component.controller');

class K2StoreControllerMigrate extends K2StoreController {

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function migrate() {

		$model = $this->getModel('migrate');
		if(!$model->canMigrate() || K2STORE_ATTRIBUTES_MIGRATED==1) {
			$msg = JText::_('K2STORE_MIGRATE_CURRENT_VERSION');
			$app->redirect('index.php?option=com_k2store&view=cpanel', $msg);
		}

		if(!$model->migrate()) {
			$msg = $model->getError();
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect('index.php?option=com_k2store&view=cpanel', $msg);
	}

}