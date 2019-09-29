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
defined('_JEXEC') or die('Restricted access');
$link = JRoute::_('index.php?option=com_k2store&view=mycart');
$document =JFactory::getDocument();
if (!version_compare(JVERSION, '3.0', 'ge'))
{
	$document->addScript(JURI::root(true).'/media/k2store/js/k2storejq.js');
} else {
	JHtml::_('jquery.framework');
}

?>
<script type="text/javascript">
<!--
if(typeof(k2store) == 'undefined') {
	var k2store = {};
}
if(typeof(k2store.jQuery) == 'undefined') {
	k2store.jQuery = jQuery.noConflict();
}

if(typeof(k2storeURL) == 'undefined') {
	var k2storeURL = '';
}

(function($) {
	$(document).ready(function(){
		var murl = k2storeURL
				+ 'index.php?option=com_k2store&view=mycart&task=ajaxmini';

		if ($('#miniK2StoreCart').length > 0) {
		$.ajax({
			url : murl,
			type: 'post',
			success: function(response){
				if ($('#miniK2StoreCart').length > 0) {
					$('#miniK2StoreCart').html(response);
				}
			}

		});
		}
	});
})(k2store.jQuery);
//-->
</script>
<div class="k2StoreCartBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">

<div id="miniK2StoreCart">

</div>
<div class="cart_link"><a href="<?php echo $link; ?>" class="btn btn-primary"><?php echo JText::_('K2STORE_VIEW_CART');?></a> </div>
</div>
