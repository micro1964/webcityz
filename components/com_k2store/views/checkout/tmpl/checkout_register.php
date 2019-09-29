<div class="k2store">
<div class="row-fluid">
<div class="left span5">
  <h2><?php echo JText::_('K2STORE_CHECKOUT_YOUR_DETAILS'); ?></h2>
  <span class="required">*</span> <?php echo JText::_('K2STORE_FIRST_NAME'); ?><br />
  <input type="text" name="first_name" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_LAST_NAME'); ?><br />
  <input type="text" name="last_name" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_EMAIL'); ?><br />
  <input type="text" name="email" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_TELEPHONE'); ?><br />
  <input type="text" name="phone_1" value="" class="large-field" />
  <br />
  <br />
  <?php echo JText::_('K2STORE_MOBILE'); ?><br />
  <input type="text" name="phone_2" value="" class="large-field" />
  <br />
  <br />
  <h2><?php echo JText::_('K2STORE_CHECKOUT_SET_PASSWORD'); ?></h2>
  <span class="required">*</span> <?php echo JText::_('K2STORE_CHECKOUT_ENTER_PASSWORD'); ?><br />
  <input type="password" name="password" value="" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_CHECKOUT_CONFIRM_PASSWORD'); ?> <br />
  <input type="password" name="confirm" value="" class="large-field" />
  <br />
  <br />
  <br />
</div>
<div class="right span5">
<h2><?php echo JText::_('K2STORE_CHECKOUT_YOUR_ADDRESS'); ?></h2>

<?php if($this->params->get('bill_company_name', 2) != 3 ): ?>
 <?php echo $this->params->get('bill_company_name', 2)==1? '<span class="required">*</span>':''; ?>
 <?php echo JText::_('K2STORE_COMPANY_NAME'); ?><br />
<input type="text" name="company" value="" class="large-field" />
<br />
<br />
<?php endif; ?>

<?php if($this->params->get('bill_tax_number', 2) != 3 ): ?>
 <?php echo $this->params->get('bill_tax_number', 2)==1? '<span class="required">*</span>':''; ?>
 <?php echo JText::_('K2STORE_TAX_ID'); ?><br />
<input type="text" name="tax_number" value="" class="large-field" />
<br />
<br />
<?php endif; ?>
<span class="required">*</span> <?php echo JText::_('K2STORE_ADDRESS1'); ?><br />
<input type="text" name="address_1" value="" class="large-field" />
<br />
<br />
<?php echo JText::_('K2STORE_ADDRESS2'); ?><br />
<input type="text" name="address_2" value="" class="large-field" />
<br />
<br />
<span class="required">*</span> <?php echo JText::_('K2STORE_CITY'); ?><br />
<input type="text" name="city" value="" class="large-field" />
<br />
<br />
<span id="payment-postcode-required" class="required">*</span> <?php echo JText::_('K2STORE_POSTCODE'); ?><br />
<input type="text" name="zip" value="<?php //echo $postcode; ?>" class="large-field" />
<br />
<br />
<span class="required">*</span> <?php echo $this->bill_country; ?><br />
<br />
<span class="required">*</span> <?php echo JText::_('K2STORE_STATE_PROVINCE'); ?><br />
<select name="zone_id" class="large-field">
</select>
<br />
<br />
<br />
</div>
<div class="span11" style="clear: both; padding-top: 15px; border-top: 1px solid #EEEEEE;">
  <?php if ($this->showShipping) { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
  <label for="shipping"><?php echo JText::_('K2STORE_MAKE_SHIPPING_SAME'); ?></label>
  <br />
  <?php } ?>
  <br />
</div>
<div class="span11" class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo JText::_('K2STORE_CHECKOUT_CONTINUE'); ?>" id="button-register" class="button btn btn-primary" />
  </div>
</div>
<input type="hidden" name="option" value="com_k2store" />
<input type="hidden" name="view" value="checkout" />
<input type="hidden" name="task" value="register_validate" />

</div> <!-- end of row-fluid -->
</div> <!-- end of k2store -->

<script type="text/javascript"><!--
(function($) {
$('#billing-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?option=com_k2store&view=checkout&task=getCountry&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#billing-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {

			html = '<option value=""><?php echo JText::_('K2STORE_SELECT'); ?></option>';

			if (json['zone'] != '') {

				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '') {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['zone'][i]['zone_name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo JText::_('K2STORE_CHECKOUT_ZONE_NONE'); ?></option>';
			}

			$('#billing-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

(function($) {
$('#billing-address select[name=\'country_id\']').trigger('change');
})(k2store.jQuery);
//--></script>