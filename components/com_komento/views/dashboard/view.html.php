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

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'view.php' );

class KomentoViewDashboard extends KomentoView
{
	function display($tmpl = null)
	{
		$mainframe = JFactory::getApplication();
		$commentsModel = Komento::getModel( 'comments' );

		$cid = JRequest::getVar( 'cid', 'all' );

		$filter['component']	= $mainframe->getUserStateFromRequest( 'com_komento.dashboard.filter_component', 'filter-component', 'all', 'string' );
		$filter['status']		= $mainframe->getUserStateFromRequest( 'com_komento.dashboard.filter_status', 'filter-status', 'all', 'string' );
		$filter['flag']			= $mainframe->getUserStateFromRequest( 'com_komento.dashboard.filter_flag', 'filter-flag', 'all', 'string' );
		$filter['sort']			= $mainframe->getUserStateFromRequest( 'com_komento.dashboard.filter_sort', 'filter-sort', 'latest', 'string' );
		$filter['search']		= trim( JString::strtolower( $mainframe->getUserStateFromRequest( 'com_komento.dashboard.filter_search', 'filter-search', '', 'string' ) ) );

		$options = array(
			'limit'		=> 0,
			'published'	=> $filter['status'],
			'flag'		=> $filter['flag'],
			'sort'		=> $filter['sort'],
			'search'	=> $filter['search'],
			'threaded'	=> 0
		);

		$comments = $commentsModel->getComments( $filter['component'], $cid, $options );
		$pagination = $commentsModel->getPagination();
		$components = $commentsModel->getUniqueComponents();

		$theme = Komento::getTheme();
		$theme->set( 'components', $components );
		$theme->set( 'pagination', $pagination );
		$theme->set( 'comments', $comments );
		$theme->set( 'filter', $filter );

		echo $theme->fetch( 'dashboard/comments.php' );
	}
}
