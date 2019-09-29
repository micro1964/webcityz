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



defined('_JEXEC') or die('Restricted access'); 
?>

<?php
    if (!empty($this->shipping_name)) 
    {  
	?>    
       <div class="shippingName">
       <?php echo JText::_('K2STORE_STANDARD_SHIPPING_METHOD'); ?>       
       [<?php echo $this->shipping_name; ?>]
       </div>
    <?php
    }
        else
    {
        ?>
        <div class="note">
        <?php echo JText::_( "K2STORE_NO_SHIPPING_RATES_FOUND" ); ?>
        </div>
        <?php
    }
?>
