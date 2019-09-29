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

class K2StoreHelperPlugin
{
	/**
	 * Only returns plugins that have a specific event
	 *
	 * @param $eventName
	 * @param $folder
	 * @return array of JTable objects
	 */
	public static function getPluginsWithEvent( $eventName, $folder='K2Store' )
	{
		$return = array();
		if ($plugins = K2StoreHelperPlugin::getPlugins( $folder ))
		{
			foreach ($plugins as $plugin)
			{
				if (K2StoreHelperPlugin::hasEvent( $plugin, $eventName ))
				{
					$return[] = $plugin;
				}
			}
		}
		return $return;
	}

	/**
	 * Returns Array of active Plugins
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getPlugins( $folder='K2Store' )
	{
		$database = JFactory::getDBO();

		$order_query = " ORDER BY ordering ASC ";
		$folder = strtolower( $folder );

		$query = "
			SELECT
				*
			FROM
				#__extensions
			WHERE  enabled = '1'
			AND
				LOWER(`folder`) = '{$folder}'
			{$order_query}
		";

		$database->setQuery( $query );
		$data = $database->loadObjectList();
		return $data;
	}

	/**
	 * Returns HTML
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getPluginsContent( $event, $options, $method='vertical' )
	{
		$text = "";
        jimport('joomla.html.pane');

		if (!$event) {
			return $text;
		}

		$args = array();
		$dispatcher	   = JDispatcher::getInstance();
		$results = $dispatcher->trigger( $event, $options );

		if ( !count($results) > 0 ) {
			return $text;
		}

		// grab content
		switch( strtolower($method) ) {
			case "vertical":
				for ($i=0; $i<count($results); $i++) {
					$result = $results[$i];
					$title = $result[1] ? JText::_( $result[1] ) : JText::_( 'Info' );
					$content = $result[0];

		            // Vertical
		            $text .= '<p>'.$content.'</p>';
				}
			  break;
			case "tabs":
			  break;
		}

		return $text;
	}

	/**
	 * Checks if a plugin has an event
	 *
	 * @param obj      $element    the plugin JTable object
	 * @param string   $eventName  the name of the event to test for
	 * @return unknown_type
	 */
	public static function hasEvent( $element, $eventName )
	{
		$success = false;
		if (!$element || !is_object($element)) {
			return $success;
		}

		if (!$eventName || !is_string($eventName)) {
			return $success;
		}

		// Check if they have a particular event
		$import 	= JPluginHelper::importPlugin( strtolower('K2Store'), $element->element );
		$dispatcher	= JDispatcher::getInstance();
		$result 	= $dispatcher->trigger( $eventName, array( $element ) );
		if (in_array(true, $result, true))
		{
			$success = true;
		}
		return $success;
	}
}