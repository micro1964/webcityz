<?php

// No direct access
defined('_JEXEC') or die;

/**
 *
 * @package		Joomla.Administrator
 * @subpackage	com_k2store
 * @since		3.2
 */
class TableOption extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__k2store_options', 'option_id', $db);
	}
	
	function check()
	{
		if(empty($this->option_unique_name)) {
			print_r($this);
			exit;
			$this->setError( JText::_("K2STORE_OPTION_UNIQUE_NAME_MUST_HAVE_A_TITLE") );
			return false;
		}
		/** check for existing name */
		$query = 'SELECT option_id FROM #__k2store_options WHERE option_unique_name = '.$this->_db->Quote($this->option_unique_name);
		$this->_db->setQuery($query);
		
		$xid = (int) $this->_db->loadResult();
		if ($xid && $xid != (int) $this->option_id) {
			$this->setError(JText::_('K2STORE_OPTION_WARNING_SAME_NAME'));
			return false;
		}
		
		if(empty($this->option_name)) {
			$this->setError( JText::_("K2STORE_OPTION_NAME_MUST_HAVE_A_TITLE") );
			return false;
		}

		return true;
	}
	
	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param	array		Named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see		JTable:bind
	 * @since	1.5
	 */

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;	// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `'.$this->_tbl.'`' .
			' SET `state` = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
	
}
