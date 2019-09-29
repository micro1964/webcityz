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

class plgContentjxtcreadinglist extends JPlugin {

	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onContentPrepare( $context, &$article, &$params, $limitstart=0 ) {

		// fail checks
		@list($component,$view) = @explode('.',$context);
		if ($component != 'com_content' || !isset($article->id)) { return; }

		$integration = $this->params->get('integration',3);

		$_GLOBALS['rlcatid'] = (array) $this->params->get('catid',0);	// share with the helper/walls
		if ($_GLOBALS['rlcatid'][0] && !in_array($article->catid,$_GLOBALS['rlcatid'])) { return; }
		
		if (JFactory::getUser()->guest) {	// guest users
			$button = $this->params->get('guestbutton') ? jxtcrlHelper::getGuestButton($this->params->get('guesturl'),'jxtcreadinglist') : '';
		}
		else {	// registered users
			$button = jxtcrlHelper::getPluginButton($article->id,$component,'jxtcreadinglist');
		}

		if (JRequest::getCmd('option') != 'com_jxtcreadinglist') {	// do not add button within RL component
			switch ($this->params->get('placement','b')) {
				case 't':
					if (!empty($article->text) && ($integration == 2 || $integration == 3)) {
						$article->text = str_ireplace('{readinglist}','',$article->text);
						$article->text = $button.$article->text;
					}
					if (!empty($article->introtext) && ($integration == 1 || $integration == 3)) {
						$article->introtext = str_ireplace('{readinglist}','',$article->introtext);
						$article->introtext = $button.$article->introtext;
					}
				break;
				case 'b':
					if (!empty($article->text) && ($integration == 2 || $integration == 3)) {
						$article->text = str_ireplace('{readinglist}','',$article->text);
						$article->text .= $button;
					}
					if (!empty($article->introtext) && ($integration == 1 || $integration == 3)) {
						$article->introtext = str_ireplace('{readinglist}','',$article->introtext);
						$article->introtext .= $button;
					}
				break;
			}
		}
		else {	// tricked to hide button on RL component
			$button = '';
		}

		// Use tag if present
		if (!empty($article->text)) $article->text = str_ireplace('{readinglist}',$button,$article->text);
		if (!empty($article->introtext)) $article->introtext = str_ireplace('{readinglist}',$button,$article->introtext);
	}
}