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

class K2StoreHelperBase extends JObject
{
/**
	 * Takes an elements object and converts it to an array that can be binded to a JTable object
	 *
	 * @param $elements is an array of objects with ->name and ->value properties, all posted from a form
	 * @return array[name] = value
	 */
	function elementsToArray( $elements )
	{
		$return = array();
        $names = array();
        $checked_items = array();
        $checked = '';
        if (empty($elements))
        {
            $elements = array();
        }

		foreach (@$elements as $element)
		{
			$isarray = false;
			$name = $element->name;
			$value = $element->value;
			$checked = $element->checked;

			// if the name is an array,
			// attempt to recreate it
			// using the array's name
			if (strpos($name, '['))
			{
				$isarray = true;
				$search = array( '[', ']' );
				$exploded = explode( '[', $name, '2' );
				$index = str_replace( $search, '', $exploded[0]);
				$name = str_replace( $search, '', $exploded[1]);
				if (!empty($index))
				{
                    // track the name of the array
	                if (!in_array($index, $names))
	                {
                        $names[] = $index;
	                }

	                if (empty(${$index}))
	                {
	                    ${$index} = array();
	                }

	                if (!empty($name))
	                {
	                	${$index}[$name] = $value;
	                }
	                else
	                {
                        ${$index}[] = $value;
	                }

				    if ($checked)
                    {
                    	if (empty($checked_items[$index]))
                    	{
                    		$checked_items[$index] = array();
                    	}
                        $checked_items[$index][] = $value;
                    }
				}
			}
            elseif (!empty($name))
			{
				$return[$name] = $value;
			    if ($checked)
                {
                    if (empty($checked_items[$name]))
                    {
                        $checked_items[$name] = array();
                    }
                    $checked_items[$name] = $value;
                }
			}
		}

		foreach ($names as $extra)
		{
			$return[$extra] = ${$extra};
		}

        $return['_checked'] = $checked_items;

		return $return;
	}

}
