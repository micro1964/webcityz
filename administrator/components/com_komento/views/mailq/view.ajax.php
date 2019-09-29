<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( KOMENTO_ADMIN_ROOT . DIRECTORY_SEPARATOR . 'views.php');

class KomentoViewMailq extends KomentoAdminView
{
	function preview()
	{
		$id = JRequest::getInt( 'id' );
		$table = Komento::getTable( 'mailq' );
		$table->load( $id );

		$ajax = Komento::getAjax();
		$ajax->success( $table->body, $table->type );
		$ajax->send();
	}
}
