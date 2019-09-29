<?php
/***********************************************************************************
************************************************************************************
***                                                                              ***
***   XTC Template Framework helper   3.4.0                                      ***
***                                                                              ***
***   Copyright (c) 2010-2017                                                    ***
***   Monev Software LLC,  All Rights Reserved                                   ***
***                                                                              ***
***   This program is free software; you can redistribute it and/or modify       ***
***   it under the terms of the GNU General Public License as published by       ***
***   the Free Software Foundation; either version 2 of the License, or          ***
***   (at your option) any later version.                                        ***
***                                                                              ***
***   This program is distributed in the hope that it will be useful,            ***
***   but WITHOUT ANY WARRANTY; without even the implied warranty of             ***
***   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              ***
***   GNU General Public License for more details.                               ***
***                                                                              ***
***   You should have received a copy of the GNU General Public License          ***
***   along with this program; if not, write to the Free Software                ***
***   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA   ***
***                                                                              ***
***   See COPYRIGHT.txt for more information.                                    ***
***   See LICENSE.txt for more information.                                      ***
***                                                                              ***
***   www.joomlaxtc.com                                                          ***
***                                                                              ***
************************************************************************************
***********************************************************************************/

defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class xtcViewFiles extends JViewLegacy {

	public function display($tpl=null)	{

		$db = JFactory::getDBO();
		$db->setQuery( 'SELECT template FROM #__template_styles WHERE id='.JRequest::getInt('id') );
		$template = $db->loadResult();
		if ($db->getErrorNum()) {  JError::raiseError(500, $db->getError() );  }

		$path = 'templates/'.$template.'/images/'.JFile::makeSafe(JRequest::getVar('f'));

		$model = $this->getModel();
    $files = $model->getFiles($path);

		$this->assign('id', JRequest::getInt('id'));
		$this->assign('f', JRequest::getVar('f'));
		$this->assign('fld', JRequest::getVar('fld'));
		$this->assignRef('path', $path);
		$this->assignRef('files', $files);

		parent::display($tpl);
	}
}
