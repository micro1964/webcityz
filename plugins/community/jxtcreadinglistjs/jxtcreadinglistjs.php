<?php
/*
  JoomlaXTC Reading List

  version 1.3.1

  Copyright (C) 2012,2013 Monev Software LLC.	All Rights Reserved.
	
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

	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;

require_once JPATH_ROOT.'/components/com_community/libraries/core.php';

if(!class_exists('plgCommunityjxtcreadinglistjs')) {
	class plgCommunityjxtcreadinglistjs extends CApplications {
		var $name 		= 'readinglist';
		var $_name		= 'readinglist';
		var $_path		= '';
		var $_user		= '';
		var $_my		= '';
	
		function onProfileDisplay() {
		
			$this->loadUserParams();

			$newwindow = $this->userparams->get('newwindow', 0);
			$maxitems = $this->userparams->get('maxitems', 5);

			$cache = JFactory::getCache('community');
			$callback = array($this, '_buildHTML');
			$content = $cache->call($callback, $newwindow, $maxitems);
			
			return $content;
		}
		
		function _buildHTML($newwindow, $maxitems) {

			require_once JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/helper.php';
			$items = jxtcrlhelper::getReadingList(0);

			$app = JFactory::getApplication('site');
			$defaultFile = JPATH_ROOT.'/plugins/community/jxtcreadinglistjs/jxtcreadinglistjs/tmpl/default.php';
			$overrideFile = JPATH_ROOT.'/templates/'.$app->getTemplate(true)->template.'/html/jxtcreadinglistjs/default.php';

			ob_start();
			require (JFile::exists($overrideFile) ? $overrideFile : $defaultFile);
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}
	}
}


