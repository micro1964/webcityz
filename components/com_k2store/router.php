<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_k2store/helpers/router.php');

function K2StoreBuildRoute( & $query) {

 $segments = array();
 //If there is only the option and Itemid, let Joomla! decide on the naming scheme
 if( isset($query['option']) && isset($query['Itemid']) &&
 		!isset($query['view']) && !isset($query['task']) &&
 		!isset($query['layout']) && !isset($query['id']) )
 {
 	return $segments;
 }

 $menus = JMenu::getInstance('site');

 $view = K2StoreRouterHelper::getAndPop($query, 'view', 'mycart');
 $task = K2StoreRouterHelper::getAndPop($query, 'task');
 $layout = K2StoreRouterHelper::getAndPop($query, 'layout');
 $id = K2StoreRouterHelper::getAndPop($query, 'id');
 $Itemid = K2StoreRouterHelper::getAndPop($query, 'Itemid');

 $qoptions = array( 'option' => 'com_k2store', 'view' => $view, 'task' => $task, 'id' => $id );

 switch($view)
 {
 	case 'mycart':
 		// Is it a mycart menu?
 		if($Itemid) {
 			$menu = $menus->getItem($Itemid);
 			$mView = isset($menu->query['view']) ? $menu->query['view'] : 'mycart';
 			$mTask = isset($menu->query['task']) ? $menu->query['task'] : '';
 			// No, we have to find another root
 			if( ($mView != 'mycart') ) $Itemid = null;
 		}

 		if(empty($Itemid))
 		{
 			$menu = K2StoreRouterHelper::findMenu($qoptions);
 			$Itemid = empty($menu) ? null : $menu->id;
 		}

 		if(empty($Itemid))
 		{
 			// No menu found, let's add a segment manually
 			$segments[] = 'mycart';
 			if(isset($task)) {
 				$segments[] = $task;
 			}
 		}
 		else
 		{
 			// Joomla! will let the menu item naming work its magic
 			$query['Itemid'] = $Itemid;
 		}
 		break;

 		case 'checkout':
 			// Is it a browser menu?
 			if($Itemid) {
 				$menu = $menus->getItem($Itemid);
 				$mView = isset($menu->query['view']) ? $menu->query['view'] : 'checkout';
 				$mTask = isset($menu->query['task']) ? $menu->query['task'] : '';
				// No, we have to find another root
 				if( ($mView != 'checkout') ) $Itemid = null;
 			}

 			if(empty($Itemid))
 			{
 				$menu = K2StoreRouterHelper::findMenu($qoptions);
 				$Itemid = empty($menu) ? null : $menu->id;
 			}

 			if(empty($Itemid))
 			{
 				// No menu found, let's add a segment based on the layout
 				$segments[] = 'checkout';
 				if(isset($task)) {
 					$segments[] = $task;
 				}
 			}
 			else
 			{
 				// sometimes we need task
 				if(isset($mTask)) {
 					$segments[] = $mTask;
 				}
 				// Joomla! will let the menu item naming work its magic
 				$query['Itemid'] = $Itemid;
 			}
 			break;

 			case 'orders':
 				// Is it a browser menu?
 				if($Itemid) {

 					$menu = $menus->getItem($Itemid);
 					$mView = isset($menu->query['view']) ? $menu->query['view'] : 'orders';
 					$mTask = isset($menu->query['task']) ? $menu->query['task'] : $task;
 					$mId = isset($menu->query['id']) ? $menu->query['id'] : $id;

 					// No, we have to find another root
 					if( ($mView != 'orders') ) $Itemid = null;

 				}



 				if(empty($Itemid))
 				{
 					//special find. Needed because we will be using order links under checkout view
 					$menu_id = K2StoreRouterHelper::findMenuOrders($qoptions);
 					$Itemid = empty($menu_id) ? null : $menu_id;
 				}

 				if(empty($Itemid))
 				{
 					// No menu found, let's add a segment based on the layout
 					$segments[] = 'orders';
 					if(isset($task)) {
 						$segments[] = $task;
 					}
 					if(isset($id)) {
 						$segments[] = $id;
 					}elseif(isset($mId)) {
 						$segments[] = $mId;
 					}
 				}
 				else
 				{
 					// Joomla! will let the menu item naming work its magic
 					if(isset($mTask)) {
 						$segments[] = $mTask;
 					}
 					if(isset($mId)) {
 						$segments[] = $mId;
 					}

 					$query['Itemid'] = $Itemid;
 				}
 				break;


 				case 'downloads':
 					// Is it a browser menu?
 					if($Itemid) {
 						$menu = $menus->getItem($Itemid);
 						$mView = isset($menu->query['view']) ? $menu->query['view'] : 'downloads';
 						$mTask = isset($menu->query['task']) ? $menu->query['task'] : $task;
 						// No, we have to find another root
 						if( ($mView != 'downloads') ) $Itemid = null;
 					}

 					if(empty($Itemid))
 					{
 						$menu = K2StoreRouterHelper::findMenu($qoptions);
 						$Itemid = empty($menu) ? null : $menu->id;
 					}

 					if(empty($Itemid))
 					{
 						// No menu found, let's add a segment based on the layout
 						$segments[] = 'downloads';
 						if(isset($task)) {
 							$segments[] = $task;
 						}
 					}
 					else
 					{

 						if(isset($mTask)) {
 							$segments[] = $mTask;
 						}
 						// Joomla! will let the menu item naming work its magic
 						$query['Itemid'] = $Itemid;
 					}
 					break;

 }

 return $segments;
}


function K2StoreParseRoute($segments) {
	$query = array();
	$menus = JMenu::getInstance('site');
	$menu = $menus->getActive();
	$vars = array();

	if(is_null($menu) && count($segments)) {
		if($segments[0] == 'mycart' ) {
			$vars['view'] = $segments[0];
		}

		if($segments[0] == 'checkout' ) {
			$vars['view'] = $segments[0];
			if(isset($segments[1])) {
				$vars['task'] = $segments[1];
			}
		}

		if($segments[0] == 'orders' ) {
			$vars['view'] = $segments[0];
			if(isset($segments[1])) {
				$vars['task'] = $segments[1];
			}
			if(isset($segments[2])) {
				$vars['id'] = $segments[2];
			}
		}

		if($segments[0] == 'downloads' ) {
			$vars['view'] = $segments[0];
			if(isset($segments[1])) {
				$vars['task'] = $segments[1];
			}
		}

	} else {

			if(count($segments))
			{

				$mView = $menu->query['view'];

				if(isset($mView) && $mView == 'mycart') {
					$vars['view'] = $segments[0];
				}elseif($segments[0] == 'mycart' ) {
					$vars['view'] = $segments[0];
				}

				if(isset($mView) && $mView == 'checkout') {
					$vars['view'] = $segments[0];
					if(isset($segments[1])) {
						$vars['task'] = $segments[1];
					}
				}elseif($segments[0] == 'checkout' ) {
					$vars['view'] = $segments[0];
					if(isset($segments[1])) {
						$vars['task'] = $segments[1];
					}

				}

				if(isset($mView) && $mView == 'orders') {
					$vars['view'] = 'orders';
					if(isset($segments[0])) {
						$vars['task'] = $segments[0];
					}
					if(isset($segments[1])) {
						$vars['id'] = $segments[1];
					}

				}elseif($segments[0] == 'orders' ) {
					$vars['view'] = $segments[0];
					if(isset($segments[1])) {
						$vars['task'] = $segments[1];
					}
					if(isset($segments[2])) {
						$vars['id'] = $segments[2];
					}
				}

				if(isset($mView) && $mView == 'downloads') {
					$vars['view'] = 'downloads';
					if(isset($segments[0])) {
						$vars['task'] = $segments[0];
					}
				}elseif($segments[0] == 'downloads' ) {
					$vars['view'] = $segments[0];
					if(isset($segments[1])) {
						$vars['task'] = $segments[1];
					}

				}

			}
	}
	return $vars;
}