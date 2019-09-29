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
defined('_JEXEC') or die('Restricted access');
$return_url = JRoute::_( "index.php?option=com_k2store&view=orders" );
$guest_action_url = JRoute::_( "index.php?option=com_k2store&view=orders&task=guestentry" );
$document =JFactory::getDocument();
if (!version_compare(JVERSION, '3.0', 'ge'))
{
	$document->addScript(JURI::root(true).'/media/k2store/js/k2storejq.js');
} else {
	JHtml::_('jquery.framework');
}
$document->addScript(JURI::root(true).'/media/k2store/js/jquery.validate.min.js');
?>

<div class="k2store">
<div class="row-fluid">
<script type="text/javascript">
			<!--
			if(typeof(k2store) == 'undefined') {
				var k2store = {};
			}

			if(typeof(k2store.jQuery) == 'undefined') {
				k2store.jQuery = jQuery.noConflict();
			}
			-->
			 </script>
	<?php if($this->params->get('show_login_form', 1)): ?>
	 <script type="text/javascript">
			<!--
			k2store.jQuery(document).ready(function(){
				k2store.jQuery('#k2storeOrderLoginForm').validate();
			});
			-->
		 </script>
		<div class="span5">
		<h3><?php echo JText::_('K2STORE_LOGIN'); ?></h3>
				<!-- LOGIN FORM -->
				<?php if (JPluginHelper::isEnabled('authentication', 'openid')) :
				$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
				$langScript =   'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var modlogin = 1;';
				$document = JFactory::getDocument();
				$document->addScriptDeclaration( $langScript );
				JHTML::_('script', 'openid.js');
        				endif; ?>

				<form
					action="<?php echo JRoute::_( 'index.php?option=com_users&task=user.login', true, $this->params->get('usesecure')); ?>"
					method="post" name="login" id="k2storeOrderLoginForm">

					<label for="username" class="k2storeUserName"><?php echo JText::_('K2STORE_USERNAME'); ?>
					
					
					<input type="text" name="username" class="inputbox required" alt="username" title="<?php echo JText::_('K2STORE_LOGIN_FORM_ENTER_USERNAME');?>" />
					</label>
					
					<label for="password" class="k2storePassword"><?php echo JText::_('K2STORE_PASSWORD'); ?> 
					<input type="password" name="password" class="inputbox required"	alt="password" title="<?php echo JText::_('K2STORE_LOGIN_FORM_ENTER_PASSWORD');?>" />
					</label>
					<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
					
					<label for="remember">
					 <input type="checkbox" name="remember"
						class="inputbox" value="yes" />
						<?php echo JText::_('K2STORE_REMEMBER_ME'); ?>
					</label>
					<?php endif; ?>
					<div class="clr"></div>
					<input type="submit" name="submit" class="k2store_checkout_button btn btn-primary"
						value="<?php echo JText::_('K2STORE_LOGIN') ?>" />
					<ul class="loginLinks">
						<li><?php // TODO Can we do this in a lightbox or something? Why does the user have to leave? ?>
							<a
							href="<?php echo JRoute::_( 'index.php?option=com_users&view=reset' ); ?>">
								<?php echo JText::_('K2STORE_FORGOT_YOUR_PASSWORD'); ?>
						</a>
						</li>
						<li><?php // TODO Can we do this in a lightbox or something? Why does the user have to leave? ?>
							<a
							href="<?php echo JRoute::_( 'index.php?option=com_users&view=remind' ); ?>">
								<?php echo JText::_('K2STORE_FORGOT_YOUR_USERNAME'); ?>
						</a>
						</li>
					</ul>
					<input type="hidden" name="option" value="com_users" /> <input
						type="hidden" name="task" value="user.login" /> <input
						type="hidden" name="return"
						value="<?php echo base64_encode( $return_url ); ?>" />
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>
				</div>
		<?php endif; ?>
		<?php if ($this->params->get('allow_guest_checkout')) : ?>
		 <script type="text/javascript">
			<!--
			k2store.jQuery(document).ready(function(){
				k2store.jQuery('#k2storeOrderGuestForm').validate();
			});
			-->
		 </script>
		<div class="span5">
				<h3><?php echo JText::_('K2STORE_ORDER_GUEST_VIEW'); ?></h3>
				<small><?php echo JText::_('K2STORE_ORDER_GUEST_VIEW_DESC'); ?></small>
				<!-- Registration form -->
				<form action="<?php echo $guest_action_url;?>" method="post"
					class="adminform" name="adminForm" id="k2storeOrderGuestForm">
					
					<div class="k2store_register_fields">
						<label for="email"> <?php echo JText::_( 'K2STORE_ORDER_EMAIL' ); ?>
							*
						</label><input name="email" id="email"
							class="required email" type="text"
							title="<?php echo JText::_('K2STORE_VALIDATION_ENTER_VALID_EMAIL'); ?>"
							 />
					</div>

					<div class="k2store_register_fields">
						<label for="order_token"> <?php echo JText::_( 'K2STORE_ORDER_TOKEN' ); ?>*
						</label> <input name="order_token" id="order_token"
							class="required" type="text" 
							title="<?php echo JText::_('K2STORE_VALIDATION_ENTER_VALID_TOKEN'); ?>"
							/>
					</div>
						<div class="k2store_register_fields">

						<input type="submit" name="submit" class="k2store_checkout_button btn btn-primary"
							value="<?php echo JText::_('K2STORE_VIEW') ?>" />
					</div>
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>
			</div>
		<?php endif; ?>
</div>
</div>