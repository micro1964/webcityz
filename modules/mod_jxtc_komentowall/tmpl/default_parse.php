<?php
/*
	JoomlaXTC Komento Wall

	version 1.3.0

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


$rawcomment = $item->comment;
//$modified_date = $item->modified;
//$created_date = $item->created;

$item = Komento::getHelper('comment')->process($item, 1);
$komento_user = new KomentoProfile($item->created_by);

$avatarimgurl = $komento_user->getAvatar();
$authorlink = $komento_user->getProfileLink();

$avatarimg = '<img src="' . $avatarimgurl . '" border="0" width="' . $avatarw . '" height="' . $avatarh . '" />';

$itemhtml = str_ireplace('{id}', $item->id, $itemhtml);
$itemhtml = str_ireplace('{processed_comment}', $item->comment, $itemhtml);
$itemhtml = str_ireplace('{rawcomment}', $rawcomment, $itemhtml);

$itemhtml = str_ireplace('{email}', $item->email, $itemhtml);
$itemhtml = str_ireplace('{url}', $item->url, $itemhtml);
$itemhtml = str_ireplace('{ip}', $item->ip, $itemhtml);

$fmtDate = KomentoDateHelper::getLapsedTime($item->created->toFormat());

$itemhtml = str_ireplace('{created}', $fmtDate, $itemhtml);
$itemhtml = str_ireplace('{component}', $item->componenttitle, $itemhtml);
$itemhtml = str_ireplace('{childs}', $item->childs, $itemhtml);
$itemhtml = str_ireplace('{contenttitle}', $item->contenttitle, $itemhtml);
$itemhtml = str_ireplace('{link}', $item->pagelink, $itemhtml);

$itemhtml = str_ireplace('{authorusername}', $item->uusername, $itemhtml);
$itemhtml = str_ireplace('{authorname}', $item->name, $itemhtml);

$itemhtml = str_ireplace('{avatarurl}', $avatarimgurl, $itemhtml);
$itemhtml = str_ireplace('{avatar}', $avatarimg, $itemhtml);
$itemhtml = str_ireplace('{authorurl}', $authorlink, $itemhtml);
$itemhtml = str_ireplace('{index}', $index, $itemhtml);

// New tag parse model (alpha)
$tagmodel = array(
	'title' => strip_tags($item->title),
	'comment' => strip_tags($item->comment),
);

foreach ($tagmodel as $tag => $value) {
	$regexp = '#{'.$tag.' (.*?)}#s';
	$match = preg_match_all($regexp, $itemhtml, $hits);
	if ($match) {
		for ($i = 0; $i < $match; $i++) {
			$tag2 = $hits[0][$i];
			$tagparam = trim($hits[1][$i]);

			if (is_numeric($tagparam) && strlen($value) > $tagparam) {
				$value = JString::trim(JString::substr($value, 0, $tagparam));
			}

			$itemhtml = str_replace($tag2, $value, $itemhtml);
		}
	}
	$itemhtml = str_ireplace('{'.$tag.'}',$value, $itemhtml);
}

if (is_string($item->modified) && $item->modified == '0000-00-00 00:00:00') { $item->modified = $item->created; }
$tagmodel = array(
  'date' => $item->created,
  'moddate' => $item->modified,
);

foreach ($tagmodel as $tag => $value) {
	$regexp = '#{'.$tag.' (.*?)}#s';
	$match = preg_match_all($regexp, $itemhtml, $hits);
	if ($match) {
		for ($i = 0; $i < $match; $i++) {
			$tag2 = $hits[0][$i];
			$tagparam = trim($hits[1][$i]);

			$format = $tagparam ? $tagparam : $dateformat;

			if (is_string($value)) {
				$itemhtml = str_replace($tag2, JFactory::getDate($value)->calendar($format), $itemhtml);
			} else {
				$itemhtml = str_replace($tag2, JFactory::getDate($value->toFormat())->calendar($format), $itemhtml);
			}
		}
	}
	if (is_string($value)) {
		$itemhtml = str_ireplace('{'.$tag.'}', JFactory::getDate($value)->calendar($dateformat), $itemhtml);
	} else {
		$itemhtml = str_ireplace('{'.$tag.'}', JFactory::getDate($value->toFormat())->calendar($dateformat), $itemhtml);
	}
}
?>