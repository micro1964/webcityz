<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store v 1.0
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * TaxSelect Form Field class for the K2Store component
 */
class JFormFieldTaxSelectJ3 extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'TaxSelectJ3';

	protected function getInput()
 {
		$lists = $this->_getSelectProfiles($this->name, $this->id,$this->value);
		return $lists;
	}

	protected function _getSelectProfiles($var, $id, $default) {

		$db = JFactory::getDBO();
		$option ='';

		$query = 'SELECT taxprofile_id AS value, taxprofile_name AS text FROM #__k2store_taxprofiles ORDER BY taxprofile_id';
		$db->setQuery( $query );
		$taxprofiles = $db->loadObjectList();

		$types[] 		= JHTML::_('select.option',  '0', '- '. JText::_( 'K2STORE_SELECT_TAXPROFILE' ) .' -' );
		foreach( $taxprofiles as $item )
		{
			$types[] = JHTML::_('select.option',  $item->value, JText::_( $item->text ) );
		}

		$lists 	= JHTML::_('select.genericlist',   $types, $var, 'class="inputbox" size="1" '.$option.'', 'value', 'text', $default );

		return $lists;

	}

}