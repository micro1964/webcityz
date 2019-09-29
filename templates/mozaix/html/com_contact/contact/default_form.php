<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
 if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<div style="width:96%; float:left; padding: 10px;">
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="emailForm">
            <fieldset><dl>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php  echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_EMAIL_FORM'), 'display-form');  ?>
		<?php endif; ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php  echo '<h2>'. JText::_('COM_CONTACT_EMAIL_FORM').'</h2>';  ?>
		<?php endif; ?>

		 <div class="ffield">
				<dt>
				<?php echo $this->form->getLabel('contact_name'); ?>
				</dt>
			<dd>
				<?php echo $this->form->getInput('contact_name'); ?>
			</dd>
		</div>
		<div class="ffield">
				<dt>
				<?php echo $this->form->getLabel('contact_email'); ?>
				</dt>
			<dd>
				<?php echo $this->form->getInput('contact_email'); ?>
			</dd>
		</div>
		<div class="ffield">
				<dt>
					<?php echo $this->form->getLabel('contact_subject'); ?>
				</dt>
			<dd>
				<?php echo $this->form->getInput('contact_subject'); ?>
			</dd>
		</div>
		<div class="ffield">
				<dt>
					<?php echo $this->form->getLabel('contact_message'); ?>
				</dt>
			<dd>
				<?php echo $this->form->getInput('contact_message'); ?>
			</dd>
		</div>

		<?php if ($this->params->get('show_email_copy')){ ?>
			<div style="margin-left:0px;margin-bottom:5px; clear:both; ">
				<?php echo $this->form->getInput('contact_email_copy'); ?>
				<?php echo $this->form->getLabel('contact_email_copy'); ?>
			</div>
		<?php } ?>

		<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
	    <?php if ($fieldset->name != 'contact'):?>
         <?php $fields = $this->form->getFieldset($fieldset->name);?>
         <?php foreach($fields as $field): ?>
            <?php if ($field->hidden): ?>
            	<?php echo $field->input;?>
            <?php else:?>
					
								<div style="float:left;">
	              	<?php echo $field->label; ?>
	                <?php if (!$field->required && $field->type != "Spacer"): ?>
  	              	<span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span>
    	            <?php endif; ?>
								</div>
								<div style="float:left;">
	              	<?php echo $field->input;?>
	              </div>
            <?php endif;?>
         <?php endforeach;?>
	    <?php endif ?>
		<?php endforeach;?>

		<div style="margin:0 0 5px 0px; clear:both; ">
			<div class="buttonMore">
				<button class="button validate morebtn" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
				<input type="hidden" name="option" value="com_contact" />
				<input type="hidden" name="task" value="contact.submit" />
				<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
				<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
				<?php echo JHtml::_( 'form.token' ); ?>
		</div></div>
</dl></fieldset>
	</form>
</div>
