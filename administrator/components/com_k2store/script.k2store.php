<?php
/*------------------------------------------------------------------------
 # com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
-------------------------------------------------------------------------*/


// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');

class Com_K2StoreInstallerScript {

	/** @var string The component's name */
	protected $_extension_name = 'com_k2store';
	private $RemovePlugins = array(
			'user' => array(
					'k2store'
			)
	);

	function preflight( $type, $parent ) {
		$jversion = new JVersion();
		//check for minimum requirement
		// abort if the current Joomla release is older
		if( version_compare( $jversion->getShortVersion(), '2.5.6', 'lt' ) ) {
			Jerror::raiseWarning(null, 'Cannot install K2Store in a Joomla release prior to 2.5.6');
			return false;
		}

		// Bugfix for "Can not build admin menus"
		if(in_array($type, array('install','discover_install'))) {
			$this->_bugfixDBFunctionReturnedNoError();
		} else {
			$this->_bugfixCantBuildAdminMenus();
		}

		//check k2store

		$xmlfile = JPATH_ADMINISTRATOR.'/components/com_k2store/manifest.xml';
		if(JFile::exists($xmlfile)) {
			$xml = JFactory::getXML($xmlfile);
			$version=(string)$xml->version;

			//check for minimum requirement
			// abort if the current K2Store release is older
			if( version_compare( $version, '3.0.3', 'lt' ) ) {
				Jerror::raiseWarning(null, 'You should first upgrade to K2Store 3.0.3 and then install 3.1.x version. Otherwise, the changes made till 3.0.3 wont be reflected in your install');
				return false;
			}

			//check the previous version in case the user intalls it twice.
			$previous_version = $this->_getPreviousVersion();

			if(is_null($previous_version) || $previous_version !='3.0.3' ) {
				$file = JPATH_ADMINISTRATOR.'/components/com_k2store/pre-version.txt';
				$buffer = $version;
				JFile::write($file, $buffer);
			}
		}


	}

	function install() {
		$db = JFactory::getDbo();
		//get the table list
		$tables = $db->getTableList();
		//get prefix
		$prefix = $db->getPrefix();

		//orders table modifications
		if(in_array($prefix.'k2store_orders', $tables)){
			$fields = $db->getTableColumns('#__k2store_orders');

			if (!array_key_exists('user_email', $fields)) {
				$query = "ALTER TABLE #__k2store_orders ADD `user_email` varchar(255) NOT NULL AFTER `user_id`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('token', $fields)) {
				$query = "ALTER TABLE #__k2store_orders ADD `token` varchar(255) NOT NULL AFTER `user_email`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('customer_language', $fields)) {
				$query = "ALTER TABLE #__k2store_orders ADD `customer_language` varchar(255) NOT NULL AFTER `customer_note`";
				$db->setQuery($query);
				$db->query();
			}
		}

		//address
		if(in_array($prefix.'k2store_address', $tables)){
			$fields = $db->getTableColumns('#__k2store_address');

			if (!array_key_exists('email', $fields)) {
				$query = "ALTER TABLE #__k2store_address ADD `email` varchar(255) NOT NULL AFTER `last_name`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('zone_id', $fields)) {
				$query = "ALTER TABLE #__k2store_address ADD `zone_id` varchar(255) NOT NULL AFTER `zip`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('country_id', $fields)) {
				$query = "ALTER TABLE #__k2store_address ADD `country_id` varchar(255) NOT NULL AFTER `zone_id`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('type', $fields)) {
				$query = "ALTER TABLE #__k2store_address ADD `type` varchar(255) NOT NULL AFTER `fax`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('company', $fields)) {
				$query = "ALTER TABLE #__k2store_address ADD `company` varchar(255) NOT NULL AFTER `type`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('tax_number', $fields)) {
				$query = "ALTER TABLE #__k2store_address ADD `tax_number` varchar(255) NOT NULL AFTER `company`";
				$db->setQuery($query);
				$db->query();
			}
		}

			//orderitem attributes
		if(in_array($prefix.'k2store_orderitemattributes', $tables)){
			$fields = $db->getTableColumns('#__k2store_orderitemattributes');

			if (!array_key_exists('productattributeoption_id', $fields)) {
				$query = "ALTER TABLE #__k2store_orderitemattributes ADD `productattributeoption_id` int(11) NOT NULL AFTER `orderitem_id`";
				$db->setQuery($query);
				$db->query();
			}


			if (!array_key_exists('productattributeoptionvalue_id', $fields)) {
				$query = "ALTER TABLE #__k2store_orderitemattributes ADD `productattributeoptionvalue_id` int(11) NOT NULL AFTER `productattributeoption_id`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('orderitemattribute_name', $fields)) {
				$query = "ALTER TABLE #__k2store_orderitemattributes ADD `orderitemattribute_name` varchar(255) NOT NULL AFTER `productattributeoptionvalue_id`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('orderitemattribute_value', $fields)) {
				$query = "ALTER TABLE #__k2store_orderitemattributes ADD `orderitemattribute_value` varchar(255) NOT NULL AFTER `orderitemattribute_name`";
				$db->setQuery($query);
				$db->query();
			}

			if (!array_key_exists('orderitemattribute_type', $fields)) {
				$query = "ALTER TABLE #__k2store_orderitemattributes ADD `orderitemattribute_type` varchar(255) NOT NULL AFTER `orderitemattribute_prefix`";
				$db->setQuery($query);
				$db->query();
			}

		}

		//tax profiles
		if(in_array($prefix.'k2store_taxprofiles', $tables)){
			$fields = $db->getTableColumns('#__k2store_taxprofiles');

			if (!array_key_exists('taxprofile_id', $fields) && array_key_exists('id', $fields) ) {

				//we have the old table. drop it
				$query = "DROP TABLE #__k2store_taxprofiles";
				$db->setQuery($query);
				$db->query();

				//create a new one
				$query = "CREATE TABLE IF NOT EXISTS `#__k2store_taxprofiles` (
				`taxprofile_id` int(11) NOT NULL AUTO_INCREMENT,
				`taxprofile_name` varchar(255) NOT NULL,
				`state` int(11) NOT NULL,
				`ordering` int(11) NOT NULL,
				PRIMARY KEY (`taxprofile_id`)
				) DEFAULT CHARSET=utf8;
				";
				$db->setQuery($query);
				$db->query();
			}

		}

		//store profile
		if(!in_array($prefix.'k2store_storeprofiles', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_storeprofiles` (
			  `store_id` int(11) NOT NULL AUTO_INCREMENT,
			  `store_name` varchar(255) NOT NULL,
			  `store_desc` varchar(255) NOT NULL,
			  `country_id` int(11) NOT NULL,
			  `zone_id` int(11) NOT NULL,
			  `country_name` varchar(255) NOT NULL,
			  `zone_name` varchar(255) NOT NULL,
			  `state` int(11) NOT NULL,
			  `ordering` int(11) NOT NULL,
			  PRIMARY KEY (`store_id`)
			) DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
		}

		//geozonerules
		if(!in_array($prefix.'k2store_geozonerules', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_geozonerules` (
			  `geozonerule_id` int(11) NOT NULL AUTO_INCREMENT,
			  `geozone_id` int(11) NOT NULL,
			  `country_id` int(11) NOT NULL,
			  `zone_id` int(11) NOT NULL,
			  `ordering` int(11) NOT NULL,
			  PRIMARY KEY (`geozonerule_id`)
			)DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
		}

		//geozones
		if(!in_array($prefix.'k2store_geozones', $tables)){
			$query = "
			 CREATE TABLE IF NOT EXISTS `#__k2store_geozones` (
			  `geozone_id` int(11) NOT NULL AUTO_INCREMENT,
			  `geozone_name` varchar(255) NOT NULL,
			  `state` int(11) NOT NULL,
			  `ordering` int(11) NOT NULL,
			  PRIMARY KEY (`geozone_id`)
			)DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
		}

		//tax rates
		if(!in_array($prefix.'k2store_taxrates', $tables)){
			$query = "
			 CREATE TABLE IF NOT EXISTS `#__k2store_taxrates` (
				 `taxrate_id` int(11) NOT NULL AUTO_INCREMENT,
				  `geozone_id` int(11) NOT NULL,
				  `taxrate_name` varchar(255) NOT NULL,
				  `tax_percent` decimal(11,3) NOT NULL,
				  `state` int(11) NOT NULL,
				  `ordering` int(11) NOT NULL,
				  PRIMARY KEY (`taxrate_id`)
				)DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
		}

		//tax rules
		if(!in_array($prefix.'k2store_taxrules', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_taxrules` (
			  `taxrule_id` int(11) NOT NULL AUTO_INCREMENT,
			  `taxprofile_id` int(11) NOT NULL,
			  `taxrate_id` int(11) NOT NULL,
			  `address` varchar(255) NOT NULL,
			  `ordering` int(11) NOT NULL,
			  `state` int(11) NOT NULL,
			  PRIMARY KEY (`taxrule_id`)
			)DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
		}

		//product dicount prices

		if(!in_array($prefix.'k2store_productprices', $tables)){
			$query = "
				CREATE TABLE IF NOT EXISTS `#__k2store_productprices` (
				`productprice_id` int(11) NOT NULL AUTO_INCREMENT,
				`product_id` int(11) NOT NULL,
				`quantity_start` int(11) NOT NULL,
				`condition` varchar(255) NOT NULL,
				`quantity_end` int(11) NOT NULL,
				`publish_up` datetime NOT NULL,
				`publish_down` datetime NOT NULL,
				`state` int(11) NOT NULL COMMENT 'publish or unpublish',
				`price` decimal(12,3) NOT NULL,
				`ordering` int(11) NOT NULL,
				PRIMARY KEY (`productprice_id`)
				) DEFAULT CHARSET=utf8;
		";
			$db->setQuery($query);
			$db->query();
		}

		//product options, values - since k2store 3.2
		if(!in_array($prefix.'k2store_options', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_options` (
			  `option_id` int(11) NOT NULL AUTO_INCREMENT,
			  `type` varchar(255) NOT NULL,
			  `option_unique_name` varchar(255) NOT NULL,
			  `option_name` varchar(255) NOT NULL,
			  `ordering` int(11) NOT NULL,
			  `state` int(11) NOT NULL,
			  PRIMARY KEY (`option_id`)
			) DEFAULT CHARSET=utf8;
			";

			$db->setQuery($query);
			$db->query();
		}

		if(!in_array($prefix.'k2store_optionvalues', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_optionvalues` (
				  `optionvalue_id` int(11) NOT NULL AUTO_INCREMENT,
				  `option_id` int(11) NOT NULL,
				  `optionvalue_name` varchar(255) NOT NULL,
				  `ordering` int(11) NOT NULL,
				  PRIMARY KEY (`optionvalue_id`)
				) DEFAULT CHARSET=utf8;
			";

			$db->setQuery($query);
			$db->query();
		}


		if(!in_array($prefix.'k2store_product_options', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_product_options` (
			  `product_option_id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_id` int(11) NOT NULL,
			  `option_id` int(11) NOT NULL,
			  `option_value` varchar(255) NOT NULL,
			  `required` tinyint(1) NOT NULL,
			  PRIMARY KEY (`product_option_id`)
			) DEFAULT CHARSET=utf8;
		";
			$db->setQuery($query);
			$db->query();
		}

		if(!in_array($prefix.'k2store_product_optionvalues', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_product_optionvalues` (
				  `product_optionvalue_id` int(11) NOT NULL AUTO_INCREMENT,
				  `product_option_id` int(11) NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `option_id` int(11) NOT NULL,
				  `optionvalue_id` int(11) NOT NULL,
				  `product_optionvalue_price` decimal(11,3) NOT NULL,
				  `product_optionvalue_prefix` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `ordering` int(11) NOT NULL,
				  PRIMARY KEY (`product_optionvalue_id`)
				) DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
		}

		if(!in_array($prefix.'k2store_productquantities', $tables)){
			$query = "
			CREATE TABLE IF NOT EXISTS `#__k2store_productquantities` (
			  `productquantity_id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_attributes` text NOT NULL COMMENT 'A CSV of productattributeoption_id values, always in numerical order',
			  `product_id` int(11) NOT NULL,
			  `quantity` int(11) NOT NULL,
			  PRIMARY KEY (`productquantity_id`),
			  KEY `product_id` (`product_id`)
			) DEFAULT CHARSET=utf8;
			";
			$db->setQuery($query);
			$db->query();
			}

			if(!in_array($prefix.'k2store_coupons', $tables)){
				$query = "
				CREATE TABLE IF NOT EXISTS `#__k2store_coupons` (
				  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
				  `coupon_name` varchar(255) NOT NULL,
				  `coupon_code` varchar(255) NOT NULL,
				  `state` tinyint(2) NOT NULL,
				  `value` int(11) NOT NULL,
				  `value_type` char(1) NOT NULL,
				  `max_uses` int(11) NOT NULL,
				  `logged` int(11) NOT NULL,
				  `max_customer_uses` int(11) NOT NULL,
				  `valid_from` datetime NOT NULL,
				  `valid_to` datetime NOT NULL,
				  `product_category` varchar(255) NOT NULL,
				  PRIMARY KEY (`coupon_id`)
				)DEFAULT CHARSET=utf8;
				";
				$db->setQuery($query);
				$db->query();
			}


			if(!in_array($prefix.'k2store_order_coupons', $tables)){
				$query = "
				CREATE TABLE IF NOT EXISTS `#__k2store_order_coupons` (
				  `order_coupon_id` int(11) NOT NULL AUTO_INCREMENT,
				  `coupon_id` int(11) NOT NULL,
				  `orderpayment_id` int(11) NOT NULL,
				  `customer_id` int(11) NOT NULL,
				  `amount` decimal(11,5) NOT NULL,
				  `created_date` datetime NOT NULL,
				  PRIMARY KEY (`order_coupon_id`)
				) DEFAULT CHARSET=utf8;
				";
				$db->setQuery($query);
				$db->query();
			}


	}

	function update($parent) {
		if(!defined('DS')){
			define('DS',DIRECTORY_SEPARATOR);
		}
		$db = JFactory::getDBO();
		//get the backup done
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'backup.php');
		$backup = new K2StoreBackup();
		$backup->backup($db);

		jimport('joomla.filesystem.file');
		//lets delete the admin.k2store.php if exists
		$old_entry = JPATH_ADMINISTRATOR.'/components/com_k2store/admin.k2store.php';
		if(JFile::exists($old_entry)) {
			JFile::delete($old_entry);
		}
		//now call the install
		$this->install();

	}

		public function postflight($type, $parent)
		{
			$app = JFactory::getApplication('site');
			$db = JFactory::getDBO();
			$status = new stdClass;
			$status->modules = array();
			$status->plugins = array();
			$src = $parent->getParent()->getPath('source');
			$manifest = $parent->getParent()->manifest;
			$modules = $manifest->xpath('modules/module');
			foreach ($modules as $module)
			{
				$name = (string)$module->attributes()->module;
				$client = (string)$module->attributes()->client;
				if (is_null($client))
				{
					$client = 'site';
				}
				($client == 'administrator') ? $path = $src.'/administrator/modules/'.$name : $path = $src.'/modules/'.$name;
				$installer = new JInstaller;
				$result = $installer->install($path);
				$status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
			}

			$plugins = $manifest->xpath('plugins/plugin');
			foreach ($plugins as $plugin)
			{
				$name = (string)$plugin->attributes()->plugin;
				$group = (string)$plugin->attributes()->group;
				$path = $src.'/plugins/'.$group;
				if (JFolder::exists($src.'/plugins/'.$group.'/'.$name))
				{
					$path = $src.'/plugins/'.$group.'/'.$name;
				}
				$installer = new JInstaller;
				$result = $installer->install($path);
				$query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($name)." AND folder=".$db->Quote($group);
				$db->setQuery($query);
				$db->query();
				$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
			}

			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home=1";
			$db->setQuery( $query );
			$template = $db->loadResult();
			//rename the override folder if exists
			$src = JPATH_SITE.'/templates/'.$template.'/html/com_k2store';
			$dest = JPATH_SITE.'/templates/'.$template.'/html/old_com_k2store';
			require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/helpers/version.php');
			if(K2StoreVersion::getPreviousVersion() == '3.0.3' &&
					JFolder::exists($src )
			) {
				JFolder::move($src, $dest);
			}

			//remove obsolete plugins
			$this->_removeObsoletePlugins($parent);
			$this->_rebuildMenus();
			$this->installationResults($status);

		}


		public function uninstall($parent)
		{
			$db = JFactory::getDBO();
			$status = new stdClass;
			$status->modules = array();
			$status->plugins = array();
			$manifest = $parent->getParent()->manifest;
			$plugins = $manifest->xpath('plugins/plugin');
			foreach ($plugins as $plugin)
			{
				$name = (string)$plugin->attributes()->plugin;
				$group = (string)$plugin->attributes()->group;
				$query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($name)." AND folder = ".$db->Quote($group);
				$db->setQuery($query);
				$extensions = $db->loadColumn();
				if (count($extensions))
				{
					foreach ($extensions as $id)
					{
						$installer = new JInstaller;
						$result = $installer->uninstall('plugin', $id);
					}
					$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
				}

			}
			$modules = $manifest->xpath('modules/module');
			foreach ($modules as $module)
			{
				$name = (string)$module->attributes()->module;
				$client = (string)$module->attributes()->client;
				$db = JFactory::getDBO();
				$query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($name)."";
				$db->setQuery($query);
				$extensions = $db->loadColumn();
				if (count($extensions))
				{
					foreach ($extensions as $id)
					{
						$installer = new JInstaller;
						$result = $installer->uninstall('module', $id);
					}
					$status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
				}

			}
			$this->uninstallationResults($status);
		}

		private function _removeObsoletePlugins($parent)
		{
			$src = $parent->getParent()->getPath('source');
			$db = JFactory::getDbo();

			foreach($this->RemovePlugins as $folder => $plugins) {
				foreach($plugins as $plugin) {
					$sql = $db->getQuery(true)
					->select($db->qn('extension_id'))
					->from($db->qn('#__extensions'))
					->where($db->qn('type').' = '.$db->q('plugin'))
					->where($db->qn('element').' = '.$db->q($plugin))
					->where($db->qn('folder').' = '.$db->q($folder));
					$db->setQuery($sql);
					$id = $db->loadResult();
					if($id)
					{
						$installer = new JInstaller;
						$result = $installer->uninstall('plugin',$id,1);
					}
				}
			}
		}

		/**
		 * Joomla! 1.6+ bugfix for "DB function returned no error"
		 */
		private function _bugfixDBFunctionReturnedNoError()
		{
			$db = JFactory::getDbo();

			// Fix broken #__assets records
			$query = $db->getQuery(true);
			$query->select('id')
			->from('#__assets')
			->where($db->qn('name').' = '.$db->q($this->_extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__assets')
				->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}

			// Fix broken #__extensions records
			$query = $db->getQuery(true);
			$query->select('extension_id')
			->from('#__extensions')
			->where($db->qn('element').' = '.$db->q($this->_extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__extensions')
				->where($db->qn('extension_id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}

			// Fix broken #__menu records
			$query = $db->getQuery(true);
			$query->select('id')
			->from('#__menu')
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('menutype').' = '.$db->q('main'))
			->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__menu')
				->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}
		}


		/**
		 * Joomla! 1.6+ bugfix for "Can not build admin menus"
		 */
		private function _bugfixCantBuildAdminMenus()
		{
			$db = JFactory::getDbo();

			// If there are multiple #__extensions record, keep one of them
			$query = $db->getQuery(true);
			$query->select('extension_id')
			->from('#__extensions')
			->where($db->qn('element').' = '.$db->q($this->_extension_name));
			$db->setQuery($query);
			$ids = $db->loadColumn();
			if(count($ids) > 1) {
				asort($ids);
				$extension_id = array_shift($ids); // Keep the oldest id

				foreach($ids as $id) {
					$query = $db->getQuery(true);
					$query->delete('#__extensions')
					->where($db->qn('extension_id').' = '.$db->q($id));
					$db->setQuery($query);
					$db->query();
				}
			}

			// @todo

			// If there are multiple assets records, delete all except the oldest one
			$query = $db->getQuery(true);
			$query->select('id')
			->from('#__assets')
			->where($db->qn('name').' = '.$db->q($this->_extension_name));
			$db->setQuery($query);
			$ids = $db->loadObjectList();
			if(count($ids) > 1) {
				asort($ids);
				$asset_id = array_shift($ids); // Keep the oldest id

				foreach($ids as $id) {
					$query = $db->getQuery(true);
					$query->delete('#__assets')
					->where($db->qn('id').' = '.$db->q($id));
					$db->setQuery($query);
					$db->query();
				}
			}

			// Remove #__menu records for good measure!
			$query = $db->getQuery(true);
			$query->select('id')
			->from('#__menu')
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('menutype').' = '.$db->q('main'))
			->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name));
			$db->setQuery($query);
			$ids1 = $db->loadColumn();
			if(empty($ids1)) $ids1 = array();
			$query = $db->getQuery(true);
			$query->select('id')
			->from('#__menu')
			->where($db->qn('type').' = '.$db->q('component'))
			->where($db->qn('menutype').' = '.$db->q('main'))
			->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name.'&%'));
			$db->setQuery($query);
			$ids2 = $db->loadColumn();
			if(empty($ids2)) $ids2 = array();
			$ids = array_merge($ids1, $ids2);
			if(!empty($ids)) foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__menu')
				->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}
		}

		private function _rebuildMenus() {

			$db = JFactory::getDbo();

			$query = $db->getQuery(true);
			$query->select('extension_id')
			->from('#__extensions')
			->where($db->qn('element').' = '.$db->q($this->_extension_name));
			$db->setQuery($query);
			$extension_id = $db->loadResult();
			if($extension_id) {
				$query = $db->getQuery(true);
				$query->select('*')
				->from('#__menu')
				->where($db->qn('type').' = '.$db->q('component'))
				->where($db->qn('menutype').' != '.$db->q('main'))
				->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name.'&%'));
				$db->setQuery($query);
				$menus = $db->loadObjectList();

				if(count($menus)) {
					foreach($menus as $menu){
						if($menu->component_id != $extension_id) {
							$table = JTable::getInstance('Menu', 'JTable', array());
							$table->load($menu->id);
							$table->component_id= $extension_id;
							if(!$table->store()) {
								//dont do anything stupid. Just return true. This can be done manually too.
								return true;
							}
						}
					}
				}
			}

		return true;
		}


		private function _getPreviousVersion() {

			jimport('joomla.filesystem.file');
			$target = JPATH_ADMINISTRATOR.'/components/com_k2store/pre-version.txt';
			$version = null;
			if(JFile::exists($target)) {
				$rawData = JFile::read($target);
				$info = explode("\n", $rawData);
				$version = trim($info[0]);
			}
			return $version;

		}


		private function installationResults($status)
		{
			$language = JFactory::getLanguage();
			$language->load('com_k2store');
		        $rows = 0; ?>
		        <img src="<?php echo JURI::root(true); ?>/media/k2store/images/k2store-logo.png" width="109" height="48" alt="K2Store Component" align="right" />
		        <div class="alert alert-block alert-danger">
		        		<?php echo JText::_('K2STORE_ATTRIBUTE_MIGRATION_ALERT'); ?>
		        </div>
		        <h2><?php echo JText::_('K2STORE_INSTALLATION_STATUS'); ?></h2>
		        <table class="adminlist table table-striped">
		            <thead>
		                <tr>
		                    <th class="title" colspan="2"><?php echo JText::_('K2STORE_EXTENSION'); ?></th>
		                    <th width="30%"><?php echo JText::_('K2STORE_STATUS'); ?></th>
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                    <td colspan="3"></td>
		                </tr>
		            </tfoot>
		            <tbody>
		                <tr class="row0">
		                    <td class="key" colspan="2"><?php echo 'K2Store '.JText::_('K2STORE_COMPONENT'); ?></td>
		                    <td><strong><?php echo JText::_('K2STORE_INSTALLED'); ?></strong></td>
		                </tr>
		                <?php if (count($status->modules)): ?>
		                <tr>
		                    <th><?php echo JText::_('K2STORE_MODULE'); ?></th>
		                    <th><?php echo JText::_('K2STORE_CLIENT'); ?></th>
		                    <th></th>
		                </tr>
		                <?php foreach ($status->modules as $module): ?>
		                <tr class="row<?php echo(++$rows % 2); ?>">
		                    <td class="key"><?php echo $module['name']; ?></td>
		                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
		                    <td><strong><?php echo ($module['result'])?JText::_('K2STORE_INSTALLED'):JText::_('K2_NOT_INSTALLED'); ?></strong></td>
		                </tr>
		                <?php endforeach; ?>
		                <?php endif; ?>
		                <?php if (count($status->plugins)): ?>
		                <tr>
		                    <th><?php echo JText::_('K2STORE_PLUGIN'); ?></th>
		                    <th><?php echo JText::_('K2STORE_GROUP'); ?></th>
		                    <th></th>
		                </tr>
		                <?php foreach ($status->plugins as $plugin): ?>
		                <tr class="row<?php echo(++$rows % 2); ?>">
		                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
		                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
		                    <td><strong><?php echo ($plugin['result'])?JText::_('K2STORE_INSTALLED'):JText::_('K2STORE_NOT_INSTALLED'); ?></strong></td>
		                </tr>
		                <?php endforeach; ?>
		                <?php endif; ?>
		            </tbody>
		        </table>
		    <?php
		    }

		    private function uninstallationResults($status)
		    {
		    $language = JFactory::getLanguage();
		    $language->load('com_k2store');
		    $rows = 0;
		 ?>
		        <h2><?php echo JText::_('K2STORE_REMOVAL_STATUS'); ?></h2>
		        <table class="adminlist">
		            <thead>
		                <tr>
		                    <th class="title" colspan="2"><?php echo JText::_('K2STORE_EXTENSION'); ?></th>
		                    <th width="30%"><?php echo JText::_('K2STORE_STATUS'); ?></th>
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                    <td colspan="3"></td>
		                </tr>
		            </tfoot>
		            <tbody>
		                <tr class="row0">
		                    <td class="key" colspan="2"><?php echo 'K2Store '.JText::_('K2STORE_COMPONENT'); ?></td>
		                    <td><strong><?php echo JText::_('K2STORE_REMOVED'); ?></strong></td>
		                </tr>
		                <?php if (count($status->modules)): ?>
		                <tr>
		                    <th><?php echo JText::_('K2STORE_MODULE'); ?></th>
		                    <th><?php echo JText::_('K2STORE_CLIENT'); ?></th>
		                    <th></th>
		                </tr>
		                <?php foreach ($status->modules as $module): ?>
		                <tr class="row<?php echo(++$rows % 2); ?>">
		                    <td class="key"><?php echo $module['name']; ?></td>
		                    <td class="key"><?php echo ucfirst($module['client']); ?></td>
		                    <td><strong><?php echo ($module['result'])?JText::_('K2STORE_REMOVED'):JText::_('K2STORE_NOT_REMOVED'); ?></strong></td>
		                </tr>
		                <?php endforeach; ?>
		                <?php endif; ?>

		                <?php if (count($status->plugins)): ?>
		                <tr>
		                    <th><?php echo JText::_('K2STORE_PLUGIN'); ?></th>
		                    <th><?php echo JText::_('K2STORE_GROUP'); ?></th>
		                    <th></th>
		                </tr>
		                <?php foreach ($status->plugins as $plugin): ?>
		                <tr class="row<?php echo(++$rows % 2); ?>">
		                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
		                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
		                    <td><strong><?php echo ($plugin['result'])?JText::_('K2STORE_REMOVED'):JText::_('K2STORE_NOT_REMOVED'); ?></strong></td>
		                </tr>
		                <?php endforeach; ?>
		                <?php endif; ?>
		            </tbody>
		        </table>
		    <?php
		    }

	}