<?php
/**
 * @author ElasticEmail
 * @date: 2019-04-10
 *
 * @copyright  Copyright (C) 2010-2019 elasticemail.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

jimport('joomla.application.component.controller');

defined( '_JEXEC' ) or die ( 'Restricted access' );
define('EEMAIL', '0.9.0');
class eesenderController extends JControllerLegacy
{
	protected $default_view = 'dashboard';
}