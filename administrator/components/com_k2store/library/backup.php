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

class K2StoreBackup {

	var $tables = array();
	var $output;

	// Backup the table and save it to a sql file
	function backup($db)
	{

		$prefix = $db->getPrefix();
		$filename='k2store_db_backup_'. date("d-m-Y-H-i-s") .'_file_'.time().'.sql';
		//get all tables
		$all_tables = $db->getTableList();

		$tables = array($prefix.'k2store_address',
				$prefix.'k2store_mycart',
				$prefix.'k2store_orderinfo',
				$prefix.'k2store_orderitemattributes',
				$prefix.'k2store_orderitems',
				$prefix.'k2store_orders',
				$prefix.'k2_items',
				$prefix.'k2store_productattributeoptions',
				$prefix.'k2store_productattributes',
				$prefix.'k2store_productquantities',
				$prefix.'k2store_shippingmethods',
				$prefix.'k2store_shippingrates',
				$prefix.'k2store_taxprofiles',
				$prefix.'k2store_countries',
				$prefix.'k2store_zones'
		);


		$data = "";

		$tables = is_array($tables) ? $tables : explode(',',$tables);
		//print_r($tables); exit;
		// Cycle through each provided table
		foreach($tables as $table) {

			//check if the table exisits before taking a backup
			if(in_array($table, $all_tables)){
				//lock table
				$db->lockTable($table);
				$this->_dump_table($table, $db);
				$data .= $this->output;
			}
		}
		$this->_writeOutput($filename, $data);
		$db->unlockTables();
		return true;
	}



	private function _dump_table($tablename, $db) {
		$this->output = "";
		$this->_get_table_structure($tablename, $db);
		$this->_list_values($tablename, $db);
	}

	private function _get_table_structure($tablename, $db) {

		$sql = 'SHOW CREATE TABLE '. $tablename;
		$db->setQuery( $sql);
		$rows = $db->loadAssocList();
		$this->output .= "\n\n-- Dumping structure for table: $tablename\n\n";
		$this->output .= "\n". $rows[0]['Create Table']. ';';
	}


	private function _list_values($tablename, $db) {

		$query = "SELECT * FROM $tablename";
		$db->setQuery($query);
		$rows = $db->loadRowList();
		if(count($rows)) {
		//$sql = mysql_query("SELECT * FROM $tablename");
		$this->output .= "\n\n-- Dumping data for table: $tablename\n\n";

		foreach($rows as $row) {
		//	print_r($row); exit;
		$broj_polja = count($row);

			$this->output .= "INSERT INTO `$tablename` VALUES(";
			$buffer = '';
			for ($i=0;$i < $broj_polja;$i++) {
				$vrednost = $row[$i];
				if (!is_integer($vrednost)) {
					$vrednost = "'".addslashes($vrednost)."'";
				}
				$buffer .= $vrednost.', ';
			}
			$buffer = substr($buffer,0,count($buffer)-3);
			$this->output .= $buffer . ");\n";
		}
		}
	}

	private	function _writeOutput($filename, $data) {

		// Save the sql file
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.file');

		$params = JComponentHelper::getParams('com_k2store');
		$default_save_path = JPATH_ADMINISTRATOR.'/components/com_k2store/backup';
		$save_path = $params->get('backupfolderpath', $default_save_path);

		$full_file_path = $save_path.'/'.$filename;
		if(!JFile::exists($full_file_path)){
			JFile::write(JPath::clean($save_path).'/'.$filename, $data);
		}
	}

}
