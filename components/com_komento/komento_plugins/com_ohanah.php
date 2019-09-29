<?php
/**
 * @package		Komento
 * @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Komento is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'abstract.php' );

class KomentoComOhanah extends KomentoExtension
{
	public $_item;
	public $_map = array(
		'id'			=> 'id',
		'title'			=> 'title',
		'hits'			=> 'hits',
		'created_by'	=> 'created_by',
		'catid'			=> 'ohanah_category_id',
		'permalink'		=> 'permalink'
		);

	public function __construct( $component )
	{
		parent::__construct( $component );
	}

	/**
	 * Method to load a plugin object by content id number
	 *
	 * @access	public
	 *
	 * @return	object	Instance of this class
	 */
	public function load( $cid )
	{
		static $instances = null;

		if( is_null($instances) )
		{
			$instances = array();
		}

		$cid		= !$cid ? JRequest::getInt( 'id' ) : $cid;

		if( !array_key_exists($cid, $instances) )
		{
			$db		= Komento::getDBO();
			$query	= 'SELECT * FROM ' . $db->nameQuote('#__ohanah_events')
					. ' WHERE ' . $db->nameQuote('ohanah_event_id') . '=' . $db->quote($cid);

			$db->setQuery( $query );

			if( !$this->_item = $db->loadObject() )
			{
				return $this->onLoadArticleError( $cid );
			}

			// Ohanah does not store hits for some reason.
			$this->_item->hits		= 0;

			$link = 'index.php?option=com_ohanah&view=event&id=' . $this->_item->ohanah_event_id;
			$this->_item->permalink = $this->prepareLink( $link );

			$instances[$cid] = $this->_item;
		}

		$this->_item = $instances[$cid];

		return $this;
	}

	public function getContentIds( $categories = '' )
	{
		$db		= Komento::getDBO();
		$query = '';

		if( empty( $categories ) )
		{
			$query = 'SELECT `ohanah_event_id` FROM ' . $db->nameQuote( '#__ohanah_events' ) . ' ORDER BY `ohanah_event_id`';
		}
		else
		{
			if( is_array( $categories ) )
			{
				$categories = implode( ',', $categories );
			}

			$query = 'SELECT `ohanah_event_id` FROM ' . $db->nameQuote( '#__ohanah_events' ) . ' WHERE `ohanah_category_id` IN (' . $categories . ') ORDER BY `ohanah_event_id`';
		}

		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	public function getCategories()
	{
		$db		= Komento::getDBO();
		$query	= 'SELECT c.ohanah_category_id AS id, c.title'
				. ' FROM `#__ohanah_categories` as c';
		$db->setQuery( $query );
		$categories	= $db->loadObjectList();

		return $categories;
	}

	public function isListingView()
	{
		$views = array( 'events' );

		return in_array(JRequest::getCmd('view'), $views);
	}

	public function isEntryView()
	{
		return JRequest::getCmd('view') == 'event';
	}

	public function onExecute( &$article, $html, $view, $options = array() )
	{
		if( $view == 'listing' )
		{
			$article->text	.= $html;
			return $html;
		}

		if( $view == 'entry' )
		{
			$article->text	.= $html;
			return $html;
		}
	}

	public function getEventTrigger()
	{
		$entryTrigger = ( Komento::joomlaVersion() > '1.5' ) ? 'onContentPrepare' : 'onPrepareContent';

		return $entryTrigger;
	}

	public function onBeforeLoad( $eventTrigger, $context, &$article, &$params, &$page, &$options )
	{
		if( !($article instanceof JTable) )
		{
			return false;
		}

		$cid	= JRequest::getInt( 'id' );

		if( $cid )
		{
			$article->id = $cid;
		}
		else
		{
			// bad workarount :(
			$text	= $article->text;
			$text	= str_ireplace('<!--{emailcloak=off}-->', '', $text);
			$db		= Komento::getDBO();
			$query	= 'SELECT ohanah_event_id FROM `#__ohanah_events` WHERE description = ' . $db->quote( $text );
			$db->setQuery( $query );
			$article->id	= $db->loadResult();
		}

		if( !$article->id )
		{
			return false;
		}

		return true;
	}
}
