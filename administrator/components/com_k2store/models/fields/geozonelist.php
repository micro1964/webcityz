<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


class JFormFieldGeoZoneList extends JFormFieldList {

	protected $type = 'GeoZoneList';

	public function getInput() {
			
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'taxrates.php');
		$model = new K2StoreModelTaxRates;
		$countries = $model->getGeoZones();
		//generate geozone filter list
		$geozone_options = array();
		$geozone_options[] = JHTML::_('select.option', '', JText::_('K2STORE_SELECT_GEOZONE'));
		foreach($countries as $row) {
			$geozone_options[] =  JHTML::_('select.option', $row->geozone_id, $row->geozone_name);
		}
			
		return JHTML::_('select.genericlist', $geozone_options, $this->name, 'onchange=', 'value', 'text', $this->value);
	}
	 
}
