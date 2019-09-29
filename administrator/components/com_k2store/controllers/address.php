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

jimport('joomla.application.component.controller');

class K2StoreControllerAddress extends K2StoreController {

    function display() {
		
		switch($this->getTask())
		{			
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'address');
				JRequest::setVar( 'edit', true );
				$model = $this->getModel('address');
				
			} 
			break;
			
		}
	    parent::display();
    }
    
    
    function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$post	= JRequest::get('post');
		
		//print_r($post); exit;
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('address');

		if ($model->store($post)) {
			$msg = JText::_( 'Address Saved' );
		} else {
			$msg = JText::_( 'Error Saving address' );
		}

		$link = 'index.php?option=com_k2store&view=addresses';
		$this->setRedirect($link, $msg);
	}
    

}
?>
