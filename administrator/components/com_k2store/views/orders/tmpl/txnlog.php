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


//no direct access
 defined('_JEXEC') or die('Restricted access');
 $row = $this->row;
 ?>
 <div class="row-fluid k2store">
 	<div class="span12">
 		<h3><?php echo JText::_('K2STORE_TRANSACTION_LOG_HEADER'); ?>&nbsp;<?php echo $row->order_id; ?></h3>
 		<div class="alert alert-info">
 			<?php echo JText::_('K2STORE_TRANSACTION_LOG_HELP_MSG');?>
 		</div>
 			<dl class="dl-horizontal">
 			<dt><?php echo JText::_('K2STORE_ORDER_TRANSACTION_STATUS'); ?> </dt>
 			<dd>
 				<div class="alert alert-warning">
 				<small><?php echo JText::_('K2STORE_ORDER_TRANSACTION_STATUS_HELP_MSG'); ?></small>
 				</div>
 				<?php echo JText::_($row->transaction_status); ?>
 			</dd>
 			<hr />	
 			<dt><?php echo JText::_('K2STORE_ORDER_TRANSACTION_DETAILS'); ?></dt>
 			<dd>
 				<div class="alert alert-warning">
 				<small><?php echo JText::_('K2STORE_ORDER_TRANSACTION_DETAILS_HELP_MSG'); ?></small>
 				</div>
 				<?php echo JText::_($row->transaction_details); ?>
 			</dd> 
 		</dl>				
 	</div>
 </div>