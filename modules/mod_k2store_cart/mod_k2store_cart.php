<?php
/*------------------------------------------------------------------------
# mod_k2store_cart - K2 Store Cart
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/



// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$moduleclass_sfx = $params->get('moduleclass_sfx','');

require( JModuleHelper::getLayoutPath('mod_k2store_cart') );
?>