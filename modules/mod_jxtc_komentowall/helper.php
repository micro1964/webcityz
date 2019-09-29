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


class mod_jxtc_komentowallHelper {

    /**
     * Retrieves the hello message
     *
     * @access public
     */
    static function getData($component_id, $authorid, $group, $offset, $varaux, $sortfield, $sortorder, $issticked, $onlyreplies) {
        $db = JFactory::getDBO();

        $query = 'SELECT i.id, i.parent_id, i.component, i.cid, i.comment, i.name, i.title, i.email, i.url, i.ip, i.created, i.created_by, i.modified, 
            u.name AS uname, u.username AS uusername, u.email AS uemail, u.lastvisitDate, u.registerDate FROM #__komento_comments AS i 
            LEFT JOIN #__users AS u ON u.id = i.created_by WHERE i.published = 1';
				if ($component_id[0] != 0) {
					$query .= " AND i.component IN(" . implode(',', $component_id) . ")";
        }

        if ($authorid[0] != -1) {
        	$query .= ' AND i.created_by in (' . implode(',', $authorid) . ')';
        }

        switch ($issticked) {
            case 0: // Do not include
                $query .= ' AND i.sticked = 0 ';
                break;
            case 1: // Include if present
                break;
            case 2: // Only if allow comments
                $query .= ' AND i.sticked = 1 ';
                break;
        }

        switch ($onlyreplies) {
            case 0: // Do not include
                $query .= ' AND i.parent_id = 0 ';
                break;
            case 1: // Include if present
                break;
            case 2: // Only if is front page
                $query .= ' AND i.parent_id != 0 ';
                break;
        }

        if ($group == 1) {
            $query .= ' GROUP BY i.created_by';
        }

        $aux = ($sortorder == '0') ? ' ASC ' : ' DESC ';

        switch ($sortfield) {
            case '0': // component
                $query .= ' ORDER BY i.component' . $aux;
                break;
            case '1': // user name
                $query .= ' ORDER BY u.name' . $aux;
                break;
            case '2': // user username
                $query .= ' ORDER BY u.username' . $aux;
                break;
            case '3': // comentor name
                $query .= ' ORDER BY i.name' . $aux;
                break;
            case '4': // comment title
                $query .= ' ORDER BY i.title' . $aux;
                break;
            case '5': // commnet date
                $query .= ' ORDER BY i.created' . $aux;
                break;
            case '6': // comment publish date
                $query .= ' ORDER BY i.publish_up' . $aux;
                break;
            case '7':
                $query .= ' ORDER BY RAND()';
                break;
        }

        $db->setQuery($query, 0, $varaux);
        $items = $db->loadObjectList();

        //var_dump($items);
        //var_dump($db);

        return $items;
    }

}

?>