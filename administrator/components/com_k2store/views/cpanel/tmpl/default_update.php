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

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="k2store_update row-fluid">
	<div class="well">
	<h3>What's new in K2 Store</h3>
			<iframe src="http://k2store.org/updates/newsfeed/k2store_<?php echo $this->row->version; ?>_newsfeed.html">
			</iframe>
	</div>
	<div class="well">

			<h3>Credits</h3>
			<div>
			<h4><?php echo JText::_('K2STORE_CURRENT_VERSION'); ?> <span class="pull-right"><?php echo $this->row->version; ?></span> </h4>
				<p>Copyright &copy; <?php echo date('Y');?> - <?php echo date('Y')+5; ?> Ramesh Elamathi / <a href="http://www.k2store.org"><b><span style="color: #000">K2</span><span style="color: #666666">Store</span></b>.org</a></p>
								<p>If you use K2Store, please post a rating and a review at the <a target="_blank" href="http://extensions.joomla.org/extensions/extension-specific/k2-extensions/11918">Joomla! Extensions Directory</a>.</p>
			</div>

			<div style="text-align: center;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="business" value="rameshelamathi@gmail.com">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="item_name" value="K2Store">
			<input type="hidden" name="no_note" value="0">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			</div>

		 </div>
</div>
