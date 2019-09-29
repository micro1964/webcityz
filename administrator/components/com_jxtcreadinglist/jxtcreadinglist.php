<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

JTable::addIncludePath( JPATH_COMPONENT.'/tables' );

$view = JRequest::getCmd('view');

if (empty($view)) {
	$view = 'entries';
	JRequest::setVar('view',$view);
}

require_once( JPATH_COMPONENT.'/controllers/'.$view.'.php' );

$controller = new xtcController();
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();