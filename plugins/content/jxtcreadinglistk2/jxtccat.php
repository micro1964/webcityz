<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

jimport('joomla.form.formfield');

class JFormFieldJxtccat extends JFormField {

	protected	$_name = 'Jxtccat';

	protected function getInput()	{
		$db	=JFactory::getDBO();
		$q = "SELECT c.id, c.name FROM #__k2_categories as c WHERE c.trash = '0' AND c.published = '1' ORDER BY c.name";
		$db->setquery($q);
		$result=$db->loadObjectList();
		if (empty($result)) {
			return '<input type="text" class="inputbox" disabled="disabled" readonly="readonly" value="No categories found." />';
		}
    array_unshift($result,(object)array('id'=>0,'name'=>'ALL CATEGORIES'));
		$size = count($result);
		//$size = ceil($size/10);
		if ($size < 5) $size = 5;
		if ($size > 20) $size = 20;
		return JHTML::_('select.genericlist', $result, $this->name . '[]', 'class="inputbox" multiple="multiple" size="' . $size . '"', 'id', 'name', $this->value);
	}
}