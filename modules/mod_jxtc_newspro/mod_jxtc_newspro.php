<?php
/*
	JoomlaXTC Deluxe News Pro

	version 3.66.0

	Copyright (C) 2008-2017 Monev Software LLC.	All Rights Reserved.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.

	See COPYRIGHT.txt for more information.
	See LICENSE.txt for more information.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;


$moduleDir = 'mod_jxtc_newspro';

// shared function for default_parse
if (!function_exists('npMakeLink')) {
	function npMakeLink($link,$label,$target) {
		$label = ($label) ? $label : $link;
		switch ($target) {
			case 1: // open in a new window
				$html = '<a href="'.htmlspecialchars($link).'" target="_blank" rel="nofollow">'.htmlspecialchars($label).'</a>';
				break;
			case 2: // open in a popup window
				$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=600';
				$html = "<a href=\"".htmlspecialchars($link)."\" onclick=\"window.open(this.href,'targetWindow','".$attribs."');return false;\">".htmlspecialchars($label).'</a>';
				break;
			case 3: // open in a modal window
				JHtml::_('behavior.modal', 'a.modal');
				$html = '<a class="modal" href="'.htmlspecialchars($link).'" rel="{handler:\'iframe\',size:{x:600,y:600}}">'.htmlspecialchars($label).'</a>';
				break;
			default: // open in parent window
				$html = '<a href="'.htmlspecialchars($link).'" rel="nofollow">'.htmlspecialchars($label).'</a>';
				break;
		}
		return $html;
	}
}

//Core calls
$live_site = JURI::base();
$doc = JFactory::getDocument();
$db = JFactory::getDBO();
$user = JFactory::getUser();
$contentconfig = JComponentHelper::getParams('com_content');

require_once (JPATH_SITE.'/components/com_content/helpers/route.php');

//Core Vars

$userid = $user->get('id');
$accesslevel = !$contentconfig->get('show_noauth');
$nullDate = $db->getNullDate();
$date = JFactory::getDate();
$now = $date->toSQL();

//Parameters
$artid = $params->get('artid');
$filteraccess = $params->get('filteraccess', 0);
$avatarw = $params->get('avatarw');
$avatarh = $params->get('avatarh');
$compat = $params->get('compat', 'none');
$comcompat = $params->get('comcompat', 'none');
$catid = $params->get('catid', array(), 'array');
$usecurrentcat = $params->get('usecurrentcat', 1);
$subcats = $params->get('subcats', 0);
$tags = $params->get('tags', array(), 'array');
$authorid = $params->get('authorid', array(), 'array');
$includefrontpage = $params->get('includefrontpage', 1);
$group = $params->get('group', 0);
$sortorder = $params->get('sortorder', 3);
$order = $params->get('order', 3);
$rows = $params->get('rows', 1);
$columns = $params->get('columns', 1);
$pages = $params->get('pages', 1);
$offset = $params->get('offset', 0);
$template = $params->get('template', '');
$moduletemplate = trim($params->get('modulehtml', '{mainarea}'));
$itemtemplate = trim($params->get('html', '{intro}'));
$dateformat = trim($params->get('dateformat', 'Y-m-d'));
$moreclone = $params->get('moreclone', 0);
$morepos = $params->get('morepos', 'b');
$moreqty = $params->get('moreqty', 0);
$morecols = trim($params->get('morecols', 1));
$morelegend = trim($params->get('moretext', ''));
$morelegendcolor = $params->get('morergb', 'cccccc');
$moretemplate = $params->get('morehtml', '{title}');
$languagefilter = $params->get('languagefilter', 0);

if ($template && $template != -1) {
  $moduletemplate = file_get_contents(JPATH_ROOT.'/modules/mod_jxtc_newspro/templates/'.$template.'/module.html');
  $itemtemplate = file_get_contents(JPATH_ROOT.'/modules/mod_jxtc_newspro/templates/'.$template.'/element.html');
  $moretemplate = file_get_contents(JPATH_ROOT.'/modules/mod_jxtc_newspro/templates/'.$template.'/more.html');
  if (file_exists(JPATH_ROOT.'/modules/mod_jxtc_newspro/templates/'.$template.'/template.css')) {
		$doc->addStyleSheet($live_site . 'modules/mod_jxtc_newspro/templates/' . $template . '/template.css', 'text/css');
  }
}

// Build Query
$query = $db->getQuery(true);
$query->select('a.id, a.access,a.introtext,a.fulltext, a.title,UNIX_TIMESTAMP(a.created) as created,UNIX_TIMESTAMP(a.modified) as modified, a.catid, a.created_by, a.created_by_alias, a.hits, a.alias, a.images, a.urls, UNIX_TIMESTAMP(a.publish_up) as publish_up, UNIX_TIMESTAMP(a.publish_down) as publish_down, a.language');
$query->select('cc.title as cat_title, cc.params as cat_params, cc.description as cat_description, cc.alias as cat_alias, cc.id as cat_id');
$query->select('u.name as author, u.username as authorid');
$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug');
$query->select('CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug');
$query->from('#__content AS a');
$query->from('#__users AS u');
$query->where('u.id = a.created_by');
$query->from('#__categories as cc');
$query->where('cc.id = a.catid');
$query->where('cc.published = 1');
$query->where('a.state=1');

if ($artid) { // forced articles
  $articles = explode(',', $artid);
  JArrayHelper::toInteger($articles);
  $query->where('a.id IN (' . implode(',', $articles) . ')');
} else { // filtered articles
	$query->where('(a.publish_up = ' . $db->Quote($nullDate) . ' OR a.publish_up <= ' . $db->Quote($now) . ')');
	$query->where('(a.publish_down = ' . $db->Quote($nullDate) . ' OR a.publish_down >= ' . $db->Quote($now) . ')');

	// categories
  if ($catid && !empty($catid[0])) {
  	if ($subcats) {
			$query->where('cc.id IN (SELECT node.id FROM #__categories AS node, #__categories AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.id IN (' . implode(',', $catid) . '))');
  	} else {
			$query->where('cc.id IN (' . implode(',', $catid) . ')');
		}
  }

	// current category
	if ($usecurrentcat == 1) {
	  $option = JRequest::getCmd('option');
	  $view = JRequest::getCmd('view');
	  if ($option == 'com_content' and $view == "category") {
			$catid = JRequest::getInt('id', 0, 'int');
			if ($catid) $query->where('cc.id='.(int) $catid);
	  }
	}

	// tags
	if ($tags && !empty($tags[0])) {
	  $query->where('a.id IN (SELECT content_item_id FROM #__contentitem_tag_map WHERE type_alias LIKE "com_content.article" AND tag_id IN (' . implode(',', $tags) . '))');
	}

	// author
	if ($authorid && !empty($authorid[0])) {
		$query->where('a.created_by IN (' . implode(',', $authorid) . ')');
	}

	if ($group == 1) {
		$query->group('a.created_by');
	}

	// frontpage
	switch ($includefrontpage) {
		case 0: // no fp
		  $query->where('a.id NOT IN (SELECT content_id FROM #__content_frontpage)');
		break;
		case 2: // only fp
		  $query->where('a.id IN (SELECT content_id FROM #__content_frontpage)');
		break;
	}

	// Joomla ACL
	if ($accesslevel && $filteraccess) {
	  $query->where('a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
	}

	// language
	switch ($languagefilter) {
		case 1: // 1 Default language
			$query->where('AND a.language = "*"');
		break;
		case 2: // 2 User language
			$lang_tag = JFactory::getLanguage()->get('tag');
			$query->where('a.language = "' . $lang_tag . '"');
		break;
		case 3: // 3 default + User languages
			$lang_tag = JFactory::getLanguage()->get('tag');
			$query->where('AND (a.language = "*" OR a.language = "' . $lang_tag . '")');
		break;
	}
}

// order sort
$aux = ($order == '0') ? ' ASC ' : ' DESC ';
switch ($sortorder) {
  case 0: // creation
    $query->order('a.created ' . $aux);
    break;
  case 1: // modified
    $query->order('a.modified ' . $aux);
    break;
  case 2: // hits
    $query->order('a.hits ' . $aux);
    break;
  case 3: // joomla order
    $query->order('a.ordering ' . $aux);
    break;
  case 5: // Category Title
    $query->order('cc.title ' . $aux);
    break;
  case 6: // Article Title
    $query->order('a.title ' . $aux);
    break;
  case 7:
    $query->order('RAND()');
    break;
	case 8:	// publish up date
		$query->order('a.publish_up ' . $aux);
		break;
	case 9:	// publish down date
		$query->order('a.publish_down ' . $aux);
		break;
}

$mainqty = $columns * $rows * $pages;
$db->setQuery($query, $offset, $mainqty + $moreqty);
try {
	$items = $db->loadObjectList();
}
catch (RuntimeException $e) {
	JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
	return false;
}

if (count($items) == 0) return; // Return if empty

// Check for RL support
$enablerl = false;
jimport( 'joomla.plugin.helper' );
if (JpluginHelper::isEnabled('content','jxtcreadinglist')) {
	$enablerl = true;
}

// Main area
$rowmaxintro = $params->get('maxintro', '');
$rowmaxintrosuf = $params->get('maxintrosuf', '...');
$rowmaxtitle = $params->get('maxtitle', '');
$rowmaxtitlesuf = $params->get('maxtitlesuf', '...');
$rowmaxtext = $params->get('maxtext', '');
$rowmaxtextsuf = $params->get('maxtextsuf', '...');
$rowtextbrk = $params->get('textbrk', '');
require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));

// More area
$rowmaxintro = $params->get('moreintro', '');
$rowmaxintrosuf = $params->get('moreintrosuf', '...');
$rowmaxtitle = $params->get('moretitle', '');
$rowmaxtitlesuf = $params->get('moretitlesuf', '...');
$rowmaxtext = $params->get('moremaxtext', '');
$rowmaxtextsuf = $params->get('moremaxtextsuf', '...');
$rowtextbrk = $params->get('moretextbrk', '');

$items = $params->get('moreclone', 0)
	? array_slice($items,0,$moreqty)
	: array_slice($items,($columns * $rows * $pages),$moreqty);

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default').'_more');

if ($params->get('contentPlugins', 1)) {
	JPluginHelper::importPlugin('content');
	$contentconfig = JComponentHelper::getParams('com_content');
	$dispatcher = JDispatcher::getInstance();
	$item = new stdClass();
	$item->text = $modulehtml;
	$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$contentconfig, 0 ));
	$modulehtml = $item->text;
}

echo '<div id="' . $jxtc . '">' . $modulehtml . '</div>';
