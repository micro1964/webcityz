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
$options = $this->attributes;

?>
<?php if ($options) { ?>

      <div class="options">
        <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
        <!-- select -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <select name="product_option[<?php echo $option['product_option_id']; ?>]">
            <option value=""><?php echo JText::_('K2STORE_SELECT'); ?></option>
            <?php foreach ($option['optionvalue'] as $option_value) { ?>
            <option value="<?php echo $option_value['product_optionvalue_id']; ?>"><?php echo $option_value['optionvalue_name']; ?>
            <?php if ($option_value['product_optionvalue_price'] > 0) { ?>
            <?php
            //get the tax
			$tax = $this->tax_class->getProductTax($option_value['product_optionvalue_price'], $this->item->product_id);
            ?>
            (<?php echo $option_value['product_optionvalue_prefix']; ?>
            <?php  echo K2StoreHelperCart::dispayPriceWithTax($option_value['product_optionvalue_price'], $tax, $this->params->get('price_display_options', 1)); ?>
            )
            <?php } ?>
            </option>
            <?php } ?>
          </select>
        </div>
        <br />
        <?php } ?>

        <?php if ($option['type'] == 'radio') { ?>
          <!-- radio -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <?php foreach ($option['optionvalue'] as $option_value) { ?>
          <input type="radio" name="product_option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_optionvalue_id']; ?>" id="option-value-<?php echo $option_value['product_optionvalue_id']; ?>" />
          <label for="option-value-<?php echo $option_value['product_optionvalue_id']; ?>"><?php echo $option_value['optionvalue_name']; ?>
            <?php if ($option_value['product_optionvalue_price'] > 0) { ?>
	            <?php
	            //get the tax
				$tax = $this->tax_class->getProductTax($option_value['product_optionvalue_price'], $this->item->product_id);
	            ?>
            	(<?php echo $option_value['product_optionvalue_prefix']; ?>
            	<?php  echo K2StoreHelperCart::dispayPriceWithTax($option_value['product_optionvalue_price'], $tax, $this->params->get('price_display_options', 1)); ?>
            	)

            <?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>

        <?php if ($option['type'] == 'checkbox') { ?>
          <!-- checkbox-->

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <?php foreach ($option['optionvalue'] as $option_value) { ?>
          <input type="checkbox" name="product_option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_optionvalue_id']; ?>" id="option-value-<?php echo $option_value['product_optionvalue_id']; ?>" />
          <label for="option-value-<?php echo $option_value['product_optionvalue_id']; ?>"><?php echo $option_value['optionvalue_name']; ?>
            <?php if ($option_value['product_optionvalue_price'] > 0) { ?>
                <?php
	            //get the tax
				$tax = $this->tax_class->getProductTax($option_value['product_optionvalue_price'], $this->item->product_id);
	            ?>
            	(<?php echo $option_value['product_optionvalue_prefix']; ?>
            	<?php  echo K2StoreHelperCart::dispayPriceWithTax($option_value['product_optionvalue_price'], $tax, $this->params->get('price_display_options', 1)); ?>
            	)
            	<?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>


        <?php if ($option['type'] == 'text') { ?>
         <!-- text -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <input type="text" name="product_option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['optionvalue']; ?>" />
        </div>
        <br />
        <?php } ?>


        <?php if ($option['type'] == 'textarea') { ?>
         <!-- textarea -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <textarea name="product_option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['optionvalue']; ?></textarea>
        </div>
        <br />
        <?php } ?>


        <?php if ($option['type'] == 'date') { ?>
          <!-- date -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <input type="text" name="product_option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['optionvalue']; ?>" class="k2store_date" />
        </div>
        <br />
        <?php } ?>


        <?php if ($option['type'] == 'datetime') { ?>
         <!-- datetime -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <input type="text" name="product_option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['optionvalue']; ?>" class="k2store_datetime" />
        </div>
        <br />
        <?php } ?>

        <?php if ($option['type'] == 'time') { ?>
        <!-- time -->
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['option_name']; ?>:</b><br />
          <input type="text" name="product_option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['optionvalue']; ?>" class="k2store_time" />
        </div>
        <br />
        <?php } ?>
        <?php } ?>
      </div>
      <?php } ?>