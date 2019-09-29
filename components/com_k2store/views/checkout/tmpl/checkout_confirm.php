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
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="k2store">
<?php if(!$this->redirect): ?>
<!--    ORDER SUMMARY   -->
	<div class="k2storeOrderSummary">
		<?php echo $this->orderSummary; ?>
	</div>

		<!--    PAYMENT METHOD   -->
	<h3>
		<?php echo JText::_("K2STORE_PAYMENT_METHOD"); ?>
	</h3>
	<div class="payment">
	<?php echo $this->plugin_html; ?>
	</div>

<?php else: ?>
<script type="text/javascript"><!--
location ='<?php echo $this->redirect; ?>';
//--></script>
<?php endif;?>
</div>