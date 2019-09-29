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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the K2Store component
 *
 * @static
 * @package		Joomla
 * @subpackage	K2Store
 * @since 1.0
 */
class K2StoreViewDownloads extends K2StoreView
{
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		if (empty(JFactory::getUser()->id))
		{
			$url = JRoute::_( "index.php?option=com_k2store&view=downloads" );
			$redirect = "index.php?option=com_users&view=login&return=".base64_encode( $url );
			$redirect = JRoute::_( $redirect, false );
			$msg = JText::_('K2STORE_LOGIN_TO_DOWNLOAD');
			$app->redirect( $redirect, $msg);
			return;
		}
			
		$params = JComponentHelper::getParams('com_k2store');
		$model  = $this->getModel('downloads');
		$ns = 'com_k2store.downloads';
		
		//get the files for this user
		$files = $model->getItems();
		
		$this->assignRef( 'model', $model );
		$this->assignRef( 'files', $files );
		$this->assignRef( 'params', $params );
		parent::display($tpl);
	}
}