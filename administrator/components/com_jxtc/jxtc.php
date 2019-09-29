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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_templates')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$view = JRequest::getCmd('view');
$task = JRequest::getCmd('task');

jimport('joomla.application.component.controller');

if (empty($view)) {
	require_once( JPATH_COMPONENT.'/controllers/default.php' );
} else {
	require_once( JPATH_COMPONENT.'/controllers/'.$view.'.php' );
}
$controller = new xtcController();
$controller->execute( $task );
$controller->redirect();
?>