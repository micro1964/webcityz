<?php

// controller 

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

class K2StoreControllerGeoZone extends JControllerForm
{
	
	function setrule(){
	
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$ns = 'com_k2store.geozonerule';
		
		$state['id']        = JRequest::getVar('id', JRequest::getVar('id', '', 'get', 'int'), 'post', 'int');
	
		foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );
		}
	
		$id = $app->input->get('id', 0);
		$item = $model->getGeoZoneRule($id);
	
		$country_options= $model->getCountryOptions();
		$lists['country'] = JHTML::_('select.genericlist', $country_options, 'jform[country_id]', '', 'value', 'text', $item->country_id);
	
		$view   = $this->getView( 'geozonerule', 'html' );
		$view->set( '_controller', 'geozone' );
		$view->set( '_view', 'geozone' );
		$view->set( '_action', "index.php?option=com_k2store&view=geozone&task=geozone.setrule&id=".$id."&tmpl=component" );
		$view->setModel( $model, true );
		$view->assign( 'lists', $lists );
		$view->assign( 'item', $item);
		$view->assign( 'geozonerule_id', $id );
		$view->setLayout( 'default' );
		$view->display();
	
	}
	
	
	function addGeoZoneRule()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel();
		
		$response = array();
		$response['error'] = 0;
		if(!$model->saveGeoZoneRule()){
			$response['error'] = 1;
			$response['errorMessage'] = $model->getError();
			}
		if($response['error']==0){
			$response['geozonerule_id']=$app->input->get('geozonerule_id');
		}
		
		echo json_encode($response);
		
		$app->close();
	}
	
	function deleteGeoZoneRule()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel();
		
		$geoZoneRule = $model->getTable('geozonerule');
		$gid = $app->input->get('geozonerule_id');
		$response = array();
		$response['error'] = 0;
		if(!$geoZoneRule->delete(array($gid))){
			$response['error'] = 1;
			$response['errorMessage'] = $geoZoneRule->getError();
			}
		
		echo json_encode($response);
		$app->close();
	}
	
	function getZone()
	{
		$app=JFactory::getApplication();
		$data = $app->input->post->get('jform',array(),'array');
		$country_id = $data['country_id'];
		$zone_id = $data['zone_id'];
		$z_fname=$data['field_name']; 
		$z_id=$data['field_id'];
		
		// based on the country id, get zones and generate a select box
		if(!empty($country_id))
		{			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('zone_id,zone_name');
			$query->from('#__k2store_zones');
			$query->where('country_id='.$country_id);
			$db->setQuery((string)$query);
			$zoneList = $db->loadObjectList();
			$options = array();
			$options[] = JHtml::_('select.option', 0,JTEXT::_('K2STORE_ALL_ZONES'));
			if ($zoneList)
			{
				foreach($zoneList as $zone)
				{
					// this is only to generate the <option> tag inside select tag da i have told n times
					$options[] = JHtml::_('select.option', $zone->zone_id,$zone->zone_name);
				}
			}
			// now we must generate the select list and echo that... wait
			//$z_fname='jform[state_id]';
			$zoneList = JHtml::_('select.genericlist', $options, $z_fname, '', 'value', 'text',$zone_id,$z_id);
			echo $zoneList;
		}
		$app->close();
	}
	
}
