<div class="row-fluid">
<div class="left span5">
  <h2><?php echo JText::_('K2STORE_CHECKOUT_NEW_CUSTOMER'); ?></h2>
  <p><?php echo JText::_('K2STORE_CHECKOUT_OPTIONS'); ?></p>
<!-- registration -->
  <?php if($this->params->get('allow_registration', 1)): ?>
  <label for="register">
    <?php if ($this->account == 'register') { ?>
    <input type="radio" name="account" value="register" id="register" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="register" id="register" />
    <?php } ?>
    <b><?php echo JText::_('K2STORE_CHECKOUT_REGISTER'); ?></b></label>
  <br />
  <?php endif; ?>

  <!-- guest -->
  <?php if ($this->params->get('allow_guest_checkout', 0)) : ?>
  <label for="guest">
    <?php if ($this->account == 'guest') { ?>
    <input type="radio" name="account" value="guest" id="guest" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="account" value="guest" id="guest" />
    <?php } ?>
    <b><?php echo JText::_('K2STORE_CHECKOUT_GUEST'); ?></b></label>
  <br />
  <?php endif; ?>
  <br />
  <?php if($this->params->get('allow_registration', 1)): ?>
  <p><?php echo JText::_('K2STORE_CHECKOUT_REGISTER_ACCOUNT_HELP_TEXT'); ?></p>
  <?php endif; ?>

  <input type="button" value="<?php echo JText::_('K2STORE_CHECKOUT_CONTINUE'); ?>" id="button-account" class="button btn btn-primary" />
  <br />
</div>
<?php if($this->params->get('show_login_form', 1)): ?>
<div id="login" class="right span5">
  <h2><?php echo JText::_('K2STORE_CHECKOUT_RETURNING_CUSTOMER'); ?></h2>
  <p><?php echo JText::_('K2STORE_CHECKOUT_RETURNING_CUSTOMER_WELCOME'); ?></p>
  <b><?php echo JText::_('K2STORE_CHECKOUT_USERNAME'); ?></b><br />
  <input type="text" name="email" value="" />
  <br />
  <br />
  <b><?php echo JText::_('K2STORE_CHECKOUT_PASSWORD'); ?></b><br />
  <input type="password" name="password" value="" />
  <br />
  <input type="button" value="<?php echo JText::_('K2STORE_CHECKOUT_LOGIN'); ?>" id="button-login" class="button btn btn-primary" /><br />
  <input type="hidden" name="task" value="login_validate" />
  <input type="hidden" name="option" value="com_k2store" />
  <input type="hidden" name="view" value="checkout" />
  <br />
</div>
</div>
<?php endif; ?>
<input type="hidden" name="option" value="com_k2store" />
<input type="hidden" name="view" value="checkout" />