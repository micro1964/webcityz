<?php
/*
 * --------------------------------------------------------------------------------
   Weblogicx India  - K2 Store
 * --------------------------------------------------------------------------------
 * @package		Joomla! 1.5x
 * @subpackage	K2 Store
 * @author    	Weblogicx India http://www.weblogicxindia.com
 * @copyright	Copyright (c) 2010 - 2015 Weblogicx India Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link		http://weblogicxindia.com
 * --------------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die ('Restricted access');
JLoader::register('K2Plugin', JPATH_ADMINISTRATOR.'/components/com_k2/lib/k2plugin.php');
require_once (JPATH_SITE.'/components/com_k2store/helpers/utilities.php');
require_once (JPATH_SITE.'/components/com_k2store/helpers/cart.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/prices.php');
JLoader::register('K2Parameter', JPATH_ADMINISTRATOR.'/components/com_k2/lib/k2parameter.php');
class plgK2K2Store extends K2Plugin {

	// Some params
	var $pluginName = 'k2store';
	var $pluginNameHumanReadable = 'K2 Store';

	function plgK2K2Store( & $subject, $params) {

		parent::__construct($subject, $params);
		//$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		$lang = JFactory::getLanguage();
		$lang->load('com_k2store');
	}

	public function onK2PrepareContent(&$item, &$params, $page=0)
	{

		$app = JFactory::getApplication();
		// Bail out if the page is not HTML
		if($app->input->getCmd('format')!='html' && $app->input->getCmd('format')!='') return;

		// simple performance check to determine whether plugin should process further
		if(JString::strpos($item->text, 'k2storecart') === false) return;
				$k2params = JComponentHelper::getParams('com_k2store');
				if($k2params->get('addtocart_placement', 'default') == 'default') {
					return true;
				}
				// expression to search for k2storecart
				$regex_with_id		= '/{k2storecart\s+(.*?)}/i';
				$regex_without_id	= '/{k2storecart\}/i';

				// Find all instances of plugin and put in $matches for loading k2store cart
				// $matches[0] is full pattern match, $matches[1] is the article id
				preg_match_all($regex_with_id, $item->text, $results_with_id, PREG_SET_ORDER);
				// No matches, skip this
				if($results_with_id) {
					$matches = $results_with_id;
				} else {
					preg_match_all($regex_without_id, $item->text, $results_without_id, PREG_SET_ORDER);
					$matches = $results_without_id;
				}

				if ($matches) {
					foreach ($matches as $match) {
						//$match[0] has the text.
						//check again
						$item_id = NULL;
						if (empty($match[1])) {
							$item_id = $item->id;
						} else {
							$item_id = (int) $match[1];
						}

						if(empty($item_id)) {
							return true;
						}

						$product = new JObject();
						$product->id = $item_id;
						$product->text = $item->text;
						$output = $this->_loadCart($product, $params);
						$item->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $item->text, 1);
					}
				}
	}

	function onK2AfterDisplay( & $item, & $params, $limitstart) {

		$mainframe = JFactory::getApplication();

		$k2params = JComponentHelper::getParams('com_k2store');
		if($k2params->get('addtocart_placement', 'default') == 'tag') {
			$output = '';
		} else {
			$output = $this->_loadCart($item, $params);
		}
		return $output;

	}

	function onAfterK2Save($row, $isNew) {

		$plugins = $this->_getPluginData($row);

		$this->_addProductOptions($row);

		$stock = $plugins->get('item_qty');
		if($stock > 0 || $stock == -1 ) {

			//initialise variables

			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_k2store/tables');
			$table =  JTable::getInstance('ProductQuantities', 'Table');
			//save this stock in the product quantities table.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__k2store_productquantities');
			$query->where('product_id='.$db->quote($row->id));
			$db->setQuery($query);
			$item = $db->loadObject();

			if(!$item) {
				$table->product_id = $row->id;
				$table->quantity = $stock;
				if(!$table->save($data)) {
					 $table->setError($table->getError);
					 return false;
				}
			} else {
				$table->load($item->productquantity_id);
				$table->quantity = $stock;
				if(!$table->store()) {
					$table->setError($table->getError);
					return false;
					}
			}

		}

	}

	 function onRenderAdminForm( & $item, $type, $tab='') {

		if($type == 'item' && $tab == 'content') {
			$plugins = $this->_getPluginData($item);

				$stock = K2StorePrices::getStock($item->id);
				//parse the JSON string to an array
				$registry = new JRegistry();
				$registry->loadString($item->plugins);
				$plugin_data = $registry->toArray();

				if(isset($plugin_data['k2storeitem_qty'])) {
					//now reset the quantity.
					$plugin_data['k2storeitem_qty'] = $stock;
					$registry->loadArray($plugin_data);

					//now store the data;
					$item->plugins =  $registry->toString();

				}
			//render the form

			if ( !empty ($tab)) {
				$path = $type.'-'.$tab;
			}
			else {
				$path = $type;
			}
			if (!isset($item->plugins))
			{
				$item->plugins = NULL;
			}

			$xml_file = JPATH_SITE.'/plugins/k2/'.$this->pluginName.'/'.$this->pluginName.'.xml';

			if (version_compare(JVERSION, '3.0', 'ge')) {

				jimport('joomla.form.form');
				$form = JForm::getInstance('plg_k2_'.$this->pluginName.'_'.$path, $xml_file, array(), true, 'fields[@group="'.$path.'"]');
				//print_r($form);
				$values = array();
				if ($item->plugins)
				{
					foreach (json_decode($item->plugins) as $name => $value)
					{
						$count = 1;
						$values[str_replace($this->pluginName, '', $name, $count)] = $value;
					}
					$form->bind($values);
				}
				$fields = '';
				foreach ($form->getFieldset() as $field)
				{
					$search = 'name="'.$field->name.'"';
					$replace = 'name="plugins['.$this->pluginName.$field->name.']"';
					$input = JString::str_ireplace($search, $replace, $field->__get('input'));
					$fields .= $field->__get('label').' '.$input;
				}
			} else {
				$form = new K2Parameter($item->plugins, $xml_file, $this->pluginName);
			    $fields = $form->render('plugins', $path);
			}

			if ($fields){
				$plugin = new JObject;
				$plugin->set('name', JText::_( 'K2Store' ));
				$plugin->set('fields', $fields);
				return $plugin;
			}

		}

	}

	function _addProductOptions($row) {
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_k2store/tables');
		$plugins = $this->_getPluginData($row);

		//get option IDs and save them as product option ids
		$pa_options = $plugins->get('item_option')->product_option_ids;

		//get whether the option is required.
		$pa_option_required = $plugins->get('item_option')->product_option_required;

		if(isset($pa_options) && count($pa_options) && isset($pa_option_required) ) {

			//convert option required values object to array
			$registry = new JRegistry;
			$registry->loadObject($pa_option_required );
			$pa_option_required = $registry->toArray();

			foreach ($pa_options as $option_id) {
				$table =  JTable::getInstance('ProductOptions', 'Table');
				//save this stock in the product quantities table.
				$table->product_id = $row->id;
				$table->option_id = $option_id;
				if($pa_option_required[$option_id]) {
					$table->required = 1;
				} else {
					$table->required = 0;
				}
				$table->store();
			}

		}

		//if user modified his option preferences, we got to get the changes and save them as well.
		$modified_option_required = $plugins->get('item_option')->product_option_required_save;
		if(isset($modified_option_required) && count($modified_option_required ) ) {
			foreach($modified_option_required as $po_id=>$value) {
				$item =  JTable::getInstance('ProductOptions', 'Table');
				$item->load($po_id);
				$item->required = $value;
				$item->store();
			}
		}


	}


	//function to get plugin data

	protected function _getPluginData($row) {


		$pluginName = 'k2store';

		if(JVERSION==1.7) {
			// Get the output of the K2 plugin fields (the data entered by your site maintainers)
			$plugins = new JParameter($row->plugins, '', $pluginName);
		} else {
			$plugins = new K2Parameter($row->plugins, '', $pluginName);
		}

		return $plugins;
	}

	protected function _loadCart($item, $params){

		$lang = JFactory::getLanguage();
		$lang->load('com_k2store');

		if(empty($item->id) || is_int($item->id == false)) {
			return '';
		}

		$k2params = JComponentHelper::getParams('com_k2store');
		$item_enabled = K2StorePrices::getItemEnabled($item->id);
		// show/hide add to cart button
		$content = '';
		$output = '';

		if($item_enabled) {
			$output = $content = K2StoreHelperCart::getAjaxCart($item);
			if($k2params->get('isregister')) {

				if(JFactory::getUser()->id) {
					$output = $content;
				} else {
					$output = '';
				}
			}
		}
		return $output;
	}

} // END CLASS
