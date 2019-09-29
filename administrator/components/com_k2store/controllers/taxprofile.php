<?php

// controller

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

class K2StoreControllerTaxProfile extends JControllerForm
{


	function settaxrule(){

		$app = JFactory::getApplication();
		$model = $this->getModel();
		$ns = 'com_k2store.taxrule';

		$state['id']        = JRequest::getVar('id', JRequest::getVar('id', '', 'get', 'int'), 'post', 'int');

		foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );
		}

		$id = $app->input->get('id', 0);
		$item = $model->getTaxRule($id);

		$lists['taxrate']= $model->getTaxRateList($item->taxrate_id);
		$lists['address']= $model->getAddressList($item->address);

		$view   = $this->getView( 'taxrule', 'html' );
		$view->set( '_controller', 'taxprofile' );
		$view->set( '_view', 'taxprofile' );
		$view->set( '_action', "index.php?option=com_k2store&view=taxprofile&task=taxprofile.settaxrule&id=".$id."&tmpl=component" );
		$view->setModel( $model, true );
		$view->assign( 'lists', $lists );
		$view->assign( 'item', $item);
		$view->assign( 'taxrule_id', $id );
		$view->setLayout( 'default' );
		$view->display();

	}


	function addTaxRule()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel();

		$response = array();
		$response['error'] = 0;
		if(!$model->saveTaxRule()){
			$response['error'] = 1;
			$response['errorMessage'] = $model->getError();
			}
		if($response['error']==0){
			$response['taxrule_id']=$app->input->get('taxrule_id');
		}

		echo json_encode($response);

		$app->close();
	}

	function deleteTaxRule()
	{
		$app=JFactory::getApplication();
		$model=$this->getModel();

		$taxRule = $model->getTable('taxrule');
		$gid = $app->input->get('taxrule_id');
		$response = array();
		$response['error'] = 0;
		if(!$taxRule->delete(array($gid))){
			$response['error'] = 1;
			$response['errorMessage'] = $taxRule->getError();
			}

		echo json_encode($response);
		$app->close();
	}

}
