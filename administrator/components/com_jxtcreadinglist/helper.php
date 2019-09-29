<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

$live_site = JURI::root();
$doc = JFactory::getDocument();
$doc->addStyleSheet($live_site."components/com_jxtcreadinglist/readinglist.css");

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.html.parameter');
JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/tables');

if (!class_exists('jxtcrlHelper')) {

	abstract class jxtcrlHelper {

    public static function getEntryId($itemid, $component) { // Get item/component entry id from reading list table
      $user = JFactory::getUser();
      if ($user->guest) {
          return 0;
      }

      $db = JFactory::getDBO();
      $query = "SELECT id FROM #__jxtc_readinglist WHERE published=1 AND user_id=" . $user->id . " AND item_id=" . $itemid . " and component='" . $component . "'";
      $db->setQuery($query);
      return $db->loadResult();
    }

    public static function getReadingListUrl() { // Get link to component
      $db = JFactory::getDBO();
			$query = "SELECT id FROM #__menu WHERE client_id=0 AND published=1 AND link LIKE '%index.php?option=com_jxtcreadinglist%' ORDER BY access DESC";
			$db->setQuery($query, 0, 1);
			$Itemid = (int) $db->loadResult();
			
			$url = JRoute::_('index.php?option=com_jxtcreadinglist&Itemid='.$Itemid);
    	
    	return $url;
    }

    public static function getReadingList($catid, $uid = 0) { // Get all items for current user in category arrays
      $user = $uid ? JFactory::getUser($uid) : JFactory::getUser();
      if ($user->guest) {
				return NULL;
      }

      $db = JFactory::getDBO();
      $query = "SELECT * FROM #__jxtc_readinglist WHERE published=1 AND user_id=" . $user->id . " ORDER BY entry_date DESC";
      $db->setQuery($query);
      $entries = $db->loadObjectList();

      $items = array();

      foreach ($entries as $entry) {
        switch ($entry->component) {
          case 'com_content' : $item = jxtcrlHelper::_COM_CONTENT($entry, $catid); break;
          case 'com_k2' : $item = jxtcrlHelper::_COM_K2($entry, $catid); break;
        }

        if (!empty($item)) {
					$items[$item->category_title][] = $item;
        }
      }
      return $items;
    }

    public static function getReadingListCount($uid = 0) { // Count items for current user
      $user = $uid ? JFactory::getUser($uid) : JFactory::getUser();
      if ($user->guest) {
				return 0;
      }

      $db = JFactory::getDBO();
      $query = "SELECT count(*) FROM #__jxtc_readinglist WHERE published=1 AND user_id=" . $user->id;
      $db->setQuery($query);
      $count = $db->loadResult();

      return $count;
    }

    public static function _COM_CONTENT($entry, $catid) {

      require_once JPATH_ROOT.'/components/com_content/helpers/route.php';

      $db = JFactory::getDBO();
      $query = $db->getQuery(true);

      $query->select(
        'a.id, a.asset_id, a.title, a.alias, a.introtext, a.fulltext, ' .
        // If badcats is not null, this means that the article is inside an unpublished category
        // In this case, the state is set to 0 to indicate Unpublished (even if the article state is Published)
        'CASE WHEN badcats.id is null THEN a.state ELSE 0 END AS state, ' .
        'a.catid, a.created, a.created_by, a.created_by_alias, ' .
        // use created if modified is 0
        'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified, ' .
        'a.modified_by, a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, ' .
        'a.images, a.urls, a.attribs, a.version, a.ordering, ' .
        'a.metakey, a.metadesc, a.access, a.hits, a.metadata, a.featured, a.language, a.xreference'
      );
      $query->from('#__content AS a');

      // Join on category table.
      $query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access');
      $query->join('LEFT', '#__categories AS c on c.id = a.catid');

      // Join on user table.
      $query->select('u.name AS author');
      $query->join('LEFT', '#__users AS u on u.id = a.created_by');

      // Join on contact table
      $subQuery = $db->getQuery(true);
      $subQuery->select('contact.user_id, MAX(contact.id) AS id, contact.language');
      $subQuery->from('#__contact_details AS contact');
      $subQuery->where('contact.published = 1');
      $subQuery->group('contact.user_id, contact.language');
      $query->select('contact.id as contactid');
      $query->join('LEFT', '(' . $subQuery . ') AS contact ON contact.user_id = a.created_by');

      // Join over the categories to get parent category titles
      $query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias');
      $query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

      // Join on voting table
      $query->select('ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count');
      $query->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');

      $query->where('a.id = ' . (int) $entry->item_id);
      if ($catid) $query->where('a.catid = ' . (int) $catid);

      // Join to check for category published state in parent categories up the tree
      // If all categories are published, badcats.id will be null, and we just use the article state
      $subquery = ' (SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
      $subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
      $subquery .= 'WHERE parent.extension = ' . $db->quote('com_content');
      $subquery .= ' AND parent.published <= 0 GROUP BY cat.id)';
      $query->join('LEFT OUTER', $subquery . ' AS badcats ON badcats.id = c.id');

      // Filter by published state.
      $db->setQuery($query);

      $item = $db->loadObject();

      if (empty($item)) {
          return NULL;
      }

      // Convert parameter fields to objects.
      $registry = new JRegistry;
      $registry->loadString($item->attribs);
      $item->params = $registry;
      $registry = new JRegistry;
      $registry->loadString($item->metadata);
      $item->metadata = $registry;

      // Compute selected asset permissions.
      $user = JFactory::getUser();

      // Technically guest could edit an article, but lets not check that to improve performance a little.
      if (!$user->get('guest')) {
        $userId = $user->get('id');
        $asset = 'com_content.article.' . $item->id;

        // Check general edit permission first.
        if ($user->authorise('core.edit', $asset)) {
        	$item->params->set('access-edit', true);
        }
        // Now check if edit.own is available.
        elseif (!empty($userId) && $user->authorise('core.edit.own', $asset)) {
          // Check for a valid user and that they are the owner.
          if ($userId == $item->created_by) {
              $item->params->set('access-edit', true);
          }
        }
      }

      $item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
      $item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
      $item->parent_slug = $item->category_alias ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

			$uri	= JURI::getInstance();
			$base	= $uri->toString(array('scheme', 'host', 'port'));
      $item->itemUrl = $base.JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
      $item->itemCategoryUrl = $base.JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));

      $item->component = 'com_content';

      // Call Content Plugins
			if ($item->fulltext) { $item->text = $item->fulltext; }
			else { $item->text = $item->introtext; }

      JPluginHelper::importPlugin('content');
      $contentconfig = JComponentHelper::getParams('com_content');
      $dispatcher = JDispatcher::getInstance();
      $results = $dispatcher->trigger('onContentPrepare', array('com_content.article', &$item, &$contentconfig, 0));

      return $item;
    }

    public static function _COM_K2($entry, $catid) {

      require_once JPATH_SITE.'/components/com_k2/helpers/route.php';

      $db = JFactory::getDBO();

      $query = 'SELECT i.id, i.video, i.gallery, i.access, i.introtext, i.fulltext, i.title,
				UNIX_TIMESTAMP(i.created) as created, UNIX_TIMESTAMP(i.modified)
				as modified, i.catid, i.extra_fields, i.created_by, i.hits, i.params, cc.params as cat_params, cc.name as category_title,
				cc.image as cat_image, cc.alias as category_alias, u.username as author, u.name as authorname, CASE WHEN CHAR_LENGTH(i.alias)
				THEN CONCAT_WS(":", i.id, i.alias) ELSE i.id END as slug, CASE WHEN CHAR_LENGTH(cc.alias)
				THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug, kr.rating_sum , kr.rating_count, kr.lastip
				FROM #__k2_items AS i LEFT JOIN #__k2_rating AS kr ON kr.itemID = i.id,
				#__k2_categories AS cc, #__users AS u  WHERE i.id=' . $entry->item_id . ' AND cc.id = i.catid
				AND u.id = i.created_by AND i.published = 1 AND i.trash = 0 AND cc.published = 1 AND cc.trash = 0';

      if ($catid) {
				$query .= ' AND i.catid=' . $catid;
      }

      $db->setQuery($query);

      $item = $db->loadObject();

      if (empty($item)) {
      	return NULL;
      }

			$uri	= JURI::getInstance();
			$base	= $uri->toString(array('scheme', 'host', 'port'));
      $item->itemUrl = $base.JRoute::_(K2HelperRoute::getItemRoute($item->slug, $item->catslug));
      $item->itemCategoryUrl = $base.JRoute::_(K2HelperRoute::getCategoryRoute($item->catslug));

      $item->component = 'com_k2';

      // Call Content Plugins
      JLoader::register('K2HelperRoute', JPATH_ROOT.'/components/com_k2/helpers/route.php');
      JLoader::register('K2HelperPermissions', JPATH_ROOT.'/components/com_k2/helpers/permissions.php');
      JLoader::register('K2HelperUtilities', JPATH_ROOT.'/components/com_k2/helpers/utilities.php');
      
      $params = JComponentHelper::getParams('com_k2');

      $item = jxtcrlHelper::executeK2Plugins($item, $params);

      return $item;
		}

    public static function executeK2Plugins($item, $params) {            
      JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_k2/tables');

      $dispatcher = JDispatcher::getInstance();
      JPluginHelper::importPlugin('k2');
      JPluginHelper::importPlugin('content');

      // Get Params
			$cparams = new JRegistry;
			$cparams->loadString($item->cat_params);
			$iparams = new JRegistry;
			$iparams->loadString($item->params);
      $item->params = $params;
      if ($cparams->get('inheritFrom')) {
        $masterCategoryID = $cparams->get('inheritFrom');
        // Category JTable
        $masterCategory = JTable::getInstance('K2Category', 'Table');
        $masterCategory->load($masterCategoryID);
				$cparams = new JRegistry;
				$cparams->loadString($masterCategory->params);
      }
      $item->params->merge($cparams);
      $item->params->merge($iparams);
      
      // For SIG Pro Plugin
      $auxitem = new stdClass();
      if ($item->gallery) {
        $params->set('galleries_rootfolder', 'media/k2/galleries');
        $params->set('enabledownload', '0');
        $auxitem->text = $item->gallery;
        $dispatcher->trigger('onContentPrepare', array('com_k2.item', &$auxitem, &$params, 0));
        $item->gallery = $auxitem->text;
      }

      // For All Videos Plug
      if ($item->video) {
        $params->set('afolder', 'media/k2/audio');
        $params->set('vfolder', 'media/k2/videos');
        
        $params->set('vwidth', $item->params->get('itemVideoWidth'));
        $params->set('vheight', $item->params->get('itemVideoHeight'));
        $params->set('autoplay', $item->params->get('itemVideoAutoPlay'));

        $auxitem->text = $item->video;
        $dispatcher->trigger('onContentPrepare', array('com_k2.item', &$auxitem, &$params, 0));
        $item->video = $auxitem->text;
      }

      //Execute plugin over introtext
      $auxitem->text = $item->introtext;
      $dispatcher->trigger('onContentPrepare', array('com_k2.item', &$auxitem, &$params, 0));
      $item->introtext = $auxitem->text;

      //Ecevute plugins over fulltext
      $auxitem->text = $item->fulltext;
      $dispatcher->trigger('onContentPrepare', array('com_k2.item', &$auxitem, &$params, 0));
      $item->fulltext = $auxitem->text;

      //Get default image
      $item->image = '';
      $size = '';

      $isize = $item->params->get('itemImgSize');

      switch ($isize) {
        case 'XSmall':
	        $size = 'XS';
	        $item->imageWidth = $item->params->get('itemImageXS');
	        break;
        case 'Small':
          $size = 'S';
          $item->imageWidth = $item->params->get('itemImageS');
          break;
        case 'Medium':
          $size = 'M';
          $item->imageWidth = $item->params->get('itemImageM');
          break;
        case 'Large':
          $size = 'L';
          $item->imageWidth = $item->params->get('itemImageL');
          break;
        case 'XLarge':
          $size = 'XL';
          $item->imageWidth = $item->params->get('itemImageXL');
          break;
      }
      
      if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_' . $size . '.jpg') && $item->params->get('itemImage')){
        $item->image = 'media/k2/items/cache/' . md5("Image" . $item->id) . '_' . $size . '.jpg';
        $item->imageXLarge = 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XL.jpg';
      }

      return $item;
    }

    public static function getEmailLink($cid) {
      $user = JFactory::getUser();
      if ($user->guest) {
				return null;
      }

      $key = sha1($user->id . '|' . $user->registerDate . '|' . $cid);

      $Itemid = JRequest::getInt('Itemid');
      $uri = JURI::getInstance();
      $base = $uri->toString(array('scheme', 'host', 'port'));
      $link = $base.JRoute::_('index.php?option=com_jxtcreadinglist&view=share&cid=' . $cid . '&uid=' . $user->id . '&key=' . $key . '&Itemid=' . $Itemid);

      return $link;
    }

    public static function validateKey($cid, $uid, $key) {

      $user = JFactory::getUser($uid);

      if (!$user->id) {
				return false;
      }
      if (!$user->registerDate) {
				return false;
      }

      $testkey = sha1($user->id . '|' . $user->registerDate . '|' . $cid);

      return ($testkey == $key);
    }

    public static function getPluginButton($id, $component, $plugin) {

	    $app = JFactory::getApplication('site');
	    $template = $app->getTemplate(true)->template;
      $linkid = jxtcrlHelper::getEntryId($id, $component);

      if ($linkid) { // do Remove Button
        $defaultFile = JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/remove.php';
        $overrideFile = JPATH_ROOT.'/templates/'.$template.'/html/'.$plugin.'/remove.php';
      } else { // do Add button
        $defaultFile = JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/add.php';
        $overrideFile = JPATH_ROOT.'/templates/'.$template.'/html/'.$plugin.'/add.php';
      }

      ob_start();
      require (JFile::exists($overrideFile) ? $overrideFile : $defaultFile);
      $buttonhtml = ob_get_contents();
      ob_end_clean();

      // Build button code
			JHtml::_('behavior.framework');
			$postcode = base64_encode("$id|$component|$plugin");
      $document = JFactory::getDocument();
      $document->addScript(JURI::base().'components/com_jxtcreadinglist/jxtcreadinglist.js');
      $posturl = 'index.php?option=com_jxtcreadinglist&format=raw&task=post&code=' . $postcode;
      $button = '<span class="readinglistbtn" onclick="javascript:jxtcPost(this,\'' . $posturl . '\');">' . $buttonhtml . '</span>';

      return $button;
    }

    public static function getGuestButton($guesturl, $plugin) {

			if (empty($guesturl)) return null;

      $app = JFactory::getApplication('site');

      $defaultFile = JPATH_ROOT.'/plugins/content/'.$plugin.'/tmpl/guest.php';
      $overrideFile = JPATH_ROOT.'/templates/'.$app->getTemplate(true)->template.'/html/'.$plugin.'/guest.php';

      ob_start();
      require (JFile::exists($overrideFile) ? $overrideFile : $defaultFile);
      $buttonhtml = ob_get_contents();
      ob_end_clean();

      return $buttonhtml;
    }
	}
}