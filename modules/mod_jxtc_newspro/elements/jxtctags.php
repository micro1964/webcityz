<?php

/*
	JoomlaXTC Deluxe News Pro

	version 3.66.0

	Copyright (C) 2008-2017 Monev Software LLC.	All Rights Reserved.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.

	See COPYRIGHT.txt for more information.
	See LICENSE.txt for more information.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;


jimport('joomla.form.formfield');

class JFormFieldJxtctags extends JFormField {

    protected $_name = 'Jxtctags';

    protected function getInput() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('a.id AS value, a.title AS text, a.level, a.published');
        $query->from('#__tags AS a');
        $query->join('LEFT', $db->quoteName('#__tags') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

        $query->where($db->quoteName('a.alias') . ' <> ' . $db->quote('root'));

        // Filter on the published state
            $query->where('a.published = 1');

        $query->group('a.id, a.title, a.level, a.lft, a.rgt, a.parent_id, a.published');
        $query->order('a.lft ASC');

        // Get the options.
        $db->setQuery($query);

        try{
            $result = $db->loadObjectList();
            array_unshift($result, (object) array('value' => 0, 'text' => 'ANY TAG'));
            $size = count($result);
            $size = ceil($size / 10);
            if ($size < 5)
                $size = 5;
            if ($size > 20)
                $size = 20;
            return JHTML::_('select.genericlist', $result, $this->name.'[]', 'multiple="multiple" size="' . $size . '" class="inputbox"', 'value', 'text', $this->value, $this->id);
        }
        catch (RuntimeException $e){
            return JText::_("To use tags you must have at less Joomla 3.1.0");
        }
    }

}