<?php
/*
 * --------------------------------------------------------------------------------
   Weblogicx India  - K2 Store
 * --------------------------------------------------------------------------------
 * @package		Joomla! 2.5x
 * @subpackage	K2 Store
 * @author    	Weblogicx India http://www.weblogicxindia.com
 * @copyright	Copyright (c) 2010 - 2015 Weblogicx India Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link		http://weblogicxindia.com
 * --------------------------------------------------------------------------------
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$action = JRoute::_('index.php?option=com_k2store&view=checkout');
?>

<div id="content">
  <h1><?php echo JText::_('K2STORE_CHECKOUT'); ?></h1>
  <div class="k2store checkout">
    <div id="checkout">
      <div class="checkout-heading"><?php echo JText::_('K2STORE_CHECKOUT_OPTIONS'); ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php if (!$this->logged) { ?>
    <div id="billing-address">
      <div class="checkout-heading"><span><?php echo JText::_('K2STORE_CHECKOUT_ACCOUNT'); ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <?php } else { ?>
    <div id="billing-address">
      <div class="checkout-heading"><span><?php echo JText::_('K2STORE_CHECKOUT_BILLING_ADDRESS');; ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <?php if ($this->showShipping) { ?>
    <div id="shipping-address">
      <div class="checkout-heading"><?php echo JText::_('K2STORE_CHECKOUT_SHIPPING_ADDRESS'); ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <div id="shipping-payment-method">
      <div class="checkout-heading">
      <?php if ($this->showShipping) : ?>
      <?php echo JText::_('K2STORE_CHECKOUT_SHIPPING_PAYMENT_METHOD'); ?>
      <?php else: ?>
      <?php echo JText::_('K2STORE_CHECKOUT_PAYMENT_METHOD'); ?>
      <?php endif;?>
      </div>
      <div class="checkout-content"></div>
    </div>
    <div id="confirm">
      <div class="checkout-heading"><?php echo JText::_('K2STORE_CHECKOUT_CONFIRM');; ?></div>
      <div class="checkout-content"></div>
    </div>
  </div>
  </div>
<script type="text/javascript"><!--

var query = {};
query['option']='com_k2store';
query['view']='checkout';
(function($) {
$(document).on('change', '#checkout .checkout-content input[name=\'account\']', function() {
	if ($(this).attr('value') == 'register') {
		$('#billing-address .checkout-heading span').html('<?php echo JText::_('K2STORE_CHECKOUT_ACCOUNT'); ?>');
	} else {
		$('#billing-address .checkout-heading span').html('<?php echo JText::_('K2STORE_CHECKOUT_BILLING_ADDRESS'); ?>');
	}
});
})(k2store.jQuery);

(function($) {
$(document).on('click', '.checkout-heading a', function() {
	$('.checkout-content').slideUp('slow');

	$(this).parent().parent().find('.checkout-content').slideDown('slow');
});
})(k2store.jQuery);

//incase only guest checkout is allowed we got to process that first
<?php if((!$this->logged && $this->params->get('allow_guest_checkout')) && (!$this->params->get('show_login_form', 1) && !$this->params->get('allow_registration', 1))){ ?>
(function($) {
$(document).ready(function() {
	$('#billing-address .checkout-heading span').html('<?php echo JText::_('K2STORE_CHECKOUT_BILLING_ADDRESS'); ?>');
	$('#checkout').hide();
	$.ajax({
	url: 'index.php',
	type: 'post',
	data: 'option=com_k2store&view=checkout&task=guest',
	dataType: 'html',
	success: function(html) {
		$('.warning, .k2error').remove();

		$('#billing-address .checkout-content').html(html);

		$('#checkout .checkout-content').slideUp('slow');

		$('#billing-address .checkout-content').slideDown('slow');

	},
	error: function(xhr, ajaxOptions, thrownError) {
		//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});
});
})(k2store.jQuery);

<?php }elseif(!$this->logged) { ?>
(function($) {
$(document).ready(function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: 'option=com_k2store&view=checkout&task=login',
		success: function(html) {
			$('#checkout .checkout-content').html(html);

			$('#checkout .checkout-content').slideDown('slow');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

<?php } else { ?>
(function($) {
$(document).ready(function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: 'option=com_k2store&view=checkout&task=billing_address',
		dataType: 'html',
		success: function(html) {
			$('#billing-address .checkout-content').html(html);

			$('#billing-address .checkout-content').slideDown('slow');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);
<?php } ?>

//new account
(function($) {
$(document).on('click', '#button-account', function() {
		var task = $('input[name=\'account\']:checked').attr('value');
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: 'option=com_k2store&view=checkout&task='+task,
		dataType: 'html',
		beforeSend: function() {
			$('#button-account').attr('disabled', true);
			$('#button-account').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-account').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(html) {
			$('.warning, .k2error').remove();

			$('#billing-address .checkout-content').html(html);

			$('#checkout .checkout-content').slideUp('slow');

			$('#billing-address .checkout-content').slideDown('slow');

			$('.checkout-heading a').remove();

			$('#checkout .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

//Login
(function($) {
$(document).on('click', '#button-login', function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: $('#checkout #login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#checkout .checkout-content').prepend('<div class="warning alert alert-danger" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

				$('.warning').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

// Register
(function($) {
$(document).on('click', '#button-register', function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: $('#billing-address input[type=\'text\'], #billing-address input[type=\'password\'], #billing-address input[type=\'checkbox\']:checked, #billing-address input[type=\'radio\']:checked, #billing-address input[type=\'hidden\'], #billing-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').attr('disabled', true);
			$('#button-register').after('<span class="wait">&nbsp;<img src="media/k2store/images/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-register').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#billing-address .checkout-content').prepend('<div class="warning alert alert-block alert-danger" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['first_name']) {
					$('#billing-address input[name=\'first_name\'] + br').after('<span class="k2error">' + json['error']['first_name'] + '</span>');
				}

				if (json['error']['last_name']) {
					$('#billing-address input[name=\'last_name\'] + br').after('<span class="k2error">' + json['error']['last_name'] + '</span>');
				}

				if (json['error']['email']) {
					$('#billing-address input[name=\'email\'] + br').after('<span class="k2error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['phone_1']) {
					$('#billing-address input[name=\'phone_1\'] + br').after('<span class="k2error">' + json['error']['phone_1'] + '</span>');
				}

				if (json['error']['company']) {
					$('#billing-address input[name=\'company\'] + br').after('<span class="k2error">' + json['error']['company'] + '</span>');
				}

				if (json['error']['tax_number']) {
					$('#billing-address input[name=\'tax_number\'] + br').after('<span class="k2error">' + json['error']['tax_number'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#billing-address input[name=\'address_1\'] + br').after('<span class="k2error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#billing-address input[name=\'city\'] + br').after('<span class="k2error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['zip']) {
					$('#billing-address input[name=\'zip\'] + br').after('<span class="k2error">' + json['error']['zip'] + '</span>');
				}

				if (json['error']['country']) {
					$('#billing-address select[name=\'country_id\'] + br').after('<span class="k2error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#billing-address select[name=\'zone_id\'] + br').after('<span class="k2error">' + json['error']['zone'] + '</span>');
				}

				if (json['error']['password']) {
					$('#billing-address input[name=\'password\'] + br').after('<span class="k2error">' + json['error']['password'] + '</span>');
				}

				if (json['error']['confirm']) {
					$('#billing-address input[name=\'confirm\'] + br').after('<span class="k2error">' + json['error']['confirm'] + '</span>');
				}
			} else {
				<?php if ($this->showShipping) { ?>
				var shipping_address = $('#billing-address input[name=\'shipping_address\']:checked').attr('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php',
						type: 'post',
						data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-payment-method .checkout-content').html(html);

							$('#billing-address .checkout-content').slideUp('slow');

							$('#shipping-payment-method .checkout-content').slideDown('slow');

							$('#checkout .checkout-heading a').remove();
							$('#billing-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-payment-method .checkout-heading a').remove();
							//$('#payment-method .checkout-heading a').remove();

							$('#shipping-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
							$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');

							$.ajax({
								url: 'index.php?option=com_k2store&view=checkout&task=shipping_address',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					$.ajax({
						url: 'index.php',
						type: 'post',
						data: 'option=com_k2store&view=checkout&task=shipping_address',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);

							$('#billing-address .checkout-content').slideUp('slow');

							$('#shipping-address .checkout-content').slideDown('slow');

							$('#checkout .checkout-heading a').remove();
							$('#billing-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-payment-method .checkout-heading a').remove();
							//$('#payment-method .checkout-heading a').remove();

							$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-payment-method .checkout-content').html(html);

						$('#billing-address .checkout-content').slideUp('slow');

						$('#shipping-payment-method .checkout-content').slideDown('slow');

						$('#checkout .checkout-heading a').remove();
						$('#billing-address .checkout-heading a').remove();
						//$('#payment-method .checkout-heading a').remove();
						$('#shipping-payment-method .checkout-heading a').remove();

						$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>

				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=billing_address',
					dataType: 'html',
					success: function(html) {
						$('#billing-address .checkout-content').html(html);

						$('#billing-address .checkout-heading span').html('<?php echo JText::_('K2STORE_BILLING_ADDRESS'); ?>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

//billing address
(function($) {
$(document).on('click', '#button-billing-address', function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: $('#billing-address input[type=\'text\'], #billing-address input[type=\'password\'], #billing-address input[type=\'checkbox\']:checked, #billing-address input[type=\'radio\']:checked, #billing-address input[type=\'hidden\'], #billing-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-billing-address').attr('disabled', true);
			$('#button-billing-address').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-billing-address').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#billing-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['first_name']) {
					$('#billing-address input[name=\'first_name\']').after('<span class="k2error">' + json['error']['first_name'] + '</span>');
				}

				if (json['error']['last_name']) {
					$('#billing-address input[name=\'last_name\']').after('<span class="k2error">' + json['error']['last_name'] + '</span>');
				}

				if (json['error']['phone_1']) {
					$('#billing-address input[name=\'phone_1\']').after('<span class="k2error">' + json['error']['phone_1'] + '</span>');
				}

				if (json['error']['company']) {
					$('#billing-address input[name=\'company\']').after('<span class="k2error">' + json['error']['company'] + '</span>');
				}

				if (json['error']['tax_number']) {
					$('#billing-address input[name=\'tax_number\']').after('<span class="k2error">' + json['error']['tax_number'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#billing-address input[name=\'address_1\']').after('<span class="k2error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#billing-address input[name=\'city\']').after('<span class="k2error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['zip']) {
					$('#billing-address input[name=\'zip\']').after('<span class="k2error">' + json['error']['zip'] + '</span>');
				}

				if (json['error']['country']) {
					$('#billing-address select[name=\'country_id\']').after('<span class="k2error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#billing-address select[name=\'zone_id\']').after('<span class="k2error">' + json['error']['zone'] + '</span>');
				}
			} else {
				<?php if ($this->showShipping) { ?>
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=shipping_address',
					dataType: 'html',
					success: function(html) {
						$('#shipping-address .checkout-content').html(html);

						$('#billing-address .checkout-content').slideUp('slow');

						$('#shipping-address .checkout-content').slideDown('slow');

						$('#billing-address .checkout-heading a').remove();
						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-payment-method .checkout-heading a').remove();
						//$('#payment-method .checkout-heading a').remove();

						$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } else { ?>
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-payment-method .checkout-content').html(html);

						$('#billing-address .checkout-content').slideUp('slow');

						$('#shipping-payment-method .checkout-content').slideDown('slow');

						$('#billing-address .checkout-heading a').remove();
						$('#shipping-payment-method .checkout-heading a').remove();

						$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>

				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=billing_address',
					dataType: 'html',
					success: function(html) {
						$('#billing-address .checkout-content').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

//Shipping Address
(function($) {
$(document).on('click', '#button-shipping-address', function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'hidden\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-address').attr('disabled', true);
			$('#button-shipping-address').after('<span class="wait">&nbsp;<img src="media/k2store/images/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-shipping-address').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-address .checkout-content').prepend('<div class="warning alert alert-danger" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['first_name']) {
					$('#shipping-address input[name=\'first_name\']').after('<span class="k2error">' + json['error']['first_name'] + '</span>');
				}

				if (json['error']['last_name']) {
					$('#shipping-address input[name=\'last_name\']').after('<span class="k2error">' + json['error']['last_name'] + '</span>');
				}

				if (json['error']['phone_1']) {
					$('#shipping-address input[name=\'phone_1\']').after('<span class="k2error">' + json['error']['phone_1'] + '</span>');
				}

				if (json['error']['company']) {
					$('#shipping-address input[name=\'company\']').after('<span class="k2error">' + json['error']['company'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#shipping-address input[name=\'address_1\']').after('<span class="k2error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#shipping-address input[name=\'city\']').after('<span class="k2error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['postcode']) {
					$('#shipping-address input[name=\'postcode\']').after('<span class="k2error">' + json['error']['postcode'] + '</span>');
				}

				if (json['error']['country']) {
					$('#shipping-address select[name=\'country_id\']').after('<span class="k2error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#shipping-address select[name=\'zone_id\']').after('<span class="k2error">' + json['error']['zone'] + '</span>');
				}
			} else {
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-payment-method .checkout-content').html(html);

						$('#shipping-address .checkout-content').slideUp('slow');

						$('#shipping-payment-method .checkout-content').slideDown('slow');

						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-payment-method .checkout-heading a').remove();
						//$('#payment-method .checkout-heading a').remove();

						$('#shipping-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');

						$.ajax({
							url: 'index.php',
							type: 'post',
							data: 'option=com_k2store&view=checkout&task=shipping_address',
							dataType: 'html',
							success: function(html) {
								$('#shipping-address .checkout-content').html(html);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});

				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=billing_address',
					dataType: 'html',
					success: function(html) {
						$('#billing-address .checkout-content').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

//Guest
(function($) {
$(document).on('click', '#button-guest', function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: $('#billing-address input[type=\'text\'], #billing-address input[type=\'checkbox\']:checked, #billing-address input[type=\'radio\']:checked, #billing-address input[type=\'hidden\'], #billing-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest').attr('disabled', true);
			$('#button-guest').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-guest').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#billing-address .checkout-content').prepend('<div class="warning alert alert-danger" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['first_name']) {
					$('#billing-address input[name=\'first_name\'] + br').after('<span class="k2error">' + json['error']['first_name'] + '</span>');
				}

				if (json['error']['last_name']) {
					$('#billing-address input[name=\'last_name\'] + br').after('<span class="k2error">' + json['error']['last_name'] + '</span>');
				}

				if (json['error']['email']) {
					$('#billing-address input[name=\'email\'] + br').after('<span class="k2error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['phone_1']) {
					$('#billing-address input[name=\'phone_1\'] + br').after('<span class="k2error">' + json['error']['phone_1'] + '</span>');
				}

				if (json['error']['company']) {
					$('#billing-address input[name=\'company\'] + br').after('<span class="k2error">' + json['error']['company'] + '</span>');
				}

				if (json['error']['tax_number']) {
					$('#billing-address input[name=\'tax_number\'] + br').after('<span class="k2error">' + json['error']['tax_number'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#billing-address input[name=\'address_1\'] + br').after('<span class="k2error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#billing-address input[name=\'city\'] + br').after('<span class="k2error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['zip']) {
					$('#billing-address input[name=\'zip\'] + br').after('<span class="k2error">' + json['error']['zip'] + '</span>');
				}

				if (json['error']['country']) {
					$('#billing-address select[name=\'country_id\'] + br').after('<span class="k2error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#billing-address select[name=\'zone_id\'] + br').after('<span class="k2error">' + json['error']['zone'] + '</span>');
				}
			} else {
				<?php if ($this->showShipping) { ?>
				var shipping_address = $('#billing-address input[name=\'shipping_address\']:checked').attr('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php',
						type: 'post',
						data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-payment-method .checkout-content').html(html);

							$('#billing-address .checkout-content').slideUp('slow');

							$('#shipping-payment-method .checkout-content').slideDown('slow');

							$('#billing-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-payment-method .checkout-heading a').remove();

							$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
							$('#shipping-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');

							$.ajax({
								url: 'index.php',
								type: 'post',
								data: 'option=com_k2store&view=checkout&task=guest_shipping',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					$.ajax({
						url: 'index.php',
						type: 'post',
						data: 'option=com_k2store&view=checkout&task=guest_shipping',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);

							$('#billing-address .checkout-content').slideUp('slow');

							$('#shipping-address .checkout-content').slideDown('slow');

							$('#billing-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-payment-method .checkout-heading a').remove();
							//$('#payment-method .checkout-heading a').remove();

							$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-payment-method .checkout-content').html(html);

						$('#billing-address .checkout-content').slideUp('slow');

						$('#shipping-payment-method .checkout-content').slideDown('slow');

						$('#billing-address .checkout-heading a').remove();
						$('#shipping-payment-method .checkout-heading a').remove();

						$('#billing-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				<?php } ?>
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

})(k2store.jQuery);


// Guest Shipping
(function($) {
$(document).on('click', '#button-guest-shipping', function() {
	$.ajax({
		url: 'index.php?option=com_k2store&view=checkout&task=guest_shipping_validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest-shipping').attr('disabled', true);
			$('#button-guest-shipping').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-guest-shipping').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-address .checkout-content').prepend('<div class="warning alert alert-danger" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

					$('.warning').fadeIn('slow');
				}

				if (json['error']['first_name']) {
					$('#shipping-address input[name=\'first_name\']').after('<span class="k2error">' + json['error']['first_name'] + '</span>');
				}

				if (json['error']['last_name']) {
					$('#shipping-address input[name=\'last_name\']').after('<span class="k2error">' + json['error']['last_name'] + '</span>');
				}

				if (json['error']['company']) {
					$('#shipping-address input[name=\'company\']').after('<span class="k2error">' + json['error']['company'] + '</span>');
				}


				if (json['error']['address_1']) {
					$('#shipping-address input[name=\'address_1\']').after('<span class="k2error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['city']) {
					$('#shipping-address input[name=\'city\']').after('<span class="k2error">' + json['error']['city'] + '</span>');
				}

				if (json['error']['zip']) {
					$('#shipping-address input[name=\'zip\']').after('<span class="k2error">' + json['error']['zip'] + '</span>');
				}

				if (json['error']['country']) {
					$('#shipping-address select[name=\'country_id\']').after('<span class="k2error">' + json['error']['country'] + '</span>');
				}

				if (json['error']['zone']) {
					$('#shipping-address select[name=\'zone_id\']').after('<span class="k2error">' + json['error']['zone'] + '</span>');
				}
				if (json['error']['phone_1']) {
					$('#shipping-address input[name=\'phone_1\']').after('<span class="k2error">' + json['error']['phone_1'] + '</span>');
				}

				if (json['error']['phone_2']) {
					$('#shipping-address input[name=\'phone_2\']').after('<span class="k2error">' + json['error']['phone_2'] + '</span>');
				}
			} else {
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=shipping_payment_method',
					dataType: 'html',
					success: function(html) {
						$('#shipping-payment-method .checkout-content').html(html);

						$('#shipping-address .checkout-content').slideUp('slow');

						$('#shipping-payment-method .checkout-content').slideDown('slow');

						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-payment-method .checkout-heading a').remove();

						$('#shipping-address .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);

//shipping and payment methods
(function($) {
$(document).on('click', '#button-payment-method', function() {
	$.ajax({
		url: 'index.php',
		type: 'post',
		data: $('#shipping-payment-method input[type=\'text\'], #shipping-payment-method input[type=\'hidden\'], #shipping-payment-method input[type=\'radio\']:checked, #shipping-payment-method input[type=\'checkbox\']:checked, #shipping-payment-method textarea, #shipping-payment-method select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-method').attr('disabled', true);
			$('#button-payment-method').after('<span class="wait">&nbsp;<img src="media/k2store/images/loader.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-payment-method').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning, .k2error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-payment-method .checkout-content').prepend('<div class="warning alert alert-danger" style="display: none;">' + json['error']['warning'] + '<button data-dismiss="alert" class="close" type="button">×</button></div>');

					$('.warning').fadeIn('slow');
				}
			} else {
				$.ajax({
					url: 'index.php',
					type: 'post',
					data: 'option=com_k2store&view=checkout&task=confirm',
					dataType: 'html',
					success: function(html) {
						$('#confirm .checkout-content').html(html);

						$('#shipping-payment-method .checkout-content').slideUp('slow');

						$('#confirm .checkout-content').slideDown('slow');

						$('#shipping-payment-method .checkout-heading a').remove();

						$('#shipping-payment-method .checkout-heading').append('<a><?php echo JText::_('K2STORE_CHECKOUT_MODIFY'); ?></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
})(k2store.jQuery);
//--></script>