<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams ('com_media');

if ($this->params->get('show_image') == 1) {
    $contactspan = 6;
} else {
    $contactspan = 12;
  
}

?>
<div style=" display:table; width:100%; "  class="joomla <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<div class="contact row-fluid">
		<?php if ($this->contact->image && $this->params->get('show_image')) : ?>
			<div class="contact_image span6">
				<?php echo JHtml::_('image',$this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle')); ?>
			</div>
		<?php endif; ?>
            
            <div class="span<?php echo $contactspan;?>">
            
            
		<?php if ($this->params->get('show_contact_category') == 'show_no_link') : ?>
			<h3>
				<span class="contact-category"><?php echo $this->contact->category_title; ?></span>
			</h3>
		<?php endif; ?>
		<?php if ($this->params->get('show_contact_category') == 'show_with_link') : ?>
			<?php $contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catid);?>
			<h3>
				<span class="contact-category"><a href="<?php echo $contactLink; ?>">
					<?php echo $this->escape($this->contact->category_title); ?></a>
				</span>
			</h3>
		<?php endif; ?>
	
		<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
			<div class="filter">
				<form action="#" method="get" name="selectForm" id="selectForm">
					<?php echo JText::_('COM_CONTACT_SELECT_CONTACT'); ?>
					<?php echo JHtml::_('select.genericlist',  $this->contacts, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);?>
				</form>
			</div>
		<?php endif; ?>

		<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
		<h2 class="title">
                 <span>
				<?php echo $this->contact->name; ?>
                 </span></h2>
		<?php endif;  ?>



		<div>
			<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
				<h3>
					<?php echo $this->contact->con_position; ?>
				</h3>
			<?php endif; ?>

			<?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
				<div style="margin-bottom:18px;">
					<?php if ($this->params->get('presentation_style')!='plain'){?>
						<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_OTHER_INFORMATION'), 'display-misc');} ?>
					<?php if ($this->params->get('presentation_style')=='plain'):?>
						<?php // echo '<h3>'. JText::_('COM_CONTACT_OTHER_INFORMATION').'</h3>'; ?>
					<?php endif; ?>
					<div>
						<?php echo $this->contact->misc; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php echo $this->loadTemplate('address'); ?>

			<?php if ($this->params->get('allow_vcard')) :	?>
				<div class="panel">
                                    <h3 class="title pane-toggler-down"><a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
					<?php echo JText::_('COM_CONTACT_VCARD');?></a></h3>
				</div>
			<?php endif; ?>

			<?php  if ($this->params->get('presentation_style')!='plain'){?>
				<?php  echo  JHtml::_($this->params->get('presentation_style').'.start', 'contact-slider'); ?>
			<?php  echo JHtml::_($this->params->get('presentation_style').'.panel',JText::_('COM_CONTACT_DETAILS'), 'basic-details'); } ?>
			<?php if ($this->params->get('presentation_style')=='plain'):?>
				<?php  // echo '<h3>'. JText::_('COM_CONTACT_DETAILS').'</h3>';  ?>
			<?php endif; ?>
		
			<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>
				<?php  echo $this->loadTemplate('form');  ?>
			<?php endif; ?>
		
			<?php if ($this->params->get('show_links')) : ?>
				<?php echo $this->loadTemplate('links'); ?>
			<?php endif; ?>
			<?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
				<?php if ($this->params->get('presentation_style')!='plain'):?>
					<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('JGLOBAL_ARTICLES'), 'display-articles'); ?>
					<?php endif; ?>
					<?php if  ($this->params->get('presentation_style')=='plain'):?>
					<?php echo '<h2>'. JText::_('JGLOBAL_ARTICLES').'</h2>'; ?>
					<?php endif; ?>
					<?php echo $this->loadTemplate('articles'); ?>
			<?php endif; ?>
			<?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
				<?php if ($this->params->get('presentation_style')!='plain'):?>
					<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_PROFILE'), 'display-profile'); ?>
				<?php endif; ?>
				<?php if ($this->params->get('presentation_style')=='plain'):?>
					<?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>'; ?>
				<?php endif; ?>
				<?php echo $this->loadTemplate('profile'); ?>
			<?php endif; ?>

			<?php if ($this->params->get('presentation_style')!='plain'){?>
					<?php echo JHtml::_($this->params->get('presentation_style').'.end');} ?>
		</div>
	</div>
            </div>
</div>
