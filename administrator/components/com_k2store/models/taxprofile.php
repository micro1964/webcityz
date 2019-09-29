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
class K2StoreModelTaxProfile extends JModelAdmin
{
	protected $text_prefix = 'COM_K2STORE';

	protected $view_list = 'taxprofiles';

	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		// Get the form.
		$form = $this->loadForm('com_k2store.taxprofile', 'taxprofile', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function getTable($type = 'taxprofile', $prefix = 'Table', $config = array())
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
		$data = JFactory::getApplication()->getUserState('com_k2store.edit.taxprofile.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('taxprofile.id') == 0) {
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

	// geo zone Rule functions
	public function saveTaxRule(){

		$app=JFactory::getApplication();
		$data = $app->input->post->get('jform',array(),'array');

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('COUNT(*)');
		$query->from('#__k2store_taxrules AS r');
		$query->where('r.taxprofile_id='.$db->escape($data['taxprofile_id']));
		$query->where('r.taxrate_id='.$db->escape($data['taxrate_id']));
		$query->where('r.address='.$db->Quote($db->escape($data['address'])));
		$db->setQuery($query);
		$result = $db->loadResult();

		if($result==1){
			$this->setError(JText::_('K2STORE_TAXRULE_ALREADY_EXISTS'));
			return false;
		}

		$taxRule = $this->getTable('TaxRule');

		if (!$taxRule->bind($data))
		{
			$this->setError($taxRule->getError());
			return false;
		}

		if (!$taxRule->check())
		{

			$this->setError($taxRule->getError());
			return false;

		}

		if (!$taxRule->store())
		{

			$this->setError($taxRule->getError());
			return false;
		}
		$app->input->set('taxrule_id',$taxRule->taxrule_id);
		return true;

	}

	public function getTaxRules (){
		$app = JFactory::getApplication();
		$taxprofile_id = $app->input->get('taxprofile_id',0);
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('r.taxrule_id as id, b.taxrate_name,b.tax_percent, r.address');
		$query->from('#__k2store_taxrules AS r');
		$query->join('LEFT','#__k2store_taxrates AS b ON b.taxrate_id=r.taxrate_id');
		$query->where('r.taxprofile_id='.$taxprofile_id);
		$query->order('r.ordering');
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	public function getTaxRule($tid){
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('r.taxrule_id as id, r.taxprofile_id,r.taxrate_id , b.taxrate_name, r.address');
		$query->from('#__k2store_taxrules AS r');
		$query->join('LEFT','#__k2store_taxrates AS b ON b.taxrate_id=r.taxrate_id');
		$query->where('r.taxrule_id='.$tid);
		$db->setQuery($query);
		return $db->loadObject();
	}

	public function getTaxRateList($default_val=''){
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.taxrate_id,a.taxrate_name, a.tax_percent');
		$query->from('#__k2store_taxrates AS a');
		$query->where('state = 1');
		$query->order('a.taxrate_name');
		$db->setQuery($query);
		$taxrates = $db->loadObjectList();

		$taxrate_options = array();
		$taxrate_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_TAXRATE'));
		foreach($taxrates as $item) {
			$taxrate_name =  $item->taxrate_name.' ('.floatval($item->tax_percent).'%)';
			$taxrate_options[] =  JHTML::_('select.option', $item->taxrate_id, $taxrate_name);
		}

		$taxrate_list = JHTML::_('select.genericlist', $taxrate_options, 'jform[taxrate_id]', '', 'value', 'text', $default_val);

		return $taxrate_list;
	}

	public function getAddressList($default_val=''){
		$address_options = array();
		$address_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_ADDRESS'));
		$address_options[] =  JHTML::_('select.option', 'shipping', JText::_('K2STORE_SHIPPING_ADDRESS'));
		$address_options[] =  JHTML::_('select.option', 'billing', JText::_('K2STORE_BILLING_ADDRESS'));
		$address_options[] =  JHTML::_('select.option', 'store', JText::_('K2STORE_STORE_ADDRESS'));
		$address_list = JHTML::_('select.genericlist', $address_options, 'jform[address]', '', 'value', 'text', $default_val);
		return $address_list;
	}

}
