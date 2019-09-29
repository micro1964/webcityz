<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.controller' );
JTable::addIncludePath( JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/tables' );
require_once JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/helper.php';

class xtcController extends JControllerLegacy {

	/* POST AN ITEM FLAG CHANGE */
	function post() {
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$template = $app->getTemplate(true)->template;
		$userid = JFactory::getUser()->id;
		
		@list($id,$component,$plugin) = explode('|',base64_decode(JRequest::getVar('code')));
		settype($id,'integer');
		
		if (!$id || !$component || !$plugin || !$userid) { return; }
		
		$row = JTable::getInstance('readinglist', 'Table');
		$row->load(array('user_id'=>$userid,'item_id'=>$id,'component'=>$component)); // Check if already stored

		if (empty($row->id)) { // Add new entry
			$row->published = 1;
			// $row->checked_out = 0;
			// $row->checked_out_time = '0000-00-00 00:00:00';
			$row->ordering = $row->getNextOrder('user_id='.$userid );
			$row->item_id = $id;
			$row->user_id = $userid;
			$row->component = $component;
			$row->entry_date = date("Y-m-d H:i:s");
			$row->read = 0;
			$row->checkin();
			if (!$row->store()) {
				echo $row->getError();
			} else {
				$defaultFile = JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/remove.php';
				$overrideFile = JPATH_ROOT.'/templates/'.$template.'/html/'.$plugin.'/remove.php';
				ob_start();
				require (JFile::exists($overrideFile) ? $overrideFile : $defaultFile);
				$buttonhtml = ob_get_contents();
				ob_end_clean();
			}
		} else { // Change entry
			if ($row->published == 0) {
				$row->published = 1;
				$defaultFile = JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/remove.php';
				$overrideFile = JPATH_ROOT.'/templates/'.$template.'/html/'.$plugin.'/remove.php';
				$markupFile = JFile::exists($overrideFile) ? $overrideFile : JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/remove.php';
			}
			else {
				$row->published = 0;
				$overrideFile = JPATH_ROOT.'/templates/'.$template.'/html/'.$plugin.'/add.php';
				$markupFile = JFile::exists($overrideFile) ? $overrideFile : JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/add.php';
			}
				
			if (!$row->store()) {
				echo $row->getError();
			} else {
				ob_start();
				require $markupFile;
				$buttonhtml = ob_get_contents();
				ob_end_clean();
			}
		}

		// ajax return
		$count = jxtcrlhelper::getReadingListCount();
		echo json_encode(array($count,$buttonhtml));
	}
	
	/* DELETE A USER ENTRY */
	function delete()	{

		$id = JRequest::getInt('id');
		$catid = JRequest::getVar('catid');	$catid = $catid === '' ? -1 : (int) $catid;
		$userid = JFactory::getUser()->id;
		$Itemid = JRequest::getInt('Itemid');
		$msg = '';

		$row = JTable::getInstance('readinglist', 'Table');
		$row->load(array('user_id'=>$userid,'item_id'=>$id)); // Check if already stored
		if (!empty($row->id)) {
			if (!$row->delete()) {
				echo $row->getError();
			} else {
				$msg = JText::_('RL_ITEMDELETED');
			}
		}

//		$link = 'index.php?option=com_jxtcreadinglist&view=readinglist&cid='.$catid.'&Itemid='.$Itemid;
//		$this->setRedirect( $link, $msg );

		parent::display();
	}
}