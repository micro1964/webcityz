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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class K2StoreModelCheckout extends K2StoreModel {

	 function getData($ordering = NULL) {

	 }


	 function checkBillingAddress() {
	 	$user =	JFactory::getUser();
	 	$db = JFactory::getDBO();
	 	$session = JFactory::getSession();
	 	$mail = $session->get('guest_mail');

	 	if(empty($mail) && $user->id)
	 		$query = "SELECT * FROM #__k2store_address WHERE user_id={$user->id} AND type='billing' ORDER BY id DESC LIMIT 1";
	 	elseif(!empty($mail))
	 		$query = "SELECT * FROM #__k2store_address WHERE email=".$db->quote($mail)." AND type='billing' ORDER BY id DESC LIMIT 1";
	 	$db->setQuery($query);
	 	return $db->loadObject();

	 }

	 function checkShippingAddress() {
		$user =	JFactory::getUser();
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$mail = $session->get('guest_mail');

		if(empty($mail) && $user->id)
			$query = "SELECT * FROM #__k2store_address WHERE user_id={$user->id}  AND type='shipping' ORDER BY id DESC LIMIT 1";
		elseif(!empty($mail))
			$query = "SELECT * FROM #__k2store_address WHERE email=".$db->quote($mail)." AND type='shipping' ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getCountryList($name,$field_id,$default_cid)
	{
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select('a.country_id,a.country_name');
		$query->from('#__k2store_countries AS a');
		$query->where('state = 1');
		$query->order('a.country_name');
		$db->setQuery($query);
		$countries =$db->loadObjectList();
		$params = JComponentHelper::getParams('com_k2store');

		//generate country filter list
		$country_options = array();
		$country_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_COUNTRY'));
		foreach($countries as $row) {
			$country_options[] =  JHTML::_('select.option', $row->country_id, $row->country_name);
		}

		//check for adding required class
		//$class = 'class="required"';
		$class = '';
		return JHTML::_('select.genericlist', $country_options, $name, $class, 'value', 'text', $default_cid, $field_id);

	}

	function getZoneList($name,$id,$country_id,$zid)
	{
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select('a.zone_id,a.zone_name');
		$query->from('#__k2store_zones AS a');
		$query->where('a.state = 1 AND a.country_id='.$country_id);
		$query->order('a.zone_name');
		$db->setQuery($query);
		$zones =$db->loadObjectList();
		//generate country filter list
		$zone_options = array();
		$zone_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_STATE'));
		foreach($zones as $row) {
			$zone_options[] =  JHTML::_('select.option', $row->zone_id, $row->zone_name);
		}

		return JHTML::_('select.genericlist', $zone_options, $name, '', 'value', 'text',$zid,$id);
	}

	function getZonesByCountryId($country_id) {

		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select('a.zone_id,a.zone_name');
		$query->from('#__k2store_zones AS a');
		$query->where('a.state = 1 AND a.country_id='.$country_id);
		$query->order('a.zone_name');
		$db->setQuery($query);
		$zones = $db->loadAssocList();
		return $zones;
	}

	/* return object single record of country id */

	function getCountryById($country_id) {

		$query	= $this->_db->getQuery(true);
		$query->select('*');
		$query->from('#__k2store_countries');
		$query->where('country_id='.$country_id);
		$this->_db->setQuery($query);
		return $this->_db->loadObject();

	}

	/* return object single record of zone id */
	function getZonesById($zone_id) {

		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select('*');
		$query->from('#__k2store_zones');
		$query->where('state = 1');
		$query->where('zone_id='.$zone_id);
		$db->setQuery($query);
		$zone = $db->loadObject();
		return $zone;
	}

	function getTotalCustomersByEmail($email) {
		$query	= $this->_db->getQuery(true);
		$query->select('COUNT(*) AS total');
		$query->from('#__users');
		$query->where('LOWER(email)='.JString::strtolower($this->_db->quote($this->_db->escape($email))));
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

}
