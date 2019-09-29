<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

require_once JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/helper.php';

$option = JRequest::getCmd('option');
$cid = $option == 'com_jxtcreadinglist' ? JRequest::getInt('cid',-1) : 0;

$items = jxtcrlhelper::getReadingList(0);

require JModuleHelper::getLayoutPath('mod_jxtc_readinglist', $params->get('layout', 'default'));