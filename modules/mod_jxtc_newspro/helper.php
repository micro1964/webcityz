<?php

/*
	JoomlaXTC Deluxe News Pro

	version 3.45.2

	Copyright (C) 2008,2009,2010,2011,2013 Monev Software LLC.	All Rights Reserved.

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

jimport( 'joomla.html.parameter' );


class mod_jxtc_newsproHelper {

    public static function getAllCategorys() {
        $db = JFactory::getDBO();
        $query = "SELECT id, parent_id FROM #__categories WHERE published=1";
        $db->setQuery($query);
        $cats = $db->loadObjectList();

        return $cats;
    }

    public static function getCategoryChilds($catid, $allcats) {
        $cats = array();
        $catid = (int) $catid;

        foreach ($allcats as $category) {
            if ($catid == $category->parent_id)
                $cats[] = $category->id;
        }

        $aux = $cats;

        if (is_array($aux)) {
            foreach ($aux as $child)
                $cats = array_merge($cats, mod_jxtc_newsproHelper::getCategoryChilds($child, $allcats));
        }

        $cats = array_unique($cats);

        return $cats;
    }

}