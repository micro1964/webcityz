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

class JFormFieldJxtccategory extends JFormField {

	protected	$_name = 'Jxtccategory';

	protected function getInput()	{
		$categories = JHtml::_('category.options', 'com_content');
		array_unshift($categories, JHTML::_('select.option',  '0', JText::_('ALL CATEGORIES')));

    $size = count($categories);

    if ($size <= 5) $size = 5;
		if ($size > 20) $size = 20;

		$category = JHTML::_(
			'select.genericlist',
			$categories,
			$this->name.'[]',
			'class="inputbox" multiple="multiple" size="'.$size.'"',
			'value', 'text',
			$this->value
		);

		return $category;
	}
}