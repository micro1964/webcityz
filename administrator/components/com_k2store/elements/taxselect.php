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

class JElementTaxSelect extends JElement
{
	var	$_name = 'taxselect';

	function fetchElement($name, $value, &$node, $control_name){

		$fieldName = $control_name.'['.$name.']';

		//$document = JFactory::getDocument();
		//$document->addScriptDeclaration($js);
		//$document->addStyleDeclaration($css);

		$lists = $this->_getSelectProfiles($fieldName, $value);
		return $lists;
	}

	function _getSelectProfiles($var, $default) {

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
