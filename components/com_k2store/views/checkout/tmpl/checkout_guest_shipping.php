<?php
?>
<table class="form">
  <tr>
    <td><span class="required">*</span> <?php echo JText::_('K2STORE_FIRST_NAME'); ?></td>
    <td><input type="text" name="first_name" value="<?php echo  $this->data['first_name']; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo JText::_('K2STORE_LAST_NAME'); ?></td>
    <td><input type="text" name="last_name" value="<?php echo  $this->data['last_name']; ?>" class="large-field" /></td>
  </tr>
<?php if($this->params->get('ship_company_name', 2) != 3 ): ?>
		<tr>
		<td>
		<?php echo $this->params->get('ship_company_name', 2)==1? '<span class="required">*</span>':''; ?>
		 <?php echo JText::_('K2STORE_COMPANY_NAME'); ?>
		 </td>
		<td><input type="text" name="company" value="<?php echo  $this->data['company']; ?>" class="large-field" /></td>
		</tr>
	<?php endif; ?>
  <tr>
    <td><span class="required">*</span> <?php echo JText::_('K2STORE_ADDRESS1'); ?></td>
    <td><input type="text" name="address_1" value="<?php echo  $this->data['address_1']; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><?php echo JText::_('K2STORE_ADDRESS2'); ?></td>
    <td><input type="text" name="address_2" value="<?php echo  $this->data['address_2']; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo JText::_('K2STORE_CITY'); ?></td>
    <td><input type="text" name="city" value="<?php echo  $this->data['city']; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span id="shipping-postcode-required" class="required">*</span> <?php echo JText::_('K2STORE_POSTCODE'); ?></td>
    <td><input type="text" name="zip" value="<?php echo  $this->data['zip']; ?>" class="large-field" /></td>
  </tr>
  <tr>
  <td><span class="required">*</span> <?php echo JText::_('K2STORE_COUNTRY'); ?></td>
    <td><?php echo $this->guest_ship_country; ?></td>

  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo JText::_('K2STORE_STATE_PROVINCE'); ?></td>
    <td><select name="zone_id" class="large-field">
      </select></td>
  </tr>
    <tr>
    <td><span class="required">*</span> <?php echo JText::_('K2STORE_TELEPHONE'); ?></td>
    <td><input type="text" name="phone_1" value="<?php echo  $this->data['phone_1']; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><?php echo JText::_('K2STORE_MOBILE'); ?></td>
    <td><input type="text" name="phone_2" value="<?php echo  $this->data['phone_2']; ?>" class="large-field" /></td>
  </tr>

</table>
<br />
<div class="buttons">
  <div class="right"><input type="button" value="<?php echo JText::_('K2STORE_CHECKOUT_CONTINUE'); ?>" id="button-guest-shipping" class="button btn btn-primary" /></div>
</div>
<script type="text/javascript"><!--
(function($) {
$('#shipping-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?option=com_k2store&view=checkout&task=getCountry&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
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

			$('#shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

(function($) {
$('#shipping-address select[name=\'country_id\']').trigger('change');
})(k2store.jQuery);
//--></script>