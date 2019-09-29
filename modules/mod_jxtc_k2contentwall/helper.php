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


class mod_jxtc_k2contentwallHelper
{
/**
 * Retrieves the hello message
 *
 * @access public
 */
    public static function getData( $catid, $authorid, $group, $offset, $varaux, $sortfield, $sortorder, $featured, $tag_id, $forced, $featuredfirst, $date_filter )
    {
        $user = JFactory::getUser();
        $date = JFactory::getDate();
        $db = JFactory::getDBO();
        $now = $date->toSQL();
        $nullDate = $db->getNullDate();
        $contentconfig = JComponentHelper::getParams( 'com_content' );
        $accesslevel =!$contentconfig->get('show_noauth');

        $aid = $user->get('aid', 0);

        $distinct = '';
        $extra_table= '';
        
        if($tag_id[0] != 0){
            $distinct = 'DISTINCT ';
            $extra_table = ', #__k2_tags_xref as t';
        }

        $query = 'SELECT '.$distinct.'i.id, i.video, i.gallery, i.access, i.introtext, i.fulltext, i.title, i.featured,
            UNIX_TIMESTAMP(i.created) as created, UNIX_TIMESTAMP(i.modified) as modified,
            UNIX_TIMESTAMP(i.publish_up) as publish_up, UNIX_TIMESTAMP(i.publish_down) as publish_down,
            i.catid, i.extra_fields, i.created_by, i.hits, i.params,
            cc.params as cat_params, cc.name as cat_title, cc.image as cat_image, cc.description as cat_description, cc.alias as cat_alias,
            u.username as author, u.name as authorname, CASE WHEN CHAR_LENGTH(i.alias)
            THEN CONCAT_WS(":", i.id, i.alias) ELSE i.id END as slug, CASE WHEN CHAR_LENGTH(cc.alias)
            THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug, kr.rating_sum , kr.rating_count, kr.lastip
            FROM #__k2_items AS i LEFT JOIN #__k2_rating AS kr ON kr.itemID = i.id,
            #__k2_categories AS cc, #__users AS u'.$extra_table.' WHERE cc.id = i.catid
            AND u.id = i.created_by AND i.published = 1 AND i.trash = 0 AND cc.published = 1 AND cc.trash = 0';
        
        if (empty($forced)) {

            if ($accesslevel) {
                $groups = implode(',', $user->getAuthorisedViewLevels());
                $query .= ' AND i.access IN (' . $groups . ')';
            }

            if ($catid[0] != 0) {
							$query .= " AND i.catid IN(".implode(',', $catid).")";
            }

						if ($tag_id[0] != 0) {
            	$query .= " AND t.tagID IN(".implode(',', $tag_id).") AND t.itemID = i.id ";
            }

            if ($authorid[0] != 0) {
                $query .= ' AND i.created_by in ('.implode(',', $authorid).')';
            }

            if ($group == 1) {
                $query .= ' GROUP BY i.created_by';
            }

						switch ($date_filter) {
						    case 'any':
						        break;
						    case 'current':
	                $query .= ' AND ( i.publish_up = '.$db->Quote($nullDate).' OR i.publish_up <= '.$db->Quote($now).' )
							                AND ( i.publish_down = '.$db->Quote($nullDate).' OR i.publish_down >= '.$db->Quote($now).' )';
						        break;
						    case 'future':
	                $query .= ' AND i.publish_up > '.$db->Quote($now);
						        break;
						}

            switch ($featured) {
                case 0: // Do not include
                    $query .= ' AND i.featured = 0 ';
                    break;
                case 1: // Include if present
                    break;
                case 2: // Only featured
                    $query .= ' AND i.featured = 1 ';
                    break;
            }
        }
        else {
            $forced = explode(',', $forced);
            $query .= ' AND (i.id ='.implode(' OR i.id=', $forced).')';
        }

        $aux = ($sortorder == '0') ? ' ASC ' : ' DESC ';

				$query .= $featuredfirst ? ' ORDER BY i.featured DESC, ' : ' ORDER BY ';

        switch ($sortfield) {
            case '0': // creation
                $query .= 'i.created'.$aux;
                break;
            case '1': // modified
                $query .= 'i.modified'.$aux;
                break;
            case '2': // hits
                $query .= 'i.hits'.$aux;
                break;
            case '3': // hits
                $query .= 'i.title'.$aux;
                break;
            case '4': // hits
                $query .= 'cc.name'.$aux;
                break;
            case '5': // hits
                $query .= 'u.username'.$aux;
                break;
            case '6': // hits
                $query .= 'kr.rating_count'.$aux;
                break;
            case '8': // hits
                $query .= 'i.ordering'.$aux;
                break;
            case '7':
                $query .= 'RAND()';
                break;
        }

        $db->setQuery($query, $offset, $varaux);
        $items = $db->loadObjectList();

        return $items;
    }
}
?>