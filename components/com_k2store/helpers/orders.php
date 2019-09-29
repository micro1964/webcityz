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
defined('_JEXEC') or die('Restricted access');

class K2StoreOrdersHelper {


	public static function sendUserEmail($user_id, $order_id, $payment_status, $order_status, $order_state_id)
	{
		$mainframe =JFactory::getApplication();
		jimport('joomla.filesystem.file');
		// grab config settings for sender name and email
		$config     = JComponentHelper::getParams('com_k2store');
		$k2params = JComponentHelper::getParams('com_k2');

		//mail function
		$mailfrom   = $config->get( 'emails_defaultemail', $mainframe->getCfg('mailfrom') );
		$fromname   = $config->get( 'emails_defaultname', $mainframe->getCfg('fromname') );
		$sitename   = $config->get( 'sitename', $mainframe->getCfg('sitename') );
		$siteurl    = $config->get( 'siteurl', JURI::root() );

		//now get the order table's id based on order id
		$id = self::_getOrderKey($order_id);

		//now get the receipient
		$recipient = self::_getRecipient($id);

		if($user_id && empty($recipient->billing_first_name)) {
			$recipient->name = JFactory::getUser($user_id)->name;
		} else {
			$recipient->name = $recipient->billing_first_name.' '.$recipient->billing_last_name;
		}

		$html = self::_getHtmlFormatedOrder($id, $user_id);

		$mailer =JFactory::getMailer();
		$mode = 1;

		$subject = JText::sprintf('K2STORE_ORDER_USER_EMAIL_SUB', $recipient->name, $sitename);

		$msg = '';
		$msg .= $html;

		  //send attachments as well

        //allow_attachment_downloads

        //attachements

        //send attachments, only when the order state is confirmed and attachments are allowed
        if ($config->get('allow_attachment_downloads') && $order_state_id == 1 )  {

	        $attachments = self::getAttachments($order_id);

	        $path = $k2params->get('attachmentsFolder', NULL);
			if (is_null($path)) {
            $savepath = JPATH_ROOT.DS.'media'.DS.'k2'.DS.'attachments';
			} else {
            $savepath = $path;
			}


			if (count($attachments)>0) {
				$msg .='<br />----------------------------------------------------------------------------------------------------------- <br />';
				$msg .= JText::_('K2STORE_ATTACHED_FILES_TO_THIS_EMAIL').': <br />';
				foreach($attachments as $attachment) {
					$myfile = trim($attachment->filename);
					$att = $savepath.DS.$myfile;
					if (JFile::exists($att)) {
					$msg .= 'File: '.$myfile.'<br />';
					$mailer->addAttachment($att);
					}
				}//foreach
			}//if count

		}

		$admin_emails = trim($config->get('admin_email')) ;
		$admin_emails = explode(',',$admin_emails ) ;

		//send email
		if ($recipient)
		{
			$mailer->addRecipient($recipient->user_email);
			//   $mailer->addCC( $config->get('admin_email'));
			$mailer->addCC( $admin_emails );
			$mailer->setSubject( $subject );
			$mailer->setBody($msg);
			$mailer->IsHTML($mode);
			$mailer->setSender(array( $mailfrom, $fromname ));
			$mailer->send();
		}

		return true;
	}



	public static function _getUser($uid)
	{

		$db =JFactory::getDBO();
		$q = "SELECT name, email FROM #__users "
		. "WHERE id = {$uid}"
		;

		$db->setQuery($q);
		$user_email = $db->loadObject();

		if ($error = $db->getErrorMsg()) {
			JError::raiseError(500, $error);
			return false;
		}

		return $user_email;
	}


	public static function _getRecipient($orderpayment_id) {


		$db =JFactory::getDBO();
		$q = "SELECT user_email,user_id,billing_first_name,billing_last_name FROM #__k2store_orderinfo"
		. " WHERE orderpayment_id = {$orderpayment_id}"
		;
		$db->setQuery($q);
		$user_email = $db->loadObject();

		if ($error = $db->getErrorMsg()) {
			JError::raiseError(500, $error);
			return false;
		}

		return $user_email;
	}


	public static function _getOrderKey($order_id) {

		$db = JFactory::getDBO();
		$query = 'SELECT id FROM #__k2store_orders WHERE order_id='.$db->Quote($order_id);
		$db->setQuery($query);
		return $db->loadResult();
	}


	public static function _getHtmlFormatedOrder($id, $user_id) {

		$app = JFactory::getApplication();
		$k2storeparams   = JComponentHelper::getParams('com_k2store');


		$sitename   = $k2storeparams->get( 'sitename', $app->getCfg('sitename') );
		$siteurl    = $k2storeparams->get( 'siteurl', JURI::root() );

		$html = ' ';

		JLoader::register( "K2StoreViewOrders", JPATH_SITE."/components/com_k2store/views/orders/view.html.php" );

		$config = array();
		$config['base_path'] = JPATH_SITE."/components/com_k2store";
			// finds the default Site template
			$db = JFactory::getDBO();
			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home=1";
			$db->setQuery( $query );
			$template = $db->loadResult();

			jimport('joomla.filesystem.file');
			if (JFile::exists(JPATH_SITE.'/templates/'.$template.'/html/com_k2store/orders/orderemail.php'))
			{
				// (have to do this because we load the same view from the admin-side Orders view, and conflicts arise)
				$config['template_path'] = JPATH_SITE.'/templates/'.$template.'/html/com_k2store/orders';
			}

			if(!empty($order->customer_language)) {
				$lang = JFactory::getLanguage();
				$lang->load('com_k2store', JPATH_SITE, $order->customer_language);
			}

		$view = new K2StoreViewOrders( $config );
		$view->addTemplatePath(JPATH_SITE.'/templates/'.$template.'/html/com_k2store/orders');
		require_once(JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'models'.DS.'orders.php');
		$model =  new K2StoreModelOrders();
		//lets set the id first
		$model->setId($id);

		$order = $model->getTable( 'orders' );
		$order->load( $model->getId() );
		$orderitems = $order->getItems();
		$row = $model->getItem();

		if(!$order->user_id) {
			$isGuest = true;
		}else{
			$isGuest=false;
		}

		$view->set( '_controller', 'orders' );
		$view->set( '_view', 'orders' );
		$view->set( '_doTask', true);
		$view->set( 'hidemenu', false);
		$view->setModel( $model, true );
		$view->assign( 'row', $row );
		$show_tax = $k2storeparams->get('show_tax_total');
		$view->assign( 'show_tax', $show_tax );
		foreach ($orderitems as &$item)
		{
			$item->orderitem_price = $item->orderitem_price + floatval( $item->orderitem_attributes_price );
			$taxtotal = 0;
			if($show_tax)
			{
				$taxtotal = ($item->orderitem_tax / $item->orderitem_quantity);
			}
			$item->orderitem_price = $item->orderitem_price + $taxtotal;
			$item->orderitem_final_price = $item->orderitem_price * $item->orderitem_quantity;
			$order->order_subtotal += ($taxtotal * $item->orderitem_quantity);
		}

		$view->assign( 'order', $order );
		$view->assign( 'isGuest', $isGuest);
		$view->assign( 'sitename', $sitename);
		$view->assign( 'siteurl', $siteurl);
		$view->assign( 'params', $k2storeparams);
		$view->setLayout( 'orderemail' );

		//$this->_setModelState();
		ob_start();
		$view->display();
		$html .= ob_get_contents();
		ob_end_clean();
		return $html;
	}


	public static function getAddress($user_id) {

		$db = JFactory::getDBO();
		$query = 'SELECT tbl.*,c.country_name,z.zone_name'
		.' FROM #__k2store_address AS tbl'
		.' LEFT JOIN #__k2store_countries AS c ON tbl.country_id=c.country_id'
		.' LEFT JOIN #__k2store_zones AS z ON tbl.zone_id=z.zone_id'
		.' WHERE tbl.user_id='.(int) $user_id;
		$db->setQuery($query);
		return $db->loadObject();
	}


	public static function getAttachments($order) {

		global $mainframe;
		$db =JFactory::getDBO();
		$all_attachments = Array();

		//get all the items for this order
		$query = "SELECT * FROM #__k2store_orderitems WHERE order_id=".$order;
		$db->setQuery( $query );
		$items = $db->loadObjectList();
		//if no items found then exit now!
		if ($items==0) {
			return $all_attachments;  //return empty array
		}

		//loop through items, generating a list of attachments
		foreach($items as $item) {
			$sql = "SELECT * FROM #__k2_attachments WHERE itemID =".$item->product_id;
			$db->setQuery( $sql );
			$attachments = $db->loadObjectList();
			//accumulate all attachments into one big array
			$all_attachments = array_merge($all_attachments, (array)$attachments);
		}//foreach

		//ok, all done - return the resulting array of attachments
		return $all_attachments;
	}//function getOrderAttachments

	public static function isJson($string) {
		json_decode($string);
		if(function_exists('json_last_error')) {
			return (json_last_error() == JSON_ERROR_NONE);
		}
		return true;
	}

}