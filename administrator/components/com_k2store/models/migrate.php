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

jimport('joomla.application.component.model');

/**
 *
 * @package		Joomla
 * @subpackage	K2Store
 * @since 1.5
 */
class K2StoreModelMigrate extends K2StoreModel
{


	function getData() {

		$list = array();

		//first load all product attributes.
		$attributes = $this->getAttributes();
		if(isset($attributes) && count($attributes)) {
			foreach($attributes as $attribute){
				$paos = $this->getPAOWithEqualSign($attribute->productattribute_id);
				if(isset($paos) && count($paos)) {
					foreach($paos as $pao) {
						$item = array();
						$item['product_id'] = $attribute->product_id;
						$item['product_name'] = $attribute->title;
						$item['attribute_name'] = $attribute->productattribute_name;
						$item['pao'] = $pao;
						array_push($list, $item);
					}
				}
			}
		}

		return $list;

	}

	function getAttributes() {
		$rows = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		//lets first select product attributes
		$query->select('pa.*');
		$query->from('#__k2store_productattributes AS pa');
		$query->select('p.title');
		$query->join('LEFT', '#__k2_items AS p ON pa.product_id=p.id');
		$db->setQuery($query);
		try {
    		$rows = $db->loadObjectList();

		} catch (Exception $e) {
    		$this->setError(JText::_('K2STORE_MIGRATE_CURRENT_VERSION'));
    	}
		return $rows;

	}


	function getPAOWithEqualSign($attribute_id) {

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__k2store_productattributeoptions');
		$query->where('productattribute_id='.$attribute_id);
		$query->where('productattributeoption_prefix='.$db->quote($db->escape('=')));

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	function migrate() {

		if(!self::canMigrate() || K2STORE_ATTRIBUTES_MIGRATED==1) {
			$this->setError(JText::_('K2STORE_NO_ATTRIBUTES_TO_MIGRATE'));
			return false;
		}

		//first get products that cannot be migrated automatically
		$ids = self::getProductsNotToBemigrated();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		//lets first select product attributes
		$query->select('pa.*');
		$query->from('#__k2store_productattributes AS pa');
		if(count($ids)) {
			$query->where('pa.product_id NOT IN ('.implode(',', $ids).')');
		}

		$db->setQuery($query);

		$pas = $db->loadObjectList();
		//print_r($pas); exit;
		if(count($pas)) {
		//loop through the product attributes and also insert in option and product options table
		foreach ($pas as $attribute) {

			$option = JTable::getInstance('Option', 'Table');

			//if the table already has an option in this name. dont save it as it will be a duplicate. Just take its id
			$unique_name = JApplication::stringURLSafe($attribute->productattribute_name);
			if ($option->load(array('option_unique_name' => $unique_name, 'option_name' => $attribute->productattribute_name, 'type' => $attribute->productattribute_display_type)))
			{
				$product_id = $attribute->product_id;
				$option_id = $option->option_id;
			} else {
				//compatibility
				if($attribute->productattribute_display_type) {
					$option->type = $attribute->productattribute_display_type;
				} else {
					$option->type = 'select';
				}
				$option->option_unique_name = $unique_name;
				$option->option_name = $attribute->productattribute_name;
				$option->state = 1;
				$option->store();
			}

			$product_id = $attribute->product_id;
			$option_id = $option->option_id;

			if($option_id) {
				//now insert in product options table
				$product_option_table = JTable::getInstance('ProductOptions', 'Table');

				$product_option_table->product_id = $product_id;
				$product_option_table->option_id = $option_id;
				$product_option_table->option_value = '';
				$product_option_table->required = $attribute->productattribute_required;
				$product_option_table->store();

				$product_option_id = $product_option_table->product_option_id;

				//we now have the option id, product_option_id and product_id
				//now proceed with option values, global and product specific.

				//get the product attribute options
				$paos = self::getProductAttributeOptions($attribute->productattribute_id);
				if(count($paos)) {

					foreach($paos as $pao) {

						$option_value = JTable::getInstance('OptionValues', 'Table');

						//if the table already has an optionvalue in this name. dont save it as it will be a duplicate. Just take its id
						if ($option_value->load(array('option_id' => $option_id, 'optionvalue_name' => $pao->productattributeoption_name)))
						{
							$optionvalue_id = $option_value->optionvalue_id;
						} else {
							$option_value->option_id = $option_id;
							$option_value->optionvalue_name = $pao->productattributeoption_name;
							$option_value->ordering = $pao->ordering;
							$option_value->store();
						}

						$optionvalue_id = $option_value->optionvalue_id;

						//now insert in product optionvalues table

						$productoptionvalues_table = JTable::getInstance('ProductOptionValues', 'Table');
						$productoptionvalues_table->product_option_id = $product_option_id;
						$productoptionvalues_table->product_id = $product_id;
						$productoptionvalues_table->option_id = $option_id;
						$productoptionvalues_table->optionvalue_id = $optionvalue_id;
						$productoptionvalues_table->product_optionvalue_price = $pao->productattributeoption_price;
						$productoptionvalues_table->product_optionvalue_prefix = $pao->productattributeoption_prefix;
						$productoptionvalues_table->ordering = $pao->ordering;
						$productoptionvalues_table->store();
					} //pao loop

				} //if paos

			} //if option id
		} //pa loop

		//mark attributes migrated and dont allow multiple hitting.
		jimport('joomla.filesystem.file');
		$file = JPATH_COMPONENT_ADMINISTRATOR.'/version.php';
		if(JFile::exists($file)) {
			$fh = fopen($file, 'a') or die("can't open file");
			$buffer = "define('K2STORE_ATTRIBUTES_MIGRATED', '1');";
			fwrite($fh, $buffer);
			fclose($fh);
		}

		$this->setError(JText::_('K2STORE_MIGRATE_SUCCESSFUL'));
		return true;
	} else {
		$this->setError(JText::_('K2STORE_NO_ATTRIBUTES_TO_MIGRATE'));
		return false;
	}
	return false;
} //end of function

	function getProductAttributeOptions($attribute_id) {

		$db = JFactory::getDbo();
		$query = 'SELECT * FROM #__k2store_productattributeoptions WHERE productattribute_id='.$attribute_id;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;

	}

	function getProductsNotToBemigrated() {
		$products = array();
		$lists = self::getData();
		foreach($lists as $list) {
			$products[] = $list['product_id'];
		}
		return array_unique($products);

	}

	function canMigrate() {

		$attributes = self::getAttributes();
			if(count($attributes) < 1){
				return false;
			} else {
				return true;
			}

	}

} //end of class
