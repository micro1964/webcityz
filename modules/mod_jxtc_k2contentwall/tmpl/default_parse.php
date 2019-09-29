<?php

/*
	JoomlaXTC K2 Content Wall

	version 1.38.1

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


if ($enablerl) {
	require_once JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/helper.php';
	$readinglist = jxtcrlHelper::getPluginButton($item->id,'com_k2','jxtcreadinglistk2');
} else $readinglist = '';

require_once (JPATH_SITE.'/components/com_k2/models/item.php');

// Article image
$ini = JString::strpos(strtolower($item->introtext), '<img');
if ($ini === false)
	$img = '';
else {
	$pos = JString::strpos($item->introtext, 'src="', $ini) + 5;
	$fin = JString::strpos($item->introtext, '"', $pos);
	$img = JString::substr($item->introtext, $pos, $fin - $pos);
	$fin = JString::strpos($item->introtext, '>', $fin);
}

$ini = JString::strpos(strtolower($item->fulltext), '<img');
if ($ini === false)
	$fullimg = '';
else {
	$pos = JString::strpos($item->fulltext, 'src="', $ini) + 5;
	$fin = JString::strpos($item->fulltext, '"', $pos);
	$fullimg = JString::substr($item->fulltext, $pos, $fin - $pos);
	$fin = JString::strpos($item->fulltext, '>', $fin);
}

$intronoimage = $item->introtext;
while (($ini = JString::strpos($intronoimage, '<img')) !== false) {
	if (($fin = JString::strpos($intronoimage, '>', $ini)) === false) {
		break;
	}
	$intronoimage = JString::substr_replace($intronoimage, '', $ini, $fin - $ini + 1);
}

$fullnoimage = $item->fulltext;
while (($ini = JString::strpos($fullnoimage, '<img')) !== false) {
	if (($fin = JString::strpos($fullnoimage, '>', $ini)) === false) {
		break;
	}
	$fullnoimage = JString::substr_replace($fullnoimage, '', $ini, $fin - $ini + 1);
}

$title = ($rowmaxtitle) ? JString::substr(strip_tags($item->title), 0, $rowmaxtitle) . $rowmaxtitlesuf : strip_tags($item->title);
$intro = ($rowmaxintro) ? JString::substr(strip_tags($item->introtext), 0, $rowmaxintro) . $rowmaxintrosuf : strip_tags($item->introtext);

$rawfulltext = $item->fulltext;
$fulltext = strip_tags($item->fulltext);
if (!empty($rowtextbrk)) {
	$pos = JString::strpos($rawfulltext, $rowtextbrk);
	if ($pos !== false) {
		$rawfulltext = substr($rawfulltext, 0, $pos + strlen($rowtextbrk));
	}
	$pos = JString::strpos($fulltext, $rowtextbrk);
	if ($pos !== false) {
		$fulltext = JString::substr($fulltext, 0, $pos + strlen($rowtextbrk));
	}
}

if (!empty($rowmaxtext)) {
	$fulltext = JString::trim(JString::substr($fulltext, 0, $rowmaxtext)) . $rowmaxtextsuf;
	$rawfulltext = JString::trim(JString::substr($rawfulltext, 0, $rowmaxtext)) . $rowmaxtextsuf;
}
$avatarw = $params->get('avatarw');
$avatarh = $params->get('avatarh');
$userid = $item->created_by;
$avatarimg = '';
$authorlink = '';
$articlelink = JRoute::_(K2HelperRoute::getItemRoute($item->slug, $item->catslug));
$categorylink = JRoute::_(K2HelperRoute::getCategoryRoute($item->catslug));
$categoryrsslink = JRoute::_(K2HelperRoute::getCategoryRoute($item->catslug).'&format=feed');

switch ($params->get('compat', 'none')) {
  case 'none':
    break;
  case 'cb':
    $db->setQuery('SELECT avatar from #__comprofiler WHERE user_id=' . $userid);
    $avatar = $db->loadResult();
		$avatarimgurl = empty($avatar) ? '' : $live_site . 'components/com_comprofiler/images/' . $avatar;
    $avatarimg = empty($avatar) ? '' : '<img src="' . $avatarimgurl . '" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />';
    $authorlink = JRoute::_('index.php?option=com_comprofiler&view=profile$userid=' . $userid);
    break;
	case 'jomsoc':
		include_once( JPATH_ROOT.'/components/com_community/defines.community.php' );
		require_once( JPATH_ROOT.'/components/com_community/libraries/core.php' );
		$db->setQuery('SELECT avatar from #__community_users WHERE userid=' . $userid);
		$avatar = $db->loadResult();
		$avatarimgurl = empty($avatar) ? $live_site . 'components/com_community/assets/user.png' : $live_site . $avatar;
		$avatarimg = '<img src="' . $avatarimgurl . '" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />';
		$authorlink = CRoute::_('index.php?option=com_community&view=profile$userid=' . $userid);
		break;
  case 'kunena':    
    $db->setQuery('SELECT avatar from #__kunena_users WHERE userid=' . $userid);
    $avatar = $db->loadResult();
    $avatarimg = empty($avatar) ? '<img src="' . $live_site . 'media/kunena/avatars/nophoto.jpg" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />' : '<img src="' . $live_site . 'media/kunena/avatars/' . $avatar . '" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />';
    $slug = $userid . ':' . $item->author;
    $authorlink = KunenaRoute::_ ( 'index.php?option=com_kunena&view=profile$userid=' . $slug);
    break;
	case 'k2':
		require_once JPATH_SITE.'/components/com_k2/helpers/route.php';
		require_once JPATH_SITE.'/components/com_k2/helpers/utilities.php';
    $db->setQuery('SELECT image from #__k2_users WHERE userID=' . $userid);
    $avatar = $db->loadResult();
		$avatarimgurl = empty($avatar) ? $live_site . 'components/com_k2/images/placeholder/user.png' : $live_site . 'media/k2/users/' . $avatar;
    $avatarimg = '<img src="' . $avatarimgurl . '" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />';
    $authorlink = JRoute::_(K2HelperRoute::getUserRoute($userid));
    break;
	case 'komento':
		if (!class_exists("KomentoProfile")) {
			require_once( JPATH_ROOT.'/components/com_komento/bootstrap.php' );
			require_once( KOMENTO_CLASSES.'/profile.php' );
		}
		$komento_user = new KomentoProfile($userid);
		$avatarimg = '<img src="' . $komento_user->getAvatar() . '" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />';
		$authorlink = $komento_user->getProfileLink();
	break;
}

$comments = 0;
switch ($params->get('comcompat', 'none')) {
	case 'none':
	break;
	case 'jomcom':
		$db->setQuery("SELECT count(*) from #__jomcomment WHERE contentid = '$item->id'");
		$comments = (int) $db->loadResult();
	break;
	case 'k2':
		$db->setQuery("SELECT count(*) from #__k2_comments WHERE itemID = '$item->id'");
		$comments = (int) $db->loadResult();
	break;
	case 'komento':
		$db->setQuery("SELECT count(*) from #__komento_comments WHERE component = 'com_k2' AND cid = $item->id AND published=1");
		$comments = (int) $db->loadResult();
	break;
}

//Image
$imageXSmallurl = '';
$imageSmallurl = '';
$imageMediumurl = '';
$imageLargeurl = '';
$imageXLargeurl = '';
$imageGenericurl = '';
$imageOriginalurl = '';

$imageXSmall = '';
$imageSmall = '';
$imageMedium = '';
$imageLarge = '';
$imageXLarge = '';
$imageGeneric = '';
$imageOriginal = '';

if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_XS.jpg')) {
	$imageXSmallurl = $live_site . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XS.jpg';
	$imageXSmall = "<img src=" . $imageXSmallurl . " alt=" . $imageXSmallurl . " />";
}

if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_S.jpg')) {
  $imageSmallurl = $live_site . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_S.jpg';
  $imageSmall = "<img src=" . $imageSmallurl . " alt=" . $imageSmallurl . " />";
}

if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_M.jpg')) {
  $imageMediumurl = $live_site . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_M.jpg';
  $imageMedium = "<img src=" . $imageMediumurl . " alt=" . $imageMediumurl . " />";
}

if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_L.jpg')) {
  $imageLargeurl = $live_site . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_L.jpg';
  $imageLarge = "<img src=" . $imageLargeurl . " alt=" . $imageLargeurl . " />";
}

if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_XL.jpg')) {
  $imageXLargeurl = $live_site . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XL.jpg';
  $imageXLarge = "<img src=" . $imageXLargeurl . " alt=" . $imageXLargeurl . " />";
}

if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image" . $item->id) . '_Generic.jpg')) {
  $imageGenericurl = $live_site . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_Generic.jpg';
  $imageGeneric = "<img src=" . $imageGenericurl . " alt=" . $imageGenericurl . " />";
}

if (JFile::exists(JPATH_SITE.'/media/k2/items/src/'.md5("Image" . $item->id) . '.jpg')) {
  $imageOriginalurl = $live_site . 'media/k2/items/src/' . md5("Image" . $item->id) . '.jpg';
  $imageOriginal = "<img src=" . $imageOriginalurl . " alt=" . $imageOriginalurl . " />";
}


$itemhtml = str_ireplace('{imageXSmall}', $imageXSmall, $itemhtml);
$itemhtml = str_ireplace('{imageSmall}', $imageSmall, $itemhtml);
$itemhtml = str_ireplace('{imageMedium}', $imageMedium, $itemhtml);
$itemhtml = str_ireplace('{imageLarge}', $imageLarge, $itemhtml);
$itemhtml = str_ireplace('{imageXLarge}', $imageXLarge, $itemhtml);
$itemhtml = str_ireplace('{imageGeneric}', $imageGeneric, $itemhtml);
$itemhtml = str_ireplace('{imageOriginal}', $imageOriginal, $itemhtml);

$itemhtml = str_ireplace('{imageXSmallurl}', $imageXSmallurl, $itemhtml);
$itemhtml = str_ireplace('{imageSmallurl}', $imageSmallurl, $itemhtml);
$itemhtml = str_ireplace('{imageMediumurl}', $imageMediumurl, $itemhtml);
$itemhtml = str_ireplace('{imageLargeurl}', $imageLargeurl, $itemhtml);
$itemhtml = str_ireplace('{imageXLargeurl}', $imageXLargeurl, $itemhtml);
$itemhtml = str_ireplace('{imageGenericurl}', $imageGenericurl, $itemhtml);
$itemhtml = str_ireplace('{imageOriginalurl}', $imageOriginalurl, $itemhtml);

if ($item->video) {
  if (JString::substr($item->video, 0, 1) !== '{') {
      $item->videoType = 'embedded';
  } else {
    $k2params = JComponentHelper::getParams('com_k2');
		$itemparams = new JRegistry;
		$itemparams->loadString($item->params);
    JPluginHelper::importPlugin('content', 'jw_allvideos');

    $dispatcher = JDispatcher::getInstance();

    $item->videoType = 'allvideos';
    $k2params->set('afolder', 'media/k2/audio');
    $k2params->set('vfolder', 'media/k2/videos');

    if (JString::strpos($item->video, 'remote}')) {
      preg_match("#}(.*?){/#s", $item->video, $matches);
      if (JString::substr($matches[1], 0, 7) != 'http://') {
				$item->video = str_ireplace($matches[1], JURI::root() . $matches[1], $item->video);
			}
    }

		$videow = $params->get('videow',0);
		$videoh = $params->get('videoh',0);
		$videoWidth = $videow ? $videow : $itemparams->get('itemVideoWidth');
		$videoHeight = $videoh ? $videoh : $itemparams->get('itemVideoHeight');
		
    $k2params->set('vwidth', $videoWidth);
    $k2params->set('vheight', $videoHeight);
    $k2params->set('autoplay', $itemparams->get('itemVideoAutoPlay'));

    $item->text = $item->video;
    
    $dispatcher->trigger('onContentPrepare', array('com_content.article', &$item, &$k2params, 0));
    
    $item->video = $item->text;
  }
}
$itemhtml = str_ireplace('{video}', $item->video, $itemhtml);

if ($item->gallery) {
  $k2params = JComponentHelper::getParams('com_k2');
  JPluginHelper::importPlugin('content', 'jw_sigpro');
  //JPluginHelper::importPlugin('content', 'jw_sig');

  /*if (JFile::exists(JPATH_ROOT.'/plugins/content/jw_simpleImageGallery/jw_simpleImageGallery.php'))
      JPlugin::loadLanguage('plg_content_jw_sig', JPATH_ROOT.'/administrator');
  
  if (JFile::exists(JPATH_ROOT.'/plugins/content/jw_sigpro/jw_sigpro.php'))
      JPlugin::loadLanguage('plg_content_jw_sigpro', JPATH_ROOT.'/administrator');*/

  $dispatcher = JDispatcher::getInstance();
  $k2params->set('galleries_rootfolder', 'media/k2/galleries');
  $k2params->set('thb_width', '150');
  $k2params->set('thb_height', '120');
  $k2params->set('popup_engine', 'mootools_slimbox');
  $k2params->set('enabledownload', '0');
  $item->text = $item->gallery;
  
  $dispatcher->trigger('onContentPrepare', array('com_content.article', &$item, &$k2params, 0));
  
  $item->gallery = $item->text;
}
$itemhtml = str_ireplace('{imagegallery}', $item->gallery, $itemhtml);

// Custom fields:
if (strpos($itemhtml, '{field_') !== false) { // Grab custom fields only when needed
  require_once('components/com_k2/helpers/utilities.php');

  $model = new K2ModelItem;
  $item->extra_fields = $model->getItemExtraFields($item->extra_fields, $item);

  while (($ini = strpos($itemhtml, "{field_")) !== false) {
    $fin = strpos($itemhtml, "}", $ini);
    $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
    @list($filter, $length) = explode(' ', $filter);
    $subfilter = trim(substr($filter, 6));
    $length = isset($length) ? trim($length) : 0;
    $value = '';
    foreach ($item->extra_fields as $val) {
      if ((string) $val->alias == $subfilter) {
        $value = $val->value;
        if ($length && strlen($value) > $length) {
					$value = substr($value, 0, $length);
        }
      }
    }
    $itemhtml = substr_replace($itemhtml, $value, $ini, $fin - $ini + 1);
  }
}

// Tags
if (strpos($itemhtml, '{tag') !== false) {
  $model = new K2ModelItem;
  $tags = $model->getItemTags($item->id);
  $tagslink = '';
  $tagsname = '';
  if (count($tags)) {
    for ($i = 0; $i < (sizeof($tags) - 1); $i++) {
      $tags[$i]->link = urldecode(JRoute::_(K2HelperRoute::getTagRoute($tags[$i]->name)));
      $tagsname .= $tags[$i]->name . ', ';
      $tagslink .= '<a href="' . $tags[$i]->link . '">' . $tags[$i]->name . '</a>, ';
    }
    $tags[$i]->link = urldecode(JRoute::_(K2HelperRoute::getTagRoute($tags[$i]->name)));
    $tagsname .= $tags[$i]->name;
    $tagslink .= '<a href="' . $tags[$i]->link . '">' . $tags[$i]->name . '</a>';
  }

  $itemhtml = str_ireplace('{tagnames}', $tagsname, $itemhtml);
  $itemhtml = str_ireplace('{taglinks}', $tagslink, $itemhtml);
}

//Related items
if (strpos($itemhtml, '{related') !== false) {
  $relatedname = '';
  $relatedlink = '';
  if (count($tags)) {
    require_once (JPATH_SITE.'/components/com_k2/helpers/utilities.php');
    require_once (JPATH_SITE.'/components/com_k2/models/itemlist.php');
    $model = new K2ModelItemlist;
    $relatedItems = $model->getRelatedItems($item->id, $tags, $related);

    if (count($relatedItems)) {
      for ($i = 0; $i < (sizeof($relatedItems) - 1); $i++) {
        $relatedItems[$i]->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($relatedItems[$i]->id . ':' . urlencode($relatedItems[$i]->alias), $relatedItems[$i]->catid . ':' . urlencode($relatedItems[$i]->categoryalias))));
        $relatedname .= $relatedItems[$i]->title . ', ';
        $relatedlink .= '<a href="' . $relatedItems[$i]->link . '">' . $relatedItems[$i]->title . '</a>, ';
      }
      $relatedItems[$i]->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($relatedItems[$i]->id . ':' . urlencode($relatedItems[$i]->alias), $relatedItems[$i]->catid . ':' . urlencode($relatedItems[$i]->categoryalias))));
      $relatedname .= $relatedItems[$i]->title;
      $relatedlink .= '<a href="' . $relatedItems[$i]->link . '">' . $relatedItems[$i]->title . '</a>';
    }
  }

  $itemhtml = str_ireplace('{relatedname}', $relatedname, $itemhtml);
  $itemhtml = str_ireplace('{relatedlink}', $relatedlink, $itemhtml);
}

//Twitter link
if (strpos($itemhtml, 'twitter') !== false) {
  $twitterURL = '';
  $cattwitterURL = '';
  $k2params = JComponentHelper::getParams('com_k2');

  if ($k2params->get('twitterUsername')) {
    // Absolute URL
    $uri = $live_site . $categorylink;
    //$uri = JURI::getInstance();
    //$itemURLForTwitter = ($k2params->get('tinyURL')) ? @file_get_contents('http://tinyurl.com/api-create.php?url='.$uri->_uri) : $uri->_uri;
    $itemURLForTwitter = ($k2params->get('tinyURL')) ? @file_get_contents('http://tinyurl.com/api-create.php?url=' . $uri) : $uri;
    $cattwitterURL = 'http://twitter.com/home/?status=' . urlencode('Reading @' . $k2params->get('twitterUsername') . ' ' . $item->cat_title . ' ' . $itemURLForTwitter);
  }

  if ($k2params->get('twitterUsername')) {
    // Absolute URL
    $uri = $live_site . $articlelink;
    //$uri = JURI::getInstance();
    //$itemURLForTwitter = ($k2params->get('tinyURL')) ? @file_get_contents('http://tinyurl.com/api-create.php?url='.$uri->_uri) : $uri->_uri;
    $itemURLForTwitter = ($k2params->get('tinyURL')) ? @file_get_contents('http://tinyurl.com/api-create.php?url=' . $uri) : $uri;
    $twitterURL = 'http://twitter.com/home/?status=' . urlencode('Reading @' . $k2params->get('twitterUsername') . ' ' . $title . ' ' . $itemURLForTwitter);
  }

  $itemhtml = str_ireplace('{itemtwitterurl}', $twitterURL, $itemhtml);
  $itemhtml = str_ireplace('{categorytwitterurl}', $cattwitterURL, $itemhtml);

  $twitter = '<span class="itemTwitterLink">
  <a title="' . JText::_('Like this? Tweet it to your followers!') . '" href="' . $twitterURL . '" target="_blank">' . JText::_($twetttext) . '</a></span>';

  $cattwitter = '<span class="itemTwitterLink">
  <a title="' . JText::_('Like this? Tweet it to your followers!') . '" href="' . $cattwitterURL . '" target="_blank">' . JText::_($cattwetttext) . '</a></span>';

  $itemhtml = str_ireplace('{itemtwitterlink}', $twitter, $itemhtml);
  $itemhtml = str_ireplace('{categorytwitterlink}', $cattwitter, $itemhtml);
}

//attachment
$attachment = array();
$attachmentname = array();
$attachmenthits = array();
$attachmentattrib = array();
$attachmentdownload = array();
$attachmenturl = array();


if (strpos($itemhtml, '{attach') !== false) { // Grab custom attachment only when needed
  $model = new K2ModelItem;

  $attachments = $model->getItemAttachments($item->id);

  if ($attachments) {
    foreach ($attachments as $aux) {
      $attachmenturl[] = $aux->link;
      $attachment[] = $live_site . 'media/k2/attachments/' . $aux->filename;
      $attachmentname[] = $aux->title;
      $attachmenthits[] = $aux->hits;
      $attachmentattrib[] = $aux->titleAttribute;
      $attachmentdownload[] = '<a href="' . $aux->link . '" title="' . $aux->titleAttribute . '">' . $aux->title . '</a>';
    }
  }

  if (strpos($itemhtml, '{attachmenturl_') !== false) {
    while (($ini = strpos($itemhtml, "{attachmenturl_")) !== false) {
      $fin = strpos($itemhtml, "}", $ini);
      $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
      $i = intval(ltrim($filter, 'attachmenturl_')) - 1;
      $attachmenturl[$i] = isset($attachmenturl[$i]) ? $attachmenturl[$i] : '';

      $itemhtml = substr_replace($itemhtml, $attachmenturl[$i], $ini, $fin - $ini + 1);
    }
  }

  if (strpos($itemhtml, '{attachmentdownload_') !== false) {
    while (($ini = strpos($itemhtml, "{attachmentdownload_")) !== false) {
      $fin = strpos($itemhtml, "}", $ini);
      $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
      $i = intval(ltrim($filter, 'attachmentdownload_')) - 1;
      $attachmentdownload[$i] = isset($attachmentdownload[$i]) ? $attachmentdownload[$i] : '';

      $itemhtml = substr_replace($itemhtml, $attachmentdownload[$i], $ini, $fin - $ini + 1);
    }
  }

  if (strpos($itemhtml, '{attachment_') !== false) {
    while (($ini = strpos($itemhtml, "{attachment_")) !== false) {
      $fin = strpos($itemhtml, "}", $ini);
      $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
      $i = intval(ltrim($filter, 'attachment_')) - 1;
      $attachment[$i] = isset($attachment[$i]) ? $attachment[$i] : '';

      $itemhtml = substr_replace($itemhtml, $attachment[$i], $ini, $fin - $ini + 1);
    }
  }

  if (strpos($itemhtml, '{attachmentname_') !== false) {
    while (($ini = strpos($itemhtml, "{attachmentname_")) !== false) {
      $fin = strpos($itemhtml, "}", $ini);
      $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
      $i = intval(ltrim($filter, 'attachmentname_')) - 1;
      $attachmentname[$i] = isset($attachmentname[$i]) ? $attachmentname[$i] : '';

      $itemhtml = substr_replace($itemhtml, $attachmentname[$i], $ini, $fin - $ini + 1);
    }
  }

  if (strpos($itemhtml, '{attachmenthits_') !== false) {
    while (($ini = strpos($itemhtml, "{attachmenthits_")) !== false) {
      $fin = strpos($itemhtml, "}", $ini);
      $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
      $i = intval(ltrim($filter, 'attachmenthits_')) - 1;
      $attachmenthits[$i] = isset($attachmenthits[$i]) ? $attachmenthits[$i] : '';

      $itemhtml = substr_replace($itemhtml, $attachmenthits[$i], $ini, $fin - $ini + 1);
    }
  }

  if (strpos($itemhtml, '{attachmentattrib_') !== false) {
    while (($ini = strpos($itemhtml, "{attachmentattrib_")) !== false) {
      $fin = strpos($itemhtml, "}", $ini);
      $filter = substr($itemhtml, $ini + 1, $fin - $ini - 1);
      $i = intval(ltrim($filter, 'attachmentattrib_')) - 1;
      $attachmentattrib[$i] = isset($attachmentattrib[$i]) ? $attachmentattrib[$i] : '';

      $itemhtml = substr_replace($itemhtml, $attachmentattrib[$i], $ini, $fin - $ini + 1);
    }
  }
}

$categoryimageurl = 'No picture';
$categoryimage = 'No picture';
if ($item->cat_image) {
	$categoryimageurl = $live_site . 'media/k2/categories/' . $item->cat_image;
	$categoryimage = "<img src=" . $categoryimageurl . " alt=" . $categoryimageurl . " />";
}

$ratingprom = 0;
settype($item->rating_sum,'integer');
settype($item->rating_count,'integer');
if ($item->rating_count) {
	$ratingprom = ($item->rating_sum / $item->rating_count) * 20;
}

$rate = '<div class="itemRatingBlock"><div class="itemRatingForm">
    <ul class="itemRatingList">
            <li class="itemCurrentRating" id="itemCurrentRating' . $item->id . '" style="width:' . $ratingprom . '%;"></li>
            <li><a href="#" rel="' . $item->id . '" title="' . JText::_('1 star out of 5') . '" class="one-star">1</a></li>
            <li><a href="#" rel="' . $item->id . '" title="' . JText::_('2 stars out of 5') . '" class="two-stars">2</a></li>
            <li><a href="#" rel="' . $item->id . '" title="' . JText::_('3 stars out of 5') . '" class="three-stars">3</a></li>
            <li><a href="#" rel="' . $item->id . '" title="' . JText::_('4 stars out of 5') . '" class="four-stars">4</a></li>
            <li><a href="#" rel="' . $item->id . '" title="' . JText::_('5 stars out of 5') . '" class="five-stars">5</a></li>
    </ul>';
if ($numvotes) {
	$rate .= '<div id="itemRatingLog' . $item->id . '" class="itemRatingLog">' . $item->rating_count . '</div><div class="clr"></div>';
}

$rate .= '</div><div class="clr"></div></div>';

$rating = '<div class="itemRatingBlock"><div class="itemRatingForm">
    <ul class="itemRatingList">
    <li class="itemCurrentRating" id="itemCurrentRating' . $item->id . '" style="width:' . $ratingprom . '%;"></li>
    </ul>';
if ($numvotes) {
	$rating .= '<div id="itemRatingLog' . $item->id . '" class="itemRatingLog">' . $item->rating_count . '</div><div class="clr"></div>';
}

$rating .= '</div><div class="clr"></div></div>';

if (!$item->rating_sum) { $item->rating_sum = 'Not rated yet'; }
if (!$item->rating_count) { $item->rating_count = 'Not rated yet'; }
if (!$item->lastip) { $item->lastip = 'Not rated yet'; }

$itemhtml = str_ireplace('{ratingsum}', $item->rating_sum, $itemhtml);
$itemhtml = str_ireplace('{ratingcount}', $item->rating_count, $itemhtml);
$itemhtml = str_ireplace('{lastip}', $item->lastip, $itemhtml);
$itemhtml = str_ireplace('{rate}', $rate, $itemhtml);
$itemhtml = str_ireplace('{rating}', $rating, $itemhtml);
$itemhtml = str_ireplace('{attachmenturl}', implode(', ', $attachmenturl), $itemhtml);
$itemhtml = str_ireplace('{attachmentdownload}', implode(', ', $attachmentdownload), $itemhtml);
$itemhtml = str_ireplace('{attachment}', implode(', ', $attachment), $itemhtml);
$itemhtml = str_ireplace('{attachmenthits}', implode(', ', $attachmenthits), $itemhtml);
$itemhtml = str_ireplace('{attachmentattrib}', implode(', ', $attachmentattrib), $itemhtml);
$itemhtml = str_ireplace('{id}', $item->id, $itemhtml);
$itemhtml = str_ireplace('{link}', $articlelink, $itemhtml);
$itemhtml = str_ireplace('{introimage}', $img, $itemhtml);
$itemhtml = str_ireplace('{fullimage}', $fullimg, $itemhtml);
$itemhtml = str_ireplace('{categoryid}', $item->catid, $itemhtml );
$itemhtml = str_ireplace('{categoryimageurl}', $categoryimageurl, $itemhtml);
$itemhtml = str_ireplace('{categoryimage}', $categoryimage, $itemhtml);
$itemhtml = str_ireplace('{categoryurl}', $categorylink, $itemhtml);
$itemhtml = str_ireplace('{categoryrssurl}', $categoryrsslink, $itemhtml);
$itemhtml = str_ireplace('{avatar}', $avatarimg, $itemhtml);
$itemhtml = str_ireplace('{authorprofile}', $authorlink, $itemhtml);
$itemhtml = str_ireplace('{index}', $index, $itemhtml);
$itemhtml = str_ireplace('{hits}', $item->hits, $itemhtml);
$itemhtml = str_ireplace('{comments}', $comments, $itemhtml);
$itemhtml = str_ireplace('{readinglist}', $readinglist, $itemhtml );
$itemhtml = str_ireplace('{featured}', ($item->featured ? 'Y' : 'N'), $itemhtml );

// New tag parse model
// strings
$tagmodel = array(
	'attachmentname' => implode(', ', $attachmentname),
	'author' => $item->author,
	'authorname' => $item->authorname,
	'category' => $item->cat_title,
	'categoryalias' => $item->cat_alias,
	'categorydescription' => $item->cat_description,
	'categorydescription_text' => strip_tags($item->cat_description),
	'fullnoimage' => $fullnoimage,
	'fulltext' => $fulltext,
	'intro' => $item->introtext,
	'intronoimage' => $intronoimage,
	'introtext' => $intro,
	'rawfulltext' => $rawfulltext,
	'title' => $title
);

foreach ($tagmodel as $tag => $value) {
  $match = preg_match_all('#{'.$tag.' (.*?)}#s', $itemhtml, $hits);
  if ($match) {
    for ($i = 0; $i < $match; $i++) {
      $tag2 = $hits[0][$i]; $tagparam = trim($hits[1][$i]);
			@list($length,$suffix) = explode(' ', $tagparam,2);
	    if ($length && strlen($value) > $length) {
	    	$value = JString::trim(JString::substr($value, 0, $length));
	    	if ($suffix) { $value = $value . $suffix; }
	    }
      $itemhtml = str_ireplace($tag2, $value, $itemhtml);
    }
  }
	$itemhtml = str_ireplace('{'.$tag.'}',$value, $itemhtml);
}

//dates
$tagmodel = array(
	'date' => date('Y-m-d H:i:s',$item->created),
	'moddate' => date('Y-m-d H:i:s',$item->modified),
	'publish_up' => date('Y-m-d H:i:s',$item->publish_up),
	'publish_down' => date('Y-m-d H:i:s',$item->publish_down)
);

foreach ($tagmodel as $tag => $value) {
  $match = preg_match_all('#{'.$tag.' (.*?)}#s', $itemhtml, $hits);
  if ($match) {
    for ($i = 0; $i < $match; $i++) {
			$tag2 = $hits[0][$i]; $tagparam = trim($hits[1][$i]);
      $format = $tagparam ? $tagparam : $dateformat;
			$itemhtml = str_ireplace($tag2, JHtml::_('date', $value, $format), $itemhtml);
    }
  }
	$itemhtml = str_ireplace('{'.$tag.'}', JHtml::_('date', $value, $dateformat), $itemhtml);
}

//language
$match = preg_match_all('#{language(.*?)}#s', $itemhtml, $hits);
if ($match) {
	$lang = JFactory::getLanguage();
	$lang->load('com_k2');
  for ($i = 0; $i < $match; $i++) {
		$tag2 = $hits[0][$i]; $tagparam = trim($hits[1][$i]);
    $string = $tagparam ? $tagparam : 'K2_READ_MORE';
		$itemhtml = str_ireplace($tag2, JText::_($string), $itemhtml);
  }
}
