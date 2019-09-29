<?php if ($this->addresses) { ?>
<input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
<label for="shipping-address-existing"><?php echo JText::_('K2STORE_ADDRESS_EXISTING'); ?></label>
<div id="shipping-existing">
  <select name="address_id" style="width: 100%; margin-bottom: 15px;" size="5">
    <?php foreach ($this->addresses as $address) { ?>
    <?php if ($address['id'] == $this->address_id) { ?>
    <option value="<?php echo $address['id']; ?>" selected="selected"><?php echo $address['first_name']; ?> <?php echo $address['last_name']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone_name']; ?>, <?php echo $address['country_name']; ?></option>
    <?php } else { ?>
    <option value="<?php echo $address['id']; ?>"><?php echo $address['first_name']; ?> <?php echo $address['last_name']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone_name']; ?>, <?php echo $address['country_name']; ?></option>
    <?php } ?>
    <?php } ?>
  </select>
</div>
<p>
  <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
  <label for="shipping-address-new"><?php echo JText::_('K2STORE_ADDRESS_NEW'); ?></label>
</p>
<?php } ?>
<div id="shipping-new" style="display: <?php echo ($this->addresses ? 'none' : 'block'); ?>;">
  <table class="form">
    <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_FIRST_NAME'); ?></td>
      <td><input type="text" name="first_name" value="" class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_LAST_NAME'); ?></td>
      <td><input type="text" name="last_name" value="" class="large-field" /></td>
    </tr>

    <?php if($this->params->get('ship_company_name', 2) != 3 ): ?>
		<tr>
		<td>
		<?php echo $this->params->get('ship_company_name', 2)==1? '<span class="required">*</span>':''; ?>
		 <?php echo JText::_('K2STORE_COMPANY_NAME'); ?>
		 </td>
		<td><input type="text" name="company" value="" class="large-field" /></td>
		</tr>
	<?php endif; ?>
    <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_ADDRESS1'); ?></td>
      <td><input type="text" name="address_1" value="" class="large-field" /></td>
    </tr>
    <tr>
      <td><?php echo JText::_('K2STORE_ADDRESS2'); ?></td>
      <td><input type="text" name="address_2" value="" class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_CITY'); ?></td>
      <td><input type="text" name="city" value="" class="large-field" /></td>
    </tr>
    <tr>
      <td><span id="shipping-postcode-required" class="required">*</span> <?php echo JText::_('K2STORE_POSTCODE'); ?></td>
      <td><input type="text" name="zip" value="" class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_SELECT_A_COUNTRY'); ?></td>
      <td><?php echo $this->ship_country; ?></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_SELECT_A_ZONE'); ?></td>
      <td><select name="zone_id" class="large-field">
        </select></td>
    </tr>
     <tr>
      <td><span class="required">*</span> <?php echo JText::_('K2STORE_TELEPHONE'); ?></td>
      <td><input type="text" name="phone_1" value="" class="large-field" /></td>
    </tr>
  </table>
</div>
<br />
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo JText::_('K2STORE_CHECKOUT_CONTINUE'); ?>" id="button-shipping-address" class="button btn btn-primary" />
  </div>
</div>
 <input type="hidden" name="task" value="shipping_address_validate" />
  <input type="hidden" name="option" value="com_k2store" />
  <input type="hidden" name="view" value="checkout" />
<script type="text/javascript"><!--
(function($) {
$(document).on('change', '#shipping-address input[name=\'shipping_address\']', function() {
	if (this.value == 'new') {
		$('#shipping-existing').hide();
		$('#shipping-new').show();
	} else {
		$('#shipping-existing').show();
		$('#shipping-new').hide();
	}
});
})(k2store.jQuery);
//--></script>
<script type="text/javascript"><!--
(function($){
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

					if (json['zone'][i]['zone_id'] == '<?php echo $this->zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['zone'][i]['zone_name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo JText::_('K2STORE_CHECKOUT_NONE'); ?></option>';
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