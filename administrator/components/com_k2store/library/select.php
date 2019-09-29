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


/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
//jimport('joomla.html.html.select');
if (!version_compare(JVERSION, '3.0', 'ge'))
{
	require_once (JPATH_SITE.'/libraries/joomla/html/html/select.php');
} 
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/prices.php');
class K2StoreSelect extends JHtmlSelect
{   
	 /**
	 * Generates a +/- select list for pao prefixes
	 * 
	 * @param unknown_type $selected
	 * @param unknown_type $name
	 * @param unknown_type $attribs
	 * @param unknown_type $idtag
	 * @param unknown_type $allowAny
	 * @param unknown_type $title
	 * @return unknown_type
	 */
    public static function productattributeoptionprefix( $selected, $name = 'filter_prefix', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false, $title = 'Select Prefix' )
    {
        $list = array();
        if($allowAny) {
            $list[] =  self::option('', "- ".JText::_( $title )." -" );
        }

        $list[] = JHTML::_('select.option',  '+', "+" );
        $list[] = JHTML::_('select.option',  '-', "-" );
       // $list[] = JHTML::_('select.option',  '=', "=" );

        return self::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag );
    }
    
    /**
	 * Generates a selectlist for the specified Product Attribute 
	 *
	 * @param unknown_type $productattribute_id 
	 * @param unknown_type $selected
	 * @param unknown_type $name
	 * @param unknown_type $attribs
	 * @param unknown_type $idtag
	 * @return unknown_type
	 */
    
     public static function productattributeoptions( $productattribute_id, $selected, $name = 'filter_pao', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $required=0, $opt_selected = array())
    {
        $list = array();
        $k2storeparams   = JComponentHelper::getParams('com_k2store');
        JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'models' );
        $model = JModelLegacy::getInstance( 'ProductAttributeOptions', 'K2StoreModel' );
        $model->setId($productattribute_id );
        $model->setState('order', 'a.ordering');
        $items = $model->getAllData();
        
        //now pass it to a view and get things done
         
        JLoader::register( "K2StoreViewMyCart", JPATH_SITE."/components/com_k2store/views/mycart/view.html.php" );
         
        $config = array();
        $config['base_path'] = JPATH_SITE."/components/com_k2store";
         
        // finds the default Site template
        $db = JFactory::getDBO();
        $query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home=1";
        $db->setQuery( $query );
        $template = $db->loadResult();
         
        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_SITE.'/templates/'.$template.'/html/com_k2store/mycart/attributeradio.php'))
        {
        	// (have to do this because we load the same view from the admin-side Orders view, and conflicts arise)
        	$config['template_path'] = JPATH_SITE.'/templates/'.$template.'/html/com_k2store/mycart';
        }
         
        $view = new K2StoreViewMyCart( $config );
        $view->addTemplatePath(JPATH_SITE.'/templates/'.$template.'/html/com_k2store/mycart');
        require_once(JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'models'.DS.'mycart.php');
        $cartmodel =  new K2StoreModelMyCart();
         
        $view->set( '_controller', 'mycart' );
        $view->set( '_view', 'mycart' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->setModel( $model, true );
        $view->assign( 'productattribute_id', $productattribute_id);
        $view->assign( 'name', $name);
        $view->assign( 'selected', $selected);
        $view->assign( 'attribs', $attribs);
        $view->assign( 'idTag', $idtag);
        $view->assign( 'required', $required);
        $view->assign( 'attribs', $attribs);
        $view->assign( 'items', $items);
        $view->assign( 'params', $k2storeparams);
        $view->setLayout( 'attributeselect' );
         
        //$this->_setModelState();
        ob_start();
        $view->display();
        $html .= ob_get_contents();
        ob_end_clean();
        return $html;
        
    }	    
	
    public static function radio_productattributeoptions($productattribute_id, $selected, $name = 'filter_pao', $attribs = array('class' => 'inputbox'), $idtag = null, $required=0, $opt_selected = array())
     {
    	
    	$list = array();
    	$app = JFactory::getApplication();
    	$k2storeparams   = JComponentHelper::getParams('com_k2store');
    	JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'models' );
    	$model = JModelLegacy::getInstance( 'ProductAttributeOptions', 'K2StoreModel' );
    	$model->setId($productattribute_id );
    	$model->setState('order', 'a.ordering');
    	$items = $model->getAllData();
    	
    	//now pass it to a view and get things done
    	
    	JLoader::register( "K2StoreViewMyCart", JPATH_SITE."/components/com_k2store/views/mycart/view.html.php" );
    	
    	$config = array();
    	$config['base_path'] = JPATH_SITE."/components/com_k2store";
    	
    		// finds the default Site template
    		$db = JFactory::getDBO();
    		$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home=1";
    		$db->setQuery( $query );
    		$template = $db->loadResult();
    	
    		jimport('joomla.filesystem.file');
    		if (JFile::exists(JPATH_SITE.'/templates/'.$template.'/html/com_k2store/mycart/attributeradio.php'))
    		{
    			// (have to do this because we load the same view from the admin-side Orders view, and conflicts arise)
    			$config['template_path'] = JPATH_SITE.'/templates/'.$template.'/html/com_k2store/mycart';
    		}
    	
    	$view = new K2StoreViewMyCart( $config );
    	$view->addTemplatePath(JPATH_SITE.'/templates/'.$template.'/html/com_k2store/mycart');
    	require_once(JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'models'.DS.'mycart.php');
    	$cartmodel =  new K2StoreModelMyCart();
    	
    	$view->set( '_controller', 'mycart' );
    	$view->set( '_view', 'mycart' );
    	$view->set( '_doTask', true);
    	$view->set( 'hidemenu', false);
    	$view->setModel( $model, true );
    	$view->assign( 'productattribute_id', $productattribute_id);
    	$view->assign( 'name', $name);
    	$view->assign( 'idTag', $idtag);
    	$view->assign( 'required', $required);
    	$view->assign( 'attribs', $attribs);
    	$view->assign( 'items', $items);
    	$view->assign( 'params', $k2storeparams);
    	$view->setLayout( 'attributeradio' );
    	
    	//$this->_setModelState();
    	ob_start();
    	$view->display();
    	$html .= ob_get_contents();
    	ob_end_clean();
    	return $html;
    	 
    	
    }
    
    public function getAttributeDisplayFormat($attribute_id) {
    	$row = self::getAttribute($attribute_id);
    	return $row->productattribute_display_type;
    }
    
    public function getAttributeRequired($attribute_id) {
    	$row = self::getAttribute($attribute_id);
    	return $row->productattribute_required;
    }
    
    public function getAttributeName($attribute_id) {
    	$row = self::getAttribute($attribute_id);
    	return $row->productattribute_name;
    }
    
    
    protected function getAttribute($attribute_id) {
    		
    	JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');
    	$row = JTable::getInstance('ProductAttributes', 'Table');
    	$row->load($attribute_id);
    	return $row;
    }
}
