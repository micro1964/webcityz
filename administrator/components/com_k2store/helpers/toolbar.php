<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/


// No direct access
defined('_JEXEC') or die;

/**
 * Submenu helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_k2store
 * @since		1.6
 */

class K2StoreToolBar
{
	/** @var array The links to be rendered in the toolbar */
	protected $linkbar = array();

	public static function &getAnInstance($option = null, $config = array()) {

		if (!class_exists( $className )) {
			$className = 'K2StoreToolbar';
		}
		$instance = new $className($config);

		return $instance;

	}


	public function __construct($config = array()) {}

	protected function renderSubmenu()
	{
		$views = array(
				'cpanel',
				'K2STORE_MAINMENU_SETUP' => array('storeprofiles', 'countries', 'zones', 'geozones', 'taxrates', 'taxprofiles', 'shippingmethods', 'options'),
				'orders'
		);

		//show product attribute migration menu only for the upgraded users

		require_once (JPATH_COMPONENT_ADMINISTRATOR.'/helpers/version.php');
		if(K2StoreVersion::getPreviousVersion() == '3.0.3' && K2STORE_ATTRIBUTES_MIGRATED==false) {
			$views['K2STORE_MAINMENU_TOOLS'] = array('migrate');
		}

		foreach($views as $label => $view) {
			if(!is_array($view)) {
				$this->addSubmenuLink($view);
			} else {
				$label = JText::_($label);
				$this->appendLink($label, '', false);
				foreach($view as $v) {
					$this->addSubmenuLink($v, $label);
				}
			}
		}
	}

	private function addSubmenuLink($view, $parent = null)
	{
		static $activeView = null;
		if(empty($activeView)) {
			$activeView = JFactory::getApplication()->input->getCmd('view','cpanel');
		}

		$key = strtoupper('K2STORE_TITLE_'.strtoupper($view));
		if(strtoupper(JText::_($key)) == $key) {
			if($view == 'cpanel') {
				$name = JText::_('K2STORE_DASHBOARD');
			} else {
				$name = ucfirst($view);
			}

		} else {
			$name = JText::_($key);
		}

		$link = 'index.php?option=com_k2store&view='.$view;

		$active = $view == $activeView;

		if(strtolower($name) == 'options') {
			$name = JText::_('K2STORE_PRODUCT_GLOBAL_OPTIONS');
		}

		if(strtolower($name) == 'migrate') {
			$name = JText::_('K2STORE_MIGRATE');
		}

		$this->appendLink($name, $link, $active, null, $parent);
	}


	public function renderLinkbar() {

		$this->renderSubMenu();
		$links = $this->getLinks();
		//if(!empty($links)) {
		//	foreach($links as $link) {
				//JSubMenuHelper::addEntry($link['name'], $link['link'], $link['active']);
		//	}
		//}
		if(JFactory::getApplication()->input->getString('tmpl')=='component') {
			return true;
		}

		if(!empty($links)) {
			echo "<div class=\"k2store\">\n";
			echo "<ul class=\"nav nav-tabs\">\n";
			foreach($links as $link) {
				$dropdown = false;
				if(array_key_exists('dropdown', $link)) {
					$dropdown = $link['dropdown'];
				}

				if($dropdown) {
					echo "<li";
					$class = 'dropdown';
					if($link['active']) $class .= ' active';
					echo ' class="'.$class.'">';

					echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
					if($link['icon']) {
						echo "<i class=\"icon icon-".$link['icon']."\"></i>";
					}
					echo $link['name'];
					echo '<b class="caret"></b>';
					echo '</a>';

					echo "\n<ul class=\"dropdown-menu\">";
					foreach($link['items'] as $item) {

						echo "<li";
						if($item['active']) echo ' class="active"';
						echo ">";
						if($item['icon']) {
							echo "<i class=\"icon icon-".$item['icon']."\"></i>";
						}
						if($item['link']) {
							echo "<a tabindex=\"-1\" href=\"".$item['link']."\">".$item['name']."</a>";
						} else {
							echo $item['name'];
						}
						echo "</li>";

					}
					echo "</ul>\n";

				} else {
					echo "<li";
					if($link['active']) echo ' class="active"';
					echo ">";
					if($link['icon']) {
						echo "<i class=\"icon icon-".$link['icon']."\"></i>";
					}
					if($link['link']) {
						echo "<a href=\"".$link['link']."\">".$link['name']."</a>";
					} else {
						echo $link['name'];
					}
				}

				echo "</li>\n";
			}
			echo "</ul>\n";
			echo "</div>\n";
		}
	}



	public function appendLink($name, $link = null, $active = false, $icon = null, $parent = '')
	{
		$linkDefinition = array(
				'name'		=> $name,
				'link'		=> $link,
				'active'	=> $active,
				'icon'		=> $icon
		);
		if(empty($parent)) {
			$this->linkbar[$name] = $linkDefinition;
		} else {
			if(!array_key_exists($parent, $this->linkbar)) {
				$parentElement = $linkDefinition;
				$parentElement['link'] = null;
				$this->linkbar[$parent] = $parentElement;
				$parentElement['items'] = array();
			} else {
				$parentElement = $this->linkbar[$parent];
				if(!array_key_exists('dropdown', $parentElement) && !empty($parentElement['link'])) {
					$newSubElement = $parentElement;
					$parentElement['items'] = array($newSubElement);
				}
			}

			$parentElement['items'][] = $linkDefinition;
			$parentElement['dropdown'] = true;

			$this->linkbar[$parent] = $parentElement;
		}
	}


	public function &getLinks()
	{
		return $this->linkbar;
	}



}
