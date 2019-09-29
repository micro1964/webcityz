<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filter.filterinput' );
jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

class K2StoreModelDownloads extends K2StoreModel {
	
	var $_files = array();
	
	
	function getItems() {
		
		$files = array();
		//initialise 
		$user_id = JFactory::getUser()->id;
		$db = JFactory::getDbo();
		
		//user id is empty, then return null
		if(empty($user_id)) return $files;
		
		//continue
		//first get all the orders for this user.
		
		$query = $db->getQuery(true);
		
		$query->select('*');
		$query->from('#__k2store_orders');
		$query->where('user_id='.$db->quote($user_id));
		//check the order state. It should be 1
		$query->where('order_state_id=1');
		$db->setQuery($query);
		$orders = $db->loadObjectList();
		// print_r($orders);
		//if no orders match the user id then return an emty array
		if(count($orders) < 1) return $files;
		
			$orderItems = array();
			$rows = array();
			//now loop through the orders to find out items in each order
			foreach ($orders as $order) {
				
				unset($query);
				$query = $db->getQuery(true);
				//we only need the product id. but select orderitem id as well. so that we can pass;
				$query->select('oi.product_id, oi.order_id,att.*');
				$query->from('#__k2store_orderitems AS oi');
				$query->join('INNER', '#__k2_attachments AS att ON oi.product_id=att.itemID');
				$query->where('oi.order_id='.$db->quote($order->order_id));
				$db->setQuery($query);
				$orderItems[] = $db->loadObjectList();
						
			}
		return $orderItems;
		
		}
		
		function getLink($file_id, $itemID, $order_id) {
			$session = JFactory::getSession();
			//echo $session->getToken();
			$link = JRoute::_('index.php?option=com_k2store&view=downloads&task=download&orderItem='.$file_id.'_'. JApplication::getHash($file_id.$session->getToken()));
			return $link;
		}
				
		protected function _getAttachments($product_id) {
			
			$db = JFactory::getDbo();
			$sql = "SELECT * FROM #__k2_attachments WHERE itemID =".$product_id;
			$db->setQuery( $sql );
			$attachments = $db->loadObjectList();
			return $attachments;			
		}
	
	function download() {
		jimport('joomla.filesystem.file');
		$session = JFactory::getSession();
		$mainframe = JFactory::getApplication();
        jimport('joomla.filesystem.file');
        $k2_params = JComponentHelper::getParams('com_k2');
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_k2/tables');
        
		$id = JRequest::getInt('orderItem');

        $attachment = JTable::getInstance('K2Attachment', 'Table');
        if ($mainframe->isSite())
        {
            $token = JRequest::getVar('orderItem');
			$check = JString::substr($token, JString::strpos($token, '_') + 1);
            
            if ($check !=  JApplication::getHash($id.$session->getToken()))
            {
                JError::raiseError(404, JText::_('K2_NOT_FOUND'));
            }
        }
        $attachment->load($id);
         $path = $k2_params->get('attachmentsFolder', NULL);
        if (is_null($path))
        {
            $savepath = JPATH_ROOT.DS.'media'.DS.'k2'.DS.'attachments';
        }
        else
        {
            $savepath = $path;
        }
        $file = $savepath.DS.$attachment->filename;

        if (JFile::exists($file))
        {
			require_once (JPATH_ADMINISTRATOR.'/components/com_k2/lib/class.upload.php');
            $handle = new Upload($file);
            if ($mainframe->isSite())
            {
                $attachment->hit();
            }
            $len = filesize($file);
            $filename = basename($file);
            ob_end_clean();
            JResponse::clearHeaders();
            JResponse::setHeader('Pragma', 'public', true);
            JResponse::setHeader('Expires', '0', true);
            JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
            JResponse::setHeader('Content-Type', $handle->file_src_mime, true);
            JResponse::setHeader('Content-Disposition', 'attachment; filename='.$filename.';', true);
            JResponse::setHeader('Content-Transfer-Encoding', 'binary', true);
            JResponse::setHeader('Content-Length', $len, true);
            JResponse::sendHeaders();
            echo JFile::read($file);

        }
        else
        {
            echo JText::_('K2_FILE_DOES_NOT_EXIST');
        }
        $mainframe->close();
		
	}
}