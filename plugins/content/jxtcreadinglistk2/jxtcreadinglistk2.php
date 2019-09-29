<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

jimport('joomla.plugin.plugin');
require_once JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/helper.php';

class plgContentjxtcreadinglistk2 extends JPlugin {

	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onContentPrepare( $context, &$item, &$params, $limitstart=0 ) {

		// fail checks
		@list($component,$view) = @explode('.',$context);
		if ($component != 'com_k2' || ($view != 'item' && $view != 'itemlist') || !isset($item->id) || empty($item->text)) { return; }

		$integration = $this->params->get('integration',3);

		$_GLOBALS['rlcatidk2'] = (array) $this->params->get('catid',0);	// share with the helper/walls
		if ($_GLOBALS['rlcatidk2'][0] && !in_array($item->catid,$_GLOBALS['rlcatidk2'])) { return; }
		
		if (JFactory::getUser()->guest) {	// guest users
			$button = $this->params->get('guestbutton') ? jxtcrlHelper::getGuestButton($this->params->get('guesturl'),'jxtcreadinglistk2') : '';
		}
		else {	// registered users
			$button = jxtcrlHelper::getPluginButton($item->id,$component,'jxtcreadinglistk2');
		}

		if (JRequest::getCmd('option') != 'com_jxtcreadinglist') {	// do not add button within RL component
			@list($introtext,$fulltext) = explode('{K2Splitter}', $item->text);
			switch ($this->params->get('placement','b')) {
				case 't':
					if (!empty($fulltext) && ($integration == 2 || $integration == 3)) {
						$fulltext = str_ireplace('{readinglist}','',$fulltext);
						$fulltext = $button.$fulltext;
					}
					if (!empty($introtext) && ($integration == 1 || $integration == 3)) {
						$introtext = str_ireplace('{readinglist}','',$introtext);
						$introtext = $button.$introtext;
					}
				break;
				case 'b':
					if (!empty($fulltext) && ($integration == 2 || $integration == 3)) {
						$fulltext = str_ireplace('{readinglist}','',$fulltext);
						$fulltext .= $button;
					}
					if (!empty($introtext) && ($integration == 1 || $integration == 3)) {
						$introtext = str_ireplace('{readinglist}','',$introtext);
						$introtext .= $button;
					}
				break;
			}
			$item->text = $fulltext ? $introtext.'{K2Splitter}'.$fulltext : $introtext;
		}
		else {	// tricked to hide button on RL component
			$button = '';
		}

		// Use tag if present
		$item->text = str_ireplace('{readinglist}',$button,$item->text);
	}
}