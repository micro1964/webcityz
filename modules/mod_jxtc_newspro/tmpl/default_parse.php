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


// Check and perform Reading List support

if ($enablerl) {
	require_once JPATH_ROOT.'/administrator/components/com_jxtcreadinglist/helper.php';
	$readinglist = jxtcrlHelper::getPluginButton($item->id,'com_content','jxtcreadinglist');
} else $readinglist = '';

// Category Image
$catparams = json_decode($item->cat_params);
$cat_image = isset($catparams->image) ? $live_site.$catparams->image : '';

//Get tags
if (stripos($itemhtml,'{tags}') !== false) {
	$aux = new stdClass();
	$aux->tags = new JHelperTags;
	$aux->tags->getItemTags('com_content.article' , $item->id);
	$aux->tagLayout = new JLayoutFile('joomla.content.tags');
	$tagsHTML = $aux->tagLayout->render($aux->tags->itemTags);
} else { $tagsHTML = ''; }

// Article image
$ini=JString::strpos(strtolower($item->introtext),'<img');
if ($ini === false) $img = '';
else {
	$pos = JString::strpos($item->introtext,'src="',$ini)+5;
	$fin = JString::strpos($item->introtext,'"',$pos);
	$img = JString::substr($item->introtext,$pos,$fin-$pos);
	$fin = JString::strpos($item->introtext,'>',$fin);
}

$ini=JString::strpos(strtolower($item->fulltext),'<img');
if ($ini === false) $fullimg = '';
else {
	$pos = JString::strpos($item->fulltext,'src="',$ini)+5;
	$fin = JString::strpos($item->fulltext,'"',$pos);
	$fullimg = JString::substr($item->fulltext,$pos,$fin-$pos);
	$fin = JString::strpos($item->fulltext,'>',$fin);
}

$intronoimage = $item->introtext;
while (($ini = JString::strpos($intronoimage,'<img')) !== false) {
	if (($fin = JString::strpos($intronoimage,'>',$ini)) === false) { break; }
	$intronoimage = JString::substr_replace($intronoimage,'',$ini,$fin-$ini+1);
}

$fullnoimage = $item->fulltext;
while (($ini = JString::strpos($fullnoimage,'<img')) !== false) {
	if (($fin = JString::strpos($fullnoimage,'>',$ini)) === false) { break; }
	$fullnoimage = JString::substr_replace($fullnoimage,'',$ini,$fin-$ini+1);
}

$title = ($rowmaxtitle) ? JString::substr(strip_tags($item->title),0,$rowmaxtitle).$rowmaxtitlesuf : strip_tags($item->title);

$intro = ($rowmaxintro) ? JString::substr(strip_tags($item->introtext),0,$rowmaxintro).$rowmaxintrosuf : strip_tags($item->introtext);

$rawfulltext=$item->fulltext;
$fulltext=strip_tags($item->fulltext);

if (!empty($rowtextbrk)) {
	$pos = JString::strpos($rawfulltext,$rowtextbrk);
	if ($pos !== false) {
		$rawfulltext=substr($rawfulltext,0,$pos+strlen($rowtextbrk));
	}
	$pos = JString::strpos($fulltext,$rowtextbrk);
	if ($pos !== false) {
		$fulltext=JString::substr($fulltext,0,$pos+strlen($rowtextbrk));
	}

	$pos = JString::strpos($intronoimage,$rowtextbrk);
	if ($pos !== false) {
		$intronoimage=JString::substr($intronoimage,0,$pos+strlen($rowtextbrk));
	}
	$pos = JString::strpos($intro,$rowtextbrk);
	if ($pos !== false) {
		$intro=JString::substr($intro,0,$pos+strlen($rowtextbrk));
	}
}

if (!empty($rowmaxtext)) {
	$fulltext = JString::trim(JString::substr($fulltext,0,$rowmaxtext)).$rowmaxtextsuf;
	$rawfulltext = JString::trim(JString::substr($rawfulltext,0,$rowmaxtext)).$rowmaxtextsuf;
}
$userid = $item->created_by;
$avatarimg='';
$authorlink='';
$articlelink = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
$categorylink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug));

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

$comments=0;
switch ($comcompat) {
	case 'none':
	break;
	case 'joocomments':
		$db->setQuery("SELECT count(*) from #__joocomments WHERE article_id = $item->id AND published=1");
		$comments = (int) $db->loadResult();
	break;
	case 'jcomments':
		$db->setQuery("SELECT count(*) from #__jcomments WHERE object_id = $item->id AND object_group='com_content' AND published=1");
		$comments = (int) $db->loadResult();
	break;
	case 'komento':
		$db->setQuery("SELECT count(*) from #__komento_comments WHERE component = 'com_content' AND cid = $item->id AND published=1");
		$comments = (int) $db->loadResult();
	break;
}

$images = json_decode($item->images);
if (isset($images->image_intro)) {
	$articleintroimageurl = $live_site.$images->image_intro;
	$articleintroimagealt = $images->image_intro_alt;
	$articleintroimagecaption = $images->image_intro_caption;
	$articleintroimage = '<img src="'.$articleintroimageurl.'" alt="'.$articleintroimagealt.'" />';
}
else {
	$articleintroimageurl = '';
	$articleintroimagealt = '';
	$articleintroimagecaption = '';
	$articleintroimage = '';
}
if (isset($images->image_fulltext)) {
	$articlefulltextimageurl = $live_site.$images->image_fulltext;
	$articlefulltextimagealt = $images->image_fulltext_alt;
	$articlefulltextimagecaption = $images->image_fulltext_caption;
	$articlefulltextimage = '<img src="'.$articlefulltextimageurl.'" alt="'.$articlefulltextimagealt.'" />';
}
else {
	$articlefulltextimageurl = '';
	$articlefulltextimagealt = '';
	$articlefulltextimagecaption = '';
	$articlefulltextimage = '';
}

$urls = json_decode($item->urls);
if (isset($urls->urla)) {
	$urla = $urls->urla;
	$urlatext = $urls->urlatext;
	$targeta = $urls->targeta;
	$linka = npMakeLink($urla,$urlatext,$targeta);
}
else {
	$urla = '';
	$urlatext = '';
	$targeta = '';
	$linka = '';
}
if (isset($urls->urlb)) {
	$urlb = $urls->urlb;
	$urlbtext = $urls->urlbtext;
	$targetb = $urls->targetb;
	$linkb = npMakeLink($urlb,$urlbtext,$targetb);
}
else {
	$urlb = '';
	$urlbtext = '';
	$targetb = '';
	$linkb = '';
}
if (isset($urls->urlc)) {
	$urlc = $urls->urlc;
	$urlctext = $urls->urlctext;
	$targetc = $urls->targetc;
	$linkc = npMakeLink($urlc,$urlctext,$targetc);
}
else {
	$urlc = '';
	$urlctext = '';
	$targetc = '';
	$linkc = '';
}

$itemhtml = str_ireplace('{articlefulltextimageurl}', $articlefulltextimageurl, $itemhtml );
$itemhtml = str_ireplace('{articlefulltextimage}', $articlefulltextimage, $itemhtml );
$itemhtml = str_ireplace('{articleintroimageurl}', $articleintroimageurl, $itemhtml );
$itemhtml = str_ireplace('{articleintroimage}', $articleintroimage, $itemhtml );
$itemhtml = str_ireplace('{authorid}', $item->authorid, $itemhtml );
$itemhtml = str_ireplace('{authorprofile}', $authorlink, $itemhtml  );
$itemhtml = str_ireplace('{avatar}', $avatarimg, $itemhtml  );
$itemhtml = str_ireplace('{category_image}', $cat_image, $itemhtml );
$itemhtml = str_ireplace('{category_link}', $categorylink, $itemhtml );
$itemhtml = str_ireplace('{categoryid}', $item->cat_id, $itemhtml );
$itemhtml = str_ireplace('{catid}', $item->catid, $itemhtml );
$itemhtml = str_ireplace('{comments}', $comments, $itemhtml );
$itemhtml = str_ireplace('{fullimage}', $fullimg, $itemhtml );
$itemhtml = str_ireplace('{hits}', $item->hits, $itemhtml );
$itemhtml = str_ireplace('{id}', $item->id, $itemhtml );
$itemhtml = str_ireplace('{index}', $index, $itemhtml  );
$itemhtml = str_ireplace('{introimage}', $img, $itemhtml );
$itemhtml = str_ireplace('{linkaurl}', $urla, $itemhtml );
$itemhtml = str_ireplace('{linka}', $linka, $itemhtml );
$itemhtml = str_ireplace('{linkburl}', $urlb, $itemhtml );
$itemhtml = str_ireplace('{linkb}', $linkb, $itemhtml );
$itemhtml = str_ireplace('{linkcurl}', $urlc, $itemhtml );
$itemhtml = str_ireplace('{linkc}', $linkc, $itemhtml );
$itemhtml = str_ireplace('{link}', $articlelink, $itemhtml );
$itemhtml = str_ireplace('{readinglist}', $readinglist, $itemhtml );
$itemhtml = str_ireplace('{tags}', $tagsHTML, $itemhtml );

// strings
$tagmodel = array(
	'alias' => $item->alias,
	'articlefulltextimagealt' => $articlefulltextimagealt,
	'articlefulltextimagecaption' => $articlefulltextimagecaption,
	'articleintroimagealt' => $articleintroimagealt,
	'articleintroimagecaption' => $articleintroimagecaption,
	'author' => $item->author,
	'author_alias' => $item->created_by_alias,
	'category' => $item->cat_title,
	'category_alias' => $item->cat_alias,
	'category_description' => $item->cat_description,
	'category_description_text' => strip_tags($item->cat_description),
	'fullnoimage' => $fullnoimage,
	'fulltext' => $fulltext,
	'intro' => $item->introtext,
	'intronoimage' => $intronoimage,
	'introtext' => $intro,
	'linkatext' => $urlatext,
	'linkbtext' => $urlbtext,
	'linkctext' => $urlctext,
	'rawfulltext' => $rawfulltext,
	'title' => $title,
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
      $itemhtml = str_replace($tag2, $value, $itemhtml);
    }
  }
	$itemhtml = str_ireplace('{'.$tag.'}',$value, $itemhtml);
}

//dates
$tagmodel = array(
	'date' => $item->created,
	'moddate' => $item->modified,
	'publish_up' => $item->publish_up,
	'publish_down' => $item->publish_down
);

foreach ($tagmodel as $tag => $value) {
  $match = preg_match_all('#{'.$tag.' (.*?)}#s', $itemhtml, $hits);
  if ($match) {
    for ($i = 0; $i < $match; $i++) {
			$tag2 = $hits[0][$i]; $tagparam = trim($hits[1][$i]);
      $format = $tagparam ? $tagparam : $dateformat;
			$itemhtml = str_replace($tag2, JHtml::_('date', $value, $format), $itemhtml);
    }
  }
	$itemhtml = str_ireplace('{'.$tag.'}', JHtml::_('date', $value, $dateformat), $itemhtml);
}

//language
$match = preg_match_all('#{language(.*?)}#s', $itemhtml, $hits);
if ($match) {
	$lang = JFactory::getLanguage();
	$lang->load('com_content');
  for ($i = 0; $i < $match; $i++) {
		$tag2 = $hits[0][$i]; $tagparam = trim($hits[1][$i]);
    $string = $tagparam ? $tagparam : 'COM_CONTENT_READ_MORE';
		$itemhtml = str_replace($tag2, JText::_($string), $itemhtml);
  }
}

// fields
$match = preg_match_all('#{field(.*?)}#s', $itemhtml, $hits);
if ($match) {
  JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
  $fields = array(); foreach(FieldsHelper::getFields('com_content.article', $item, true) as $field) {
  	$fields[$field->id] = $field; $fields[$field->name] = $field;
  }
  for ($i = 0; $i < $match; $i++) {
		$tag2 = $hits[0][$i]; $tagparam = trim($hits[1][$i]);
		$args = explode(' ', $tagparam);
		$value = isset($args[1]) ? $fields[$args[0]]->$args[1] : $fields[$args[0]]->value;
		$itemhtml = str_replace($tag2, $value, $itemhtml);
  }
}
