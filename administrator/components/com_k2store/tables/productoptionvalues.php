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

class TableProductOptionValues extends JTable
{
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct('#__k2store_product_optionvalues', 'product_optionvalue_id', $db );
	}
	
	
	function check()
	{
		if (empty($this->product_id))
		{
			$this->setError( JText::_( "K2STORE_PRODUCT_ASSOCIATION_REQUIRED" ) );
			return false;
		}
        if (empty($this->product_option_id))
        {
            $this->setError( JText::_( "K2STORE_PRODUCT_OPTION_ASSOCIATION_REQUIRED" ) );
            return false;
        }
		return true;
	}
	
	
	function save()
	{
	    $this->_isNew = false;
	    $key = $this->getKeyName();
	    if (empty($this->$key))
        {
            $this->_isNew = true;
        }
        
		if ( !$this->check() )
		{
			return false;
		}
		
		if ( !$this->store() )
		{
			return false;
		}
		
		if ( !$this->checkin() )
		{
			$this->setError( $this->_db->stderr() );
			return false;
		}
		
		$this->reorder();
		
		
		$this->setError('');
		
		// TODO Move ALL onAfterSave plugin events here as opposed to in the controllers, duh
        //$dispatcher = JDispatcher::getInstance();
        //$dispatcher->trigger( 'onAfterSave'.$this->get('_suffix'), array( $this ) );
		return true;
	}
	
	
	 function reorder()
    {
        parent::reorder('product_option_id = '.$this->_db->Quote($this->product_option_id) );
    }
    
     
}