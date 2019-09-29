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


/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
class K2StoreItem
{

		/**
	 *
	 * @return unknown_type
	 */
	function display( $articleid )
	{
		$html = '';

		$item= K2StoreItem::_getK2Item($articleid);
		// Return html if the load fails
		if (!$item->id)
		{
			return $html;
		}

		$item->title = JFilterOutput::ampReplace($item->title);


		//import plugins

		$item->text = '';

		$item->text = $item->introtext . chr(13).chr(13) . $item->fulltext;

		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$params		=JComponentHelper::getParams('com_k2');
		$dispatcher = JDispatcher::getInstance();

		// process k2 plugins

		//Init K2 plugin events
		$item->event = new JObject();
		$item->event->K2BeforeDisplay = '';
		$item->event->K2AfterDisplay = '';
		$item->event->K2AfterDisplayTitle = '';
		$item->event->K2BeforeDisplayContent = '';
		$item->event->K2AfterDisplayContent = '';
		$item->event->K2CommentsCounter = '';


		JPluginHelper::importPlugin('k2');
		$results = $dispatcher->trigger('onK2BeforeDisplay', array(&$item, &$params, $limitstart));
		$item->event->K2BeforeDisplay = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onK2AfterDisplay', array(&$item, &$params, $limitstart));
		$item->event->K2AfterDisplay = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onK2AfterDisplayTitle', array(&$item, &$params, $limitstart));
		$item->event->K2AfterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onK2BeforeDisplayContent', array(&$item, &$params, $limitstart));
		$item->event->K2BeforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onK2AfterDisplayContent', array(&$item, &$params, $limitstart));
		$item->event->K2AfterDisplayContent = trim(implode("\n", $results));

		$dispatcher->trigger('onK2PrepareContent', array(&$item, &$params, $limitstart));
		$item->introtext = $item->text;


		// Use param for displaying article title
		$k2store_params = JComponentHelper::getParams('com_k2store');
		$show_title = $k2store_params->get('show_title', $params->get('show_title') );
		if ($show_title)
		{
			$html .= "<h3>{$item->title}</h3>";
		}
		$html .= $item->introtext;

		return $html;
	}

	public static function getK2Image($id, $k2params=NULL) {

		$app = JFactory::getApplication();
		$k2params =JComponentHelper::getParams('com_k2store');

		jimport('joomla.filesystem.file');

		//get the params right first
		$image_source = $k2params->get('show_thumb_cart');

		$image = '';
		$image_path = '';

		if($image_source == 'within_text') {
			$item= K2StoreItem::_getK2Item($id);

			$image_path = K2StoreItem::getImages($item->introtext);
			$image = '<img src="'.$image_path.
					'" class="itemImg'.$k2params->get('cartimage_size','small').'" />';

		} elseif($image_source == 'intro') {

			$image_size = $k2params->get('cartimage_size','small');

			if($image_size == 'Large') {
				$size = '_L';
			} elseif($image_size == 'Medium') {
				$size = '_M';
			} else {
				$size = '_S';
			}

			if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$id).$size.'.jpg')) {
				$image_path = JURI::root().'media/k2/items/cache/'.md5("Image".$id).$size.'.jpg';
			}

		} else {
			$image_path = '';
		}

		return $image_path;

	}

	public static function getImages($text) {
        $matches = array();
		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);
		$images = '';
		$images = false;
		$paths = array();
		if (isset($matches[1])) {

			$image_path = $matches[1];

			//joomla 1.5 only
			$full_url = JURI::base();

			//remove any protocol/site info from the image path
			$parsed_url = parse_url($full_url);

			$paths[] = $full_url;
			if (isset($parsed_url['path']) && $parsed_url['path'] != "/") $paths[] = $parsed_url['path'];


			foreach ($paths as $path) {
				if (strpos($image_path,$path) !== false) {
					$image_path = substr($image_path,strpos($image_path, $path)+strlen($path));
				}
			}

			// remove any / that begins the path
			if (substr($image_path, 0 , 1) == '/') $image_path = substr($image_path, 1);

			//if after removing the uri, still has protocol then the image
			//is remote and we don't support thumbs for external images
			if (strpos($image_path,'http://') !== false ||
				strpos($image_path,'https://') !== false) {
				return false;
			}

			$images = JURI::Root(True)."/".$image_path;
			}
		return $images;
	}

	public static function isShippingEnabled($id) {
		//TODO:: depricate and move to prices library
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'prices.php');
		return K2StorePrices::isShippingEnabled($id);
	}

	public static function _getK2Item($id) {

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
		$item =  JTable::getInstance('K2Item', 'Table');
		$id = intval($id);
		$item->load($id);
		return $item;
	}


	public static function getK2Link($item_id) {

		require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
		$db = JFactory::getDBO();
		$query = "SELECT i.id,i.alias,i.catid,c.alias AS categoryalias
		FROM #__k2_items as i
		LEFT JOIN #__k2_categories c ON c.id = i.catid
		WHERE i.published =1 AND i.id=".$db->Quote($item_id);
		$db->setQuery($query);
		$item = $db->loadObject();
		$link = K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryalias));
		return $link;
	}
}