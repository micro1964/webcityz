<?php
/*
	JoomlaXTC Komento Wall

	version 1.3.0

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

class JFormFieldJxtckcomponent extends JFormField {

    protected $_name = 'Jxtckcomponent';

    protected function getInput() {
        
        if (!is_dir(JPATH_ROOT . '/components/com_komento'))
            return JText::_('To use JXTC Komento Wall you must install Komento on your site too, you can get it <a href="http://stackideas.com/komento.html" target="_blank">here</a>');

        // Load constants and helpers
        require_once( JPATH_ROOT . '/components/com_komento/bootstrap.php' );

        // Require the base controller
        require_once( KOMENTO_ADMIN_ROOT . '/controllers/controller.php' );

        $result = Komento::getHelper('components')->getAvailableComponents();
        
        $components = array();
        foreach ($result as $item)
            $components[] = JHTML::_('select.option', $item, JText::_('COM_KOMENTO_' . strtoupper($item)));
        
        array_unshift($components, (object) array('value' => 0, 'text' => 'ALL COMPONENTS'));
        
        $size = count($components);
	if ($size > 20)
	    $size = 20;
	else
	    $size = 5;
	return JHTML::_('select.genericlist', $components, $this->name.'[]', 'multiple="multiple" size="' . $size . '"', 'value', 'text', $this->value, $this->id);
    }

}