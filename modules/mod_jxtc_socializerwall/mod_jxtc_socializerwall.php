<?php
/*
	JoomlaXTC Socializer Wall

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


$live_site = JURI::base();
$user = JFactory::getUser();
$db = JFactory::getDBO();
$doc = JFactory::getDocument();
$moduleDir = 'mod_jxtc_socializerwall';

$columns = $params->get('columns', 3);
$rows = $params->get('rows', 3);
$pages = $params->get('pages', 1);
$offset = $params->get('offset', 0);

$moreclone = $params->get('moreclone', 0);
$moreqty = $params->get('moreqty', 0);
$morecols	= trim( $params->get('morecols',1));
$morelegend	= trim($params->get('moretext', ''));
$morelegendcolor	= $params->get('morergb','cccccc');
$moretemplate	= $params->get('moretemplate', '');

$template = $params->get('template', '');
$moduletemplate = trim($params->get('moduletemplate', '{mainarea}'));
$itemtemplate = trim($params->get('itemtemplate', '{username}'));
if ($template && $template != -1) {
  $moduletemplate = file_get_contents(JPATH_ROOT.'/modules/'.$moduleDir.'/templates/'.$template.'/module.html');
  $itemtemplate = file_get_contents(JPATH_ROOT.'/modules/'.$moduleDir.'/templates/'.$template.'/element.html');
	$moretemplate=file_get_contents(JPATH_ROOT.'/modules/'.$moduleDir.'/templates/'.$template.'/more.html');
  if (file_exists(JPATH_ROOT.'/modules/'.$moduleDir.'/templates/'.$template.'/template.css')) {
      $doc->addStyleSheet($live_site.'modules/'.$moduleDir.'/templates/'.$template.'/template.css', 'text/css');
  }
}

$icons = array(
	'fivehundredpx' => array('tag' => '{fivehundredpx}', 'enabled' => 1, 'icons' => array('regular' => '&#xe000;', 'circle' => '&#xe200;', 'rounded' => '&#xe400;'), 'title' => '500px'),
	'aboutme'       => array('tag' => '{aboutme}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe001;', 'circle' => '&#xe201;', 'rounded' => '&#xe401;'), 'title' => 'about.me'),
	'addme'         => array('tag' => '{addme}'        , 'enabled' => 0, 'icons' => array('regular' => '&#xe002;', 'circle' => '&#xe202;', 'rounded' => '&#xe402;'), 'title' => 'addme'),
	'amazon'        => array('tag' => '{amazon}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe003;', 'circle' => '&#xe203;', 'rounded' => '&#xe403;'), 'title' => 'Amazon'),
	'aol'           => array('tag' => '{aol}'          , 'enabled' => 1, 'icons' => array('regular' => '&#xe004;', 'circle' => '&#xe204;', 'rounded' => '&#xe404;'), 'title' => 'AOL'),
	'appstorealt'   => array('tag' => '{appstorealt}'  , 'enabled' => 0, 'icons' => array('regular' => '&#xe005;', 'circle' => '&#xe205;', 'rounded' => '&#xe405;'), 'title' => 'appstorealt'),
	'appstore'      => array('tag' => '{appstore}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe006;', 'circle' => '&#xe206;', 'rounded' => '&#xe406;'), 'title' => 'App Store'),
	'apple'         => array('tag' => '{apple}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe007;', 'circle' => '&#xe207;', 'rounded' => '&#xe407;'), 'title' => 'Apple'),
	'bebo'          => array('tag' => '{bebo}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe008;', 'circle' => '&#xe208;', 'rounded' => '&#xe408;'), 'title' => 'bebo'),
	'behance'       => array('tag' => '{behance}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe009;', 'circle' => '&#xe209;', 'rounded' => '&#xe409;'), 'title' => 'Behance'),
	'bing'          => array('tag' => '{bing}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe010;', 'circle' => '&#xe210;', 'rounded' => '&#xe410;'), 'title' => 'Bing'),
	'blip'          => array('tag' => '{blip}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe011;', 'circle' => '&#xe211;', 'rounded' => '&#xe411;'), 'title' => 'Blip'),
	'blogger'       => array('tag' => '{blogger}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe012;', 'circle' => '&#xe212;', 'rounded' => '&#xe412;'), 'title' => 'Blogger'),
	'coroflot'      => array('tag' => '{coroflot}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe013;', 'circle' => '&#xe213;', 'rounded' => '&#xe413;'), 'title' => 'Coroflot'),
	'daytum'        => array('tag' => '{daytum}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe014;', 'circle' => '&#xe214;', 'rounded' => '&#xe414;'), 'title' => 'Daytum'),
	'delicious'     => array('tag' => '{delicious}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe015;', 'circle' => '&#xe215;', 'rounded' => '&#xe415;'), 'title' => 'Delicious'),
	'designbump'    => array('tag' => '{designbump}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe016;', 'circle' => '&#xe216;', 'rounded' => '&#xe416;'), 'title' => 'Design Bump'),
	'designfloat'   => array('tag' => '{designfloat}'  , 'enabled' => 1, 'icons' => array('regular' => '&#xe017;', 'circle' => '&#xe217;', 'rounded' => '&#xe417;'), 'title' => 'Design Float'),
	'deviantart'    => array('tag' => '{deviantart}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe018;', 'circle' => '&#xe218;', 'rounded' => '&#xe418;'), 'title' => 'deviantART'),
	'diggalt'       => array('tag' => '{diggalt}'      , 'enabled' => 0, 'icons' => array('regular' => '&#xe019;', 'circle' => '&#xe219;', 'rounded' => '&#xe419;'), 'title' => 'Diggalt'),
	'digg'          => array('tag' => '{digg}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe020;', 'circle' => '&#xe220;', 'rounded' => '&#xe420;'), 'title' => 'Digg'),
	'dribble'       => array('tag' => '{dribble}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe021;', 'circle' => '&#xe221;', 'rounded' => '&#xe421;'), 'title' => 'Dribbble'),
	'drupal'        => array('tag' => '{drupal}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe022;', 'circle' => '&#xe222;', 'rounded' => '&#xe422;'), 'title' => 'Drupal'),
	'ebay'          => array('tag' => '{ebay}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe023;', 'circle' => '&#xe223;', 'rounded' => '&#xe423;'), 'title' => 'eBay'),
	'email'         => array('tag' => '{email}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe024;', 'circle' => '&#xe224;', 'rounded' => '&#xe424;'), 'title' => 'Email'),
	'emberapp'      => array('tag' => '{emberapp}'     , 'enabled' => 0, 'icons' => array('regular' => '&#xe025;', 'circle' => '&#xe225;', 'rounded' => '&#xe425;'), 'title' => 'emberapp'),
	'etsy'          => array('tag' => '{etsy}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe026;', 'circle' => '&#xe226;', 'rounded' => '&#xe426;'), 'title' => 'Etsy'),
	'facebook'      => array('tag' => '{facebook}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe027;', 'circle' => '&#xe227;', 'rounded' => '&#xe427;'), 'title' => 'Facebook'),
	'feedburner'    => array('tag' => '{feedburner}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe028;', 'circle' => '&#xe228;', 'rounded' => '&#xe428;'), 'title' => 'feedBurner'),
	'flickr'        => array('tag' => '{flickr}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe029;', 'circle' => '&#xe229;', 'rounded' => '&#xe429;'), 'title' => 'Flickr'),
	'foodspotting'  => array('tag' => '{foodspotting}' , 'enabled' => 1, 'icons' => array('regular' => '&#xe030;', 'circle' => '&#xe230;', 'rounded' => '&#xe430;'), 'title' => 'Foodspotting'),
	'forrst'        => array('tag' => '{forrst}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe031;', 'circle' => '&#xe231;', 'rounded' => '&#xe431;'), 'title' => 'Forrst'),
	'foursquare'    => array('tag' => '{foursquare}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe032;', 'circle' => '&#xe232;', 'rounded' => '&#xe432;'), 'title' => 'Foursquare'),
	'friendsfeed'   => array('tag' => '{friendsfeed}'  , 'enabled' => 1, 'icons' => array('regular' => '&#xe033;', 'circle' => '&#xe233;', 'rounded' => '&#xe433;'), 'title' => 'Friendfeed'),
	'friendstar'    => array('tag' => '{friendstar}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe034;', 'circle' => '&#xe234;', 'rounded' => '&#xe434;'), 'title' => 'Friendster'),
	'gdgt'          => array('tag' => '{gdgt}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe035;', 'circle' => '&#xe235;', 'rounded' => '&#xe435;'), 'title' => 'gdgt'),
	'github'        => array('tag' => '{github}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe036;', 'circle' => '&#xe236;', 'rounded' => '&#xe436;'), 'title' => 'GitHub'),
	'githubalt'     => array('tag' => '{githubalt}'    , 'enabled' => 0, 'icons' => array('regular' => '&#xe037;', 'circle' => '&#xe237;', 'rounded' => '&#xe437;'), 'title' => 'githubalt'),
	'googlebuzz'    => array('tag' => '{googlebuzz}'   , 'enabled' => 0, 'icons' => array('regular' => '&#xe038;', 'circle' => '&#xe238;', 'rounded' => '&#xe438;'), 'title' => 'googlebuzz'),
	'googleplus'    => array('tag' => '{googleplus}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe039;', 'circle' => '&#xe239;', 'rounded' => '&#xe439;'), 'title' => 'Google+'),
	'googletalk'    => array('tag' => '{googletalk}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe040;', 'circle' => '&#xe240;', 'rounded' => '&#xe440;'), 'title' => 'Google Talk'),
	'gowallapin'    => array('tag' => '{gowallapin}'   , 'enabled' => 0, 'icons' => array('regular' => '&#xe041;', 'circle' => '&#xe241;', 'rounded' => '&#xe441;'), 'title' => 'Gowallapin'),
	'gowalla'       => array('tag' => '{gowalla}'      , 'enabled' => 0, 'icons' => array('regular' => '&#xe042;', 'circle' => '&#xe242;', 'rounded' => '&#xe442;'), 'title' => 'gowalla'),
	'grooveshark'   => array('tag' => '{grooveshark}'  , 'enabled' => 1, 'icons' => array('regular' => '&#xe043;', 'circle' => '&#xe243;', 'rounded' => '&#xe443;'), 'title' => 'Grooveshark'),
	'heart'         => array('tag' => '{heart}'        , 'enabled' => 0, 'icons' => array('regular' => '&#xe044;', 'circle' => '&#xe244;', 'rounded' => '&#xe444;'), 'title' => 'Heart'),
	'hyves'         => array('tag' => '{hyves}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe045;', 'circle' => '&#xe245;', 'rounded' => '&#xe445;'), 'title' => 'Hyves'),
	'icondock'      => array('tag' => '{icondock}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe046;', 'circle' => '&#xe246;', 'rounded' => '&#xe446;'), 'title' => 'IconDock'),
	'icq'           => array('tag' => '{icq}'          , 'enabled' => 1, 'icons' => array('regular' => '&#xe047;', 'circle' => '&#xe247;', 'rounded' => '&#xe447;'), 'title' => 'ICQ'),
	'identica'      => array('tag' => '{identica}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe048;', 'circle' => '&#xe248;', 'rounded' => '&#xe448;'), 'title' => 'identi.ca'),
	'imessage'      => array('tag' => '{imessage}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe049;', 'circle' => '&#xe249;', 'rounded' => '&#xe449;'), 'title' => 'iMessage'),
	'itunes'        => array('tag' => '{itunes}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe050;', 'circle' => '&#xe250;', 'rounded' => '&#xe450;'), 'title' => 'iTunes'),
	'lastfm'        => array('tag' => '{lastfm}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe051;', 'circle' => '&#xe251;', 'rounded' => '&#xe451;'), 'title' => 'Last.fm'),
	'linkedin'      => array('tag' => '{linkedin}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe052;', 'circle' => '&#xe252;', 'rounded' => '&#xe452;'), 'title' => 'linkedIn'),
	'meetup'        => array('tag' => '{meetup}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe053;', 'circle' => '&#xe253;', 'rounded' => '&#xe453;'), 'title' => 'Meetup'),
	'metacafe'      => array('tag' => '{metacafe}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe054;', 'circle' => '&#xe254;', 'rounded' => '&#xe454;'), 'title' => 'Metacafe'),
	'mixx'          => array('tag' => '{mixx}'         , 'enabled' => 0, 'icons' => array('regular' => '&#xe055;', 'circle' => '&#xe255;', 'rounded' => '&#xe455;'), 'title' => 'mixx'),
	'mobileme'      => array('tag' => '{mobileme}'     , 'enabled' => 0, 'icons' => array('regular' => '&#xe056;', 'circle' => '&#xe256;', 'rounded' => '&#xe456;'), 'title' => 'mobileme'),
	'mrwong'        => array('tag' => '{mrwong}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe057;', 'circle' => '&#xe257;', 'rounded' => '&#xe457;'), 'title' => 'Mister Wong'),
	'msn'           => array('tag' => '{msn}'          , 'enabled' => 1, 'icons' => array('regular' => '&#xe058;', 'circle' => '&#xe258;', 'rounded' => '&#xe458;'), 'title' => 'MSN'),
	'myspace'       => array('tag' => '{myspace}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe059;', 'circle' => '&#xe259;', 'rounded' => '&#xe459;'), 'title' => 'MySpace'),
	'newsvine'      => array('tag' => '{newsvine}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe060;', 'circle' => '&#xe260;', 'rounded' => '&#xe460;'), 'title' => 'Newsvine'),
	'paypal'        => array('tag' => '{paypal}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe061;', 'circle' => '&#xe261;', 'rounded' => '&#xe461;'), 'title' => 'PayPal'),
	'photobucket'   => array('tag' => '{photobucket}'  , 'enabled' => 1, 'icons' => array('regular' => '&#xe062;', 'circle' => '&#xe262;', 'rounded' => '&#xe462;'), 'title' => 'Photobucket'),
	'picasa'        => array('tag' => '{picasa}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe063;', 'circle' => '&#xe263;', 'rounded' => '&#xe463;'), 'title' => 'Picasa'),
	'pinterest'     => array('tag' => '{pinterest}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe064;', 'circle' => '&#xe264;', 'rounded' => '&#xe464;'), 'title' => 'Pinterest'),
	'podcast'       => array('tag' => '{podcast}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe065;', 'circle' => '&#xe265;', 'rounded' => '&#xe465;'), 'title' => 'Podcast'),
	'posterous'     => array('tag' => '{posterous}'    , 'enabled' => 0, 'icons' => array('regular' => '&#xe066;', 'circle' => '&#xe266;', 'rounded' => '&#xe466;'), 'title' => 'posterous'),
	'qik'           => array('tag' => '{qik}'          , 'enabled' => 1, 'icons' => array('regular' => '&#xe067;', 'circle' => '&#xe267;', 'rounded' => '&#xe467;'), 'title' => 'Qik'),
	'quora'         => array('tag' => '{quora}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe068;', 'circle' => '&#xe268;', 'rounded' => '&#xe468;'), 'title' => 'Quora'),
	'reddit'        => array('tag' => '{reddit}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe069;', 'circle' => '&#xe269;', 'rounded' => '&#xe469;'), 'title' => 'Reddit'),
	'retweet'       => array('tag' => '{retweet}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe070;', 'circle' => '&#xe270;', 'rounded' => '&#xe470;'), 'title' => 'Retweet'),
	'rss'           => array('tag' => '{rss}'          , 'enabled' => 1, 'icons' => array('regular' => '&#xe071;', 'circle' => '&#xe271;', 'rounded' => '&#xe471;'), 'title' => 'RSS'),
	'scribd'        => array('tag' => '{scribd}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe072;', 'circle' => '&#xe272;', 'rounded' => '&#xe472;'), 'title' => 'Scribd'),
	'sharethis'     => array('tag' => '{sharethis}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe073;', 'circle' => '&#xe273;', 'rounded' => '&#xe473;'), 'title' => 'ShareThis'),
	'skype'         => array('tag' => '{skype}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe074;', 'circle' => '&#xe274;', 'rounded' => '&#xe474;'), 'title' => 'Skype'),
	'slashdot'      => array('tag' => '{slashdot}'     , 'enabled' => 1, 'icons' => array('regular' => '&#xe075;', 'circle' => '&#xe275;', 'rounded' => '&#xe475;'), 'title' => 'Slashdot'),
	'slideshare'    => array('tag' => '{slideshare}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe076;', 'circle' => '&#xe276;', 'rounded' => '&#xe476;'), 'title' => 'Slideshare'),
	'smugmug'       => array('tag' => '{smugmug}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe077;', 'circle' => '&#xe277;', 'rounded' => '&#xe477;'), 'title' => 'SmugMug'),
	'soundcloud'    => array('tag' => '{soundcloud}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe078;', 'circle' => '&#xe278;', 'rounded' => '&#xe478;'), 'title' => 'SoundCloud'),
	'spotify'       => array('tag' => '{spotify}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe079;', 'circle' => '&#xe279;', 'rounded' => '&#xe479;'), 'title' => 'Spotify'),
	'squidoo'       => array('tag' => '{squidoo}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe080;', 'circle' => '&#xe280;', 'rounded' => '&#xe480;'), 'title' => 'Squidoo'),
	'stackoverflow' => array('tag' => '{stackoverflow}', 'enabled' => 1, 'icons' => array('regular' => '&#xe081;', 'circle' => '&#xe281;', 'rounded' => '&#xe481;'), 'title' => 'Stack Overflow'),
	'star'          => array('tag' => '{star}'         , 'enabled' => 0, 'icons' => array('regular' => '&#xe082;', 'circle' => '&#xe282;', 'rounded' => '&#xe482;'), 'title' => 'Star'),
	'stumbleupon'   => array('tag' => '{stumbleupon}'  , 'enabled' => 1, 'icons' => array('regular' => '&#xe083;', 'circle' => '&#xe283;', 'rounded' => '&#xe483;'), 'title' => 'StumbleUpon'),
	'technorati'    => array('tag' => '{technorati}'   , 'enabled' => 1, 'icons' => array('regular' => '&#xe084;', 'circle' => '&#xe284;', 'rounded' => '&#xe484;'), 'title' => 'Technorati'),
	'tumblr'        => array('tag' => '{tumblr}'       , 'enabled' => 1, 'icons' => array('regular' => '&#xe085;', 'circle' => '&#xe285;', 'rounded' => '&#xe485;'), 'title' => 'Tumblr'),
	'twitterbird'   => array('tag' => '{twitterbird}'  , 'enabled' => 1, 'icons' => array('regular' => '&#xe086;', 'circle' => '&#xe286;', 'rounded' => '&#xe486;'), 'title' => 'Twitter Bird'),
	'twitter'       => array('tag' => '{twitter}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe087;', 'circle' => '&#xe287;', 'rounded' => '&#xe487;'), 'title' => 'Twitter'),
	'viddler'       => array('tag' => '{viddler}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe088;', 'circle' => '&#xe288;', 'rounded' => '&#xe488;'), 'title' => 'Viddler'),
	'vimeo'         => array('tag' => '{vimeo}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe089;', 'circle' => '&#xe289;', 'rounded' => '&#xe489;'), 'title' => 'Vimeo'),
	'virb'          => array('tag' => '{virb}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe090;', 'circle' => '&#xe290;', 'rounded' => '&#xe490;'), 'title' => 'Virb'),
	'www'           => array('tag' => '{www}'          , 'enabled' => 1, 'icons' => array('regular' => '&#xe091;', 'circle' => '&#xe291;', 'rounded' => '&#xe491;'), 'title' => 'WWW'),
	'wikipedia'     => array('tag' => '{wikipedia}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe092;', 'circle' => '&#xe292;', 'rounded' => '&#xe492;'), 'title' => 'Wikipedia'),
	'windows'       => array('tag' => '{windows}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe093;', 'circle' => '&#xe293;', 'rounded' => '&#xe493;'), 'title' => 'Windows'),
	'wordpress'     => array('tag' => '{wordpress}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe094;', 'circle' => '&#xe294;', 'rounded' => '&#xe494;'), 'title' => 'WordPress'),
	'xing'          => array('tag' => '{xing}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe095;', 'circle' => '&#xe295;', 'rounded' => '&#xe495;'), 'title' => 'Xing'),
	'yahoobuzz'     => array('tag' => '{yahoobuzz}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe096;', 'circle' => '&#xe296;', 'rounded' => '&#xe496;'), 'title' => 'Yahoo! Buzz'),
	'yahoo'         => array('tag' => '{yahoo}'        , 'enabled' => 1, 'icons' => array('regular' => '&#xe097;', 'circle' => '&#xe297;', 'rounded' => '&#xe497;'), 'title' => 'Yahoo!'),
	'yelp'          => array('tag' => '{yelp}'         , 'enabled' => 1, 'icons' => array('regular' => '&#xe098;', 'circle' => '&#xe298;', 'rounded' => '&#xe498;'), 'title' => 'Yelp'),
	'youtube'       => array('tag' => '{youtube}'      , 'enabled' => 1, 'icons' => array('regular' => '&#xe099;', 'circle' => '&#xe299;', 'rounded' => '&#xe499;'), 'title' => 'YouTube'),
	'instagram'     => array('tag' => '{instagram}'    , 'enabled' => 1, 'icons' => array('regular' => '&#xe100;', 'circle' => '&#xe300;', 'rounded' => '&#xe500;'), 'title' => 'Instagram'),
);


// Get icon set
$mainiconType = $params->get('iconType', 'regular');
$mainiconSize = $params->get('iconSize', '20px');
$mainfontSize = $mainiconSize ? 'font-size:'.$mainiconSize.';' : '';

$moreiconType = $params->get('moreiconType', 'regular');
$moreiconSize = $params->get('moreiconSize', '20px');
$morefontSize = $moreiconSize ? 'font-size:'.$moreiconSize.';' : '';

// Populate extra fields & find out sort order
$mainItemsList = array();
$moreItemsList = array();
foreach ($icons as $key => $info) {
	if ($icons[$key]['enabled']) { 
		$icons[$key]['link']	= trim($params->get('link_'.$key,''));
		if ($icons[$key]['link']) { 
			$icons[$key]['alias']	= $key;
			$icons[$key]['order']	= $params->get('order_'.$key,0);
			$icons[$key]['pos']		= $params->get('pos_'.$key,'A');
			switch ($icons[$key]['pos']) {
				case 'A':	$mainItemsList[] = $icons[$key]; break;
				case 'O':	$moreItemsList[] = $icons[$key]; break;
			}
		}
	}
}

if (!function_exists('socializerSort')) {
	function socializerSort($a, $b) {
		return strnatcmp($a['order'],$b['order']);
	}
}

// Build HTML

usort($mainItemsList, "socializerSort");
usort($moreItemsList, "socializerSort");

$items = $mainItemsList;
$iconType = $mainiconType;
$iconSize = $mainiconSize;
$fontSize = $mainfontSize;
$class="symbol";

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));

$items = $moreItemsList;
$iconType = $moreiconType;
$iconSize = $moreiconSize;
$fontSize = $morefontSize;
$class="symbol more";

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default').'_more');


$live_site = JURI::root();
$fontCSS = "
@font-face {
    font-family: 'Mono Social Icons Font';
    src: url('".$live_site."modules/mod_jxtc_socializerwall/css/font/MonoSocialIconsFont-1.10.eot');
    src: url('".$live_site."modules/mod_jxtc_socializerwall/css/font/MonoSocialIconsFont-1.10.eot?#iefix') format('embedded-opentype'),
         url('".$live_site."modules/mod_jxtc_socializerwall/css/font/MonoSocialIconsFont-1.10.woff') format('woff'),
         url('".$live_site."modules/mod_jxtc_socializerwall/css/font/MonoSocialIconsFont-1.10.ttf') format('truetype'),
         url('".$live_site."modules/mod_jxtc_socializerwall/css/font/MonoSocialIconsFont-1.10.svg#MonoSocialIconsFont') format('svg');
    src: url('".$live_site."modules/mod_jxtc_socializerwall/css/font/MonoSocialIconsFont-1.10.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

#".$jxtc." span.symbol {
    font-family: 'Mono Social Icons Font';
    -webkit-text-rendering: optimizeLegibility;
    -moz-text-rendering: optimizeLegibility;
    -ms-text-rendering: optimizeLegibility;
    -o-text-rendering: optimizeLegibility;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    -moz-font-smoothing: antialiased;
    -ms-font-smoothing: antialiased;
    -o-font-smoothing: antialiased;
    font-smoothing: antialiased;
    line-height:1;
    $mainfontSize
}
#".$jxtc." span.more {
    $morefontSize
}";
$doc->addStyleDeclaration($fontCSS);

echo '<div id="'.$jxtc.'">'.$modulehtml.'</div>';
