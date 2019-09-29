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


defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_( "index.php?option=com_k2store&view=checkout" ); ?>" method="post" name="adminForm" enctype="multipart/form-data">

    <div class="note">
        <?php echo JText::_( "K2STORE_OFFLINE_PAYMENT_PREPARATION_MESSAGE" ); ?>
    
        <p>
            <strong><?php echo JText::_( "K2STORE_OFFLINE_PAYMENT_METHOD");?>:</strong> 
            <?php echo JText::_( 'K2STORE_'.$vars->offline_payment_method ); ?>
        </p>
    </div>
    
    <input type='hidden' name='offline_payment_method' value='<?php echo @$vars->offline_payment_method; ?>'>
    <input type="submit" class="k2store_checkout_button btn btn-primary" value="<?php echo JText::_('K2STORE_CLICK_TO_COMPLETE_ORDER'); ?>" />
    <input type='hidden' name='order_id' value='<?php echo @$vars->order_id; ?>'>
    <input type='hidden' name='orderpayment_id' value='<?php echo @$vars->orderpayment_id; ?>'>
    <input type='hidden' name='orderpayment_type' value='<?php echo @$vars->orderpayment_type; ?>'>
    <input type='hidden' name='task' value='confirmPayment'>
</form>
