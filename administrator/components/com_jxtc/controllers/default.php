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

jimport( 'joomla.application.component.controller' );

class xtcController extends JControllerLegacy {
	function __construct( $default = array())	{
		parent::__construct( $default );

		$this->registerTask('apply', 'save');
		$this->registerTask('save2copy', 'save');
	}

	function display($cachable = false, $urlparams = false) {
		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if(empty($view)) {
			JRequest::setVar('view', 'about');
		};

		// check for var limits
		$varCount = count($_POST,COUNT_RECURSIVE);
		$varLimit = ini_get('max_input_vars');
		$varSug = 5000;
		if ($varCount == $varLimit && $varSug > $varLimit) {
			JFactory::getApplication()->enqueueMessage("Your PHP 'max_input_vars' parameter is currently set to $varLimit. A minimum value of $varSug is recommended to save template parameters properly.", 'warning');
		}

		parent::display($cachable, $urlparams);
	}

	function save() {
		// This should be put in an extension plugin when available
		JRequest::checkToken() or jexit( 'Invalid Token' );

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$lang = JFactory::getLanguage();
		$lang->load('com_templates');
		$post	= JRequest::get( 'post' );
		$filter = JFilterInput::getInstance();
		$template = $filter->clean($post['jform']['template'],'alnum');
		$db		= JFactory::getDbo();
		$user	= JFactory::getUser();

		if (empty($template)) { jexit('Invalid call'); }

		// check for var limits
		$varCount = count($_POST,COUNT_RECURSIVE);
		$varLimit = ini_get('max_input_vars');
		$varSug = 5000;
		if ($varCount == $varLimit && $varSug > $varLimit) {
			JFactory::getApplication()->enqueueMessage("Your PHP 'max_input_vars' parameter is currently set to $varLimit. A minimum value of $varSug is recommended to save template parameters properly.", 'warning');
		}

		// Parse standard jform and Joomla data using the original Joomla component for maximum compatibility
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_templates/tables');
		require_once JPATH_ROOT.'/administrator/components/com_templates/models/style.php';
		$model	= new TemplatesModelStyle();
		
		$data = $post['jform'];

		if (JRequest::getVar('task') == 'save2copy') { // Force new ID
			$data['id'] =0;
			$nul = $model->getState('style.id');
			$model->setState('style.id',0);
		}

		if ($model->save($data)) { // If Joomla save succeeds, do XTC

			$id = $model->getState('style.id');	// Get style ID from model

			// Parse joomla parameters into params.ini
			foreach (array_keys($post['jform']['params']) as $param) {
				$value = is_array($post['jform']['params'][$param]) ? implode('|',$post['jform']['params'][$param]) : $post['jform']['params'][$param];
				$parameters[] = $param.'='.$value;
			}
	
			// Parse xtc parameters into params.ini
			foreach (array_keys($post['xtcparam']) as $prefix) {
				foreach (array_keys($post['xtcparam'][$prefix]) as $group) {
					foreach (array_keys($post['xtcparam'][$prefix][$group]) as $param) {
						$value = is_array($post['xtcparam'][$prefix][$group][$param])
							? implode('|',$post['xtcparam'][$prefix][$group][$param])
							: $post['xtcparam'][$prefix][$group][$param];
						$parameters[] = '{'.$prefix.'+'.$group.'}'.$param.'='.$value;
					}
				}
			}

			// Save params.ini
			$parameterFile = JPATH_ROOT.'/templates/'.$template.'/params_'.$id.'.ini';
			$string = implode("\n",$parameters);
			if (!JFile::write($parameterFile, $string)) {
				jexit('Error writing parameters file.');
			}

			// Return to template component
			$msg = JText::_('COM_TEMPLATES_STYLE_SAVE_SUCCESS');
			switch ($this->getTask()) {
				case 'save': // go back to main page
					$this->setRedirect('index.php?option=com_templates',$msg);
				break;
				case 'save2copy': // we need to add ID to editable ID list first
					$app	= JFactory::getApplication();
					$ids = $app->getUserState('com_templates.edit.style.id');
					$ids[] = $id;
					$app->setUserState('com_templates.edit.style.id',$ids);
				case 'apply': // go to edit page
					$this->setRedirect('index.php?option=com_templates&view=style&layout=edit&id='.$id,$msg);
				break;
			}
		}
	}
	
}