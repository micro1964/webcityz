<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

$xmlFile = JPATH_COMPONENT.'/jxtcreadinglist.xml';
$xml = simplexml_load_file($xmlFile);

JToolBarHelper::title('JoomlaXTC Reading List');
JToolBarHelper::preferences('com_jxtcreadinglist');

JSubMenuHelper::addEntry( JText::_('RL_MENU_ENTRIES'), 'index.php?option=com_jxtcreadinglist&view=entries', false );
JSubMenuHelper::addEntry( JText::_('RL_MENU_ABOUT'), 'index.php?option=com_jxtcreadinglist&view=about', true );
?>
Version: <b><?php echo $xml->version; ?></b>
<br/><br/>
<?php echo $xml->copyright; ?>
<br/><br/>
For more information, updates and documentation visit <a href="http://www.joomlaxtc.com" alt="JoomlaXTC">www.joomlaxtc.com</a>