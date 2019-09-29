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
JLoader::register( 'K2StoreTable', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables'.DS.'_base.php' );

class TableMyCart extends K2StoreTable
{

	/**
	* @param database A database connector object
	*/
	  function TableMyCart ( &$db )
		{
        $keynames = array();
        $keynames['user_id']    = 'user_id';
        $keynames['session_id'] = 'session_id';
        $keynames['product_id'] = 'product_id';
        $keynames['product_attributes'] = 'product_attributes';

        $this->setKeyNames( $keynames );

		parent::__construct('#__k2store_mycart', 'cart_id', $db );
	}

	  function check()
    {
        if (empty($this->user_id) && empty($this->session_id))
        {
            $this->setError( JText::_( "User or Session Required" ) );
            return false;
        }
        if (empty($this->product_id))
        {
            $this->setError( JText::_( "Product Required" ) );
            return false;
        }


        // be sure that product_attributes is sorted numerically
        if ($product_attributes = explode( ',', $this->product_attributes ))
        {
            sort($product_attributes);
            $this->product_attributes = implode(',', $product_attributes);
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


		$this->setError('');

		return true;
	}


	 function delete( $oid='' )
    {
        if (empty($oid))
        {
            // if empty, use the values of the current keys
            $keynames = $this->getKeyNames();
            foreach ($keynames as $key=>$value)
            {
                $oid[$key] = $this->$key;
            }
            if (empty($oid))
            {
                // if still empty, fail
                $this->setError( JText::_( "Cannot delete with empty key" ) );
                return false;
            }
        }

        if (!is_array($oid))
        {
            $keyName = $this->getKeyName();
            $arr = array();
            $arr[$keyName] = $oid;
            $oid = $arr;
        }

        $db = $this->getDbo();

        // initialize the query
        $query = new K2StoreQuery();
        $query->delete();
        $query->from( $this->getTableName() );

        foreach ($oid as $key=>$value)
        {
            // Check that $key is field in table
            if ( !in_array( $key, array_keys( $this->getProperties() ) ) )
            {
                $this->setError( get_class( $this ).' does not have the field '.$key );
                return false;
            }
            // add the key=>value pair to the query
            $value = $db->Quote( $db->escape( trim( strtolower( $value ) ) ) );
            $query->where( $key.' = '.$value);
        }

        $db->setQuery( (string) $query );

        if ($db->query())
        {
            return true;
        }
        else
        {
            $this->setError($db->getErrorMsg());
            return false;
        }
    }


}
