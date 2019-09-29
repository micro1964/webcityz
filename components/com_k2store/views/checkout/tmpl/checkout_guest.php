<div class="row-fluid">
<div class="left span5">
  <h2><?php echo JText::_('K2STORE_CHECKOUT_YOUR_DETAILS'); ?></h2>
<span class="required">*</span> <?php echo JText::_('K2STORE_FIRST_NAME'); ?><br />
  <input type="text" name="first_name" value="<?php echo $this->data['first_name']; ?>" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_LAST_NAME'); ?><br />
  <input type="text" name="last_name" value="<?php echo $this->data['last_name']; ?>" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_EMAIL'); ?><br />
  <input type="text" name="email" value="<?php echo $this->data['email']; ?>" class="large-field" />
  <br />
  <br />
  <span class="required">*</span> <?php echo JText::_('K2STORE_TELEPHONE'); ?><br />
  <input type="text" name="phone_1" value="<?php echo $this->data['phone_1']; ?>" class="large-field" />
  <br />
  <br />
  <?php echo JText::_('K2STORE_MOBILE'); ?><br />
  <input type="text" name="phone_2" value="<?php echo $this->data['phone_2']; ?>" class="large-field" />
  <br />
  <br />
   <?php if($this->params->get('bill_company_name', 2) != 3 ): ?>
	 <?php echo $this->params->get('bill_company_name', 2)==1? '<span class="required">*</span>':''; ?>
 	<?php echo JText::_('K2STORE_COMPANY_NAME'); ?><br />
	<input type="text" name="company" value="<?php echo $this->data['company']; ?>" class="large-field" />
	<br />
	<br />
<?php endif; ?>

<?php if($this->params->get('bill_tax_number', 2) != 3 ): ?>
 	<?php echo $this->params->get('bill_tax_number', 2)==1? '<span class="required">*</span>':''; ?>
 	<?php echo JText::_('K2STORE_TAX_ID'); ?><br />
	<input type="text" name="tax_number" value="<?php echo $this->data['tax_number']; ?>" class="large-field" />
	<br />
	<br />
<?php endif; ?>
</div>
<div class="right span5">
 <h2><?php echo JText::_('K2STORE_CHECKOUT_YOUR_ADDRESS'); ?></h2>
 <span class="required">*</span> <?php echo JText::_('K2STORE_ADDRESS1'); ?><br />
<input type="text" name="address_1" value="<?php echo $this->data['address_1']; ?>" class="large-field" />
<br />
<br />
<?php echo JText::_('K2STORE_ADDRESS2'); ?><br />
<input type="text" name="address_2" value="<?php echo $this->data['address_2']; ?>" class="large-field" />
<br />
<br />
<span class="required">*</span> <?php echo JText::_('K2STORE_CITY'); ?><br />
<input type="text" name="city" value="<?php echo $this->data['city']; ?>" class="large-field" />
<br />
<br />
<span id="payment-postcode-required" class="required">*</span> <?php echo JText::_('K2STORE_POSTCODE'); ?><br />
<input type="text" name="zip" value="<?php echo $this->data['zip']; ?>" class="large-field" />
<br />
<br />
<span class="required">*</span> <?php echo JText::_('K2STORE_COUNTRY'); ?> <br />
<?php echo $this->guest_bill_country; ?>
<br />
<br />
<span class="required">*</span> <?php echo JText::_('K2STORE_STATE_PROVINCE'); ?><br />
<select name="zone_id" class="large-field">
</select>
<br />
<br />
<br />
</div>

<?php if ($this->showShipping) { ?>
<div style="clear: both; padding-top: 15px; border-top: 1px solid #DDDDDD;">
  <?php if ($this->data['shipping_address']) { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
  <?php } else { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" />
  <?php } ?>
  <label for="shipping"><?php echo JText::_('K2STORE_MAKE_SHIPPING_SAME'); ?></label>
  <br />
  <br />
  <br />
</div>
<?php } ?>
<div class="buttons row-fluid">
  <div class="right span12 pull-right">
    <input type="button" value="<?php echo JText::_('K2STORE_CHECKOUT_CONTINUE'); ?>" id="button-guest" class="button btn btn-primary" />
  </div>
</div>
</div>
<input type="hidden" name="option" value="com_k2store" />
<input type="hidden" name="view" value="checkout" />
<input type="hidden" name="task" value="guest_validate" />

<script type="text/javascript"><!--
(function($) {
$('#billing-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?option=com_k2store&view=checkout&task=getCountry&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#billing-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
			}

			html = '<option value=""><?php echo JText::_('K2STORE_SELECT'); ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $this->data['zone_id']; ?>') {
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


(function($){
$('#billing-address select[name=\'country_id\']').trigger('change');
})(k2store.jQuery);

//--></script>