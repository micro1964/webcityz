<?php
/*------------------------------------------------------------------------
 # com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Jsecommerce model.
 *
 * @package		Joomla.Site
 * @subpackage	com_jsecommerce
 * @since		1.6
*/
class K2StoreModelTaxRate extends JModelAdmin
{
	protected $text_prefix = 'COM_K2STORE';

	protected $view_list = 'taxrates';

	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		// Get the form.
		$form = $this->loadForm('com_k2store.taxrate', 'taxrate', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}



	public function getTable($type = 'taxrate', $prefix = 'Table', $config = array())
	{

		return JTable::getInstance($type, $prefix, $config);
	}


	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
		}

		return $item;
	}


	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_k2store.edit.taxrate.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('taxrate.id') == 0) {
				$app = JFactory::getApplication();
				// set the id here if it does not work.
				//$data->set('pro_id', JRequest::getInt('pro_id', $app->getUserState('com_jsecommerce.profile.filter.category_id')));
			}
		}

		return $data;
	}

	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

	}

}
