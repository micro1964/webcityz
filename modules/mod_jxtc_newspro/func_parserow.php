<?php
/*
	JoomlaXTC Deluxe News Pro

	version 3.20.0

	Copyright (C) 2008,2009,2010,2011 Monev Software LLC.	All Rights Reserved.

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

	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;


// Category Image
$catparams = json_decode($item->cat_params);
$cat_image = isset($catparams->image) ? $catparams->image : '';

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
}

if (!empty($rowmaxtext)) {
	$fulltext = JString::trim(JString::substr($fulltext,0,$rowmaxtext)).$rowmaxtextsuf;
	$rawfulltext = JString::trim(JString::substr($rawfulltext,0,$rowmaxtext)).$rowmaxtextsuf;
}
$avatarw = $params->get('avatarw');
$avatarh = $params->get('avatarh');
$userid = $item->created_by;
$avatarimg='';
$authorlink='';
$articlelink = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
$categorylink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug));

switch ($params->get('compat','none')) {
	case 'none':
	break;
	case 'cb':
		$db->setQuery("SELECT avatar from #__comprofiler WHERE user_id = '$userid'");
		$avatar = $db->loadResult();
		$avatarimg = empty($avatar) ? '' : "<img src=\"".$live_site."components/com_comprofiler/images/$avatar\" border=\"0\" width=\"$avatarw\" height=\"$avatarh\" />";
		$db->setQuery("SELECT id from #__components WHERE link = 'option=com_comprofiler' and enabled='1'");
		$itemid = $db->loadResult();if ($itemid) { $itemid = '&Itemid='.$itemid; }
		$authorlink = JRoute::_($live_site.'index.php?option=com_comprofiler&view=profile&userid='.$userid.$itemid);
	break;
	case 'jomsoc':
		$db->setQuery("SELECT avatar from #__community_users WHERE userid = '$userid'");
		$avatar = $db->loadResult();
		$avatarimg = empty($avatar) ? '' : "<img src=\"$live_site$avatar\" border=\"0\" width=\"$avatarw\" height=\"$avatarh\" />";
		$db->setQuery("SELECT id from #__components WHERE link = 'option=com_community' and enabled='1'");
		$itemid = $db->loadResult();if ($itemid) { $itemid = '&Itemid='.$itemid; }
		$authorlink = JRoute::_($live_site.'index.php?option=com_community&view=profile&userid='.$userid.$itemid);
	break;
//	case 'ido':
//		$db->setQuery("SELECT avatar from #__idoblog_users WHERE iduser = '$userid'");
//		$avatar = $db->loadResult();
//		$avatarimg = empty($avatar) ? '' : "<img src=\"".$live_site."images/idoblog/$avatar\" border=\"0\" width=\"$avatarw\" height=\"$avatarh\" />";
//		$db->setQuery("SELECT id from #__components WHERE link = 'option=com_idoblog' and enabled='1'");
//		$itemid = $db->loadResult();if ($itemid) { $itemid = '&Itemid='.$itemid; }
//		$authorlink = JRoute::_($live_site.'index.php?option=com_idoblog&task=profile&userid='.$userid.$itemid);
//	break;
//	case 'myblog':
//		require_once( JPATH_ROOT.DS.'components'.DS.'com_myblog'.DS.'modules'.DS.'mod_myblog.php' );
//		$objModule = new MyblogModule();
//		$avatarimg = $objModule->_getAvatar( $userid );
//		$db->setQuery("SELECT id from #__components WHERE link = 'option=com_idoblog' and enabled='1'");
//		$itemid = $db->loadResult();if ($itemid) { $itemid = '&Itemid='.$itemid; }
//		$authorlink = JRoute::_("index.php?option=com_myblog&blogger=".urlencode($item->author)."&Itemid=".$objModule->myGetItemId());
//		$articlelink = myGetPermalinkUrl($item->id);
//		$categorylink = JRoute::_('index.php?option=com_myblog&task=tag&category='.$item->catid.'&Itemid='.$objModule->myGetItemId() );
//	break;
//	case 'fb':
//		$db->setQuery("SELECT avatar from #__fb_users WHERE userid = '$userid'");
//		$avatar = $db->loadResult();
//		$avatarimg = empty($avatar) ? '': "<img src=\"".$live_site."images/fbfiles/avatars/$avatar\" border=\"0\" width=\"$avatarw\" height=\"$avatarh\" />";
//		$db->setQuery("SELECT id from #__components WHERE link = 'option=com_fireboard' and enabled='1'");
//		$itemid = $db->loadResult();if ($itemid) { $itemid = '&Itemid='.$itemid; }
//		$authorlink = JRoute::_($live_site.'index.php?option=com_fireboard&func=fbprofile&task=showprf&userid='.$userid.$itemid);
//	break;
	case 'kunena':
		$db->setQuery("SELECT avatar from #__kunena_users WHERE userid = '$userid'");
		$avatar = $db->loadResult();
		$avatarimg = empty($avatar) ? '' : "<img src=\"".$live_site."media/kunena/avatars/resized/size200/$avatar\" border=\"0\" width=\"$avatarw\" height=\"$avatarh\" />";
		$db->setQuery("SELECT id from #__components WHERE link = 'option=com_kunena' and enabled='1'");
		$itemid = $db->loadResult();if ($itemid) { $itemid = '&Itemid='.$itemid; }
		$authorlink = JRoute::_($live_site.'index.php?option=com_kunena&func=profile&userid='.$userid.$itemid);
	break;
}

$comments=0;
switch ($params->get('comcompat','none')) {
	case 'none':
	break;
	case 'joocomments':
		$db->setQuery("SELECT count(*) from #__joocomments WHERE article_id = $item->id AND published=1");
		$comments = (int) $db->loadResult();
	break;
}

$itemhtml = str_replace( '{link}', $articlelink, $itemhtml );
$itemhtml = str_replace( '{title}', $title, $itemhtml );
$itemhtml = str_replace( '{intro}', $item->introtext, $itemhtml );
$itemhtml = str_replace( '{intronoimage}', $intronoimage, $itemhtml );
$itemhtml = str_replace( '{fullnoimage}', $fullnoimage, $itemhtml );
$itemhtml = str_replace( '{rawfulltext}', $rawfulltext, $itemhtml );
$itemhtml = str_replace( '{fulltext}', $fulltext, $itemhtml );
$itemhtml = str_replace( '{introtext}', $intro, $itemhtml );
$itemhtml = str_replace( '{introimage}', $img, $itemhtml );
$itemhtml = str_replace( '{fullimage}', $fullimg, $itemhtml );
$itemhtml = str_replace( '{category}', $item->cat_title, $itemhtml );
$itemhtml = str_replace( '{category_description}', $item->cat_description, $itemhtml );
$itemhtml = str_replace( '{category_description_text}', strip_tags($item->cat_description), $itemhtml );
$itemhtml = str_replace( '{category_image}', $cat_image, $itemhtml );
$itemhtml = str_replace( '{category_link}', $categorylink, $itemhtml );
$itemhtml = str_replace( '{date}', date($dateformat,$item->created), $itemhtml );
$itemhtml = str_replace( '{moddate}', date($dateformat,$item->modified), $itemhtml );
$itemhtml = str_replace( '{author}', $item->author, $itemhtml );
$itemhtml = str_replace( '{authorid}', $item->authorid, $itemhtml );
$itemhtml = str_replace( '{avatar}', $avatarimg, $itemhtml  );
$itemhtml = str_replace( '{authorprofile}', $authorlink, $itemhtml  );
$itemhtml = str_replace( '{index}', $index, $itemhtml  );
$itemhtml = str_replace( '{hits}', $item->hits, $itemhtml );
$itemhtml = str_replace( '{comments}', $comments, $itemhtml );
$itemhtml = str_replace( '{author_alias}', $item->created_by_alias, $itemhtml );

while (($ini=JString::strpos($itemhtml,"{date")) !== false) {
	$fin = JString::strpos($itemhtml,"}",$ini);
	$filter=JString::substr($itemhtml,$ini,$fin-$ini+1);
	list($null,$fmt)=explode(' ',JString::substr($filter,1,-1));
	$val=date(JString::trim($fmt),$item->created);
	$itemhtml = str_replace($filter,$val,$itemhtml);
}

?>