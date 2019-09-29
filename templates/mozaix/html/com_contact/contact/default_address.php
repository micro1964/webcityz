<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

/* marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<?php if (($this->params->get('address_check') > 0) &&  ($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
	
		<?php if ($this->params->get('address_check') > 0) : ?>
			<div>
				<?php // echo $this->params->get('marker_address'); ?>
			</div>
		<?php endif; ?>
	
		<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
			<div>
				<?php echo nl2br($this->contact->address); ?>
			</div>
		<?php endif; ?>
	
		<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
			<div>
				<?php echo $this->contact->suburb; ?>
			</div>
		<?php endif; ?>
	
		<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
			<div>
				<?php echo $this->contact->state; ?>
			</div>
		<?php endif; ?>
	
		<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
			<div>
				<?php echo $this->contact->postcode; ?>
			</div>
		<?php endif; ?>
	
		<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
			<div>
				<?php echo $this->contact->country; ?>
			</div>
		<?php endif; ?>
	
<?php endif; ?>


<?php if($this->params->get('show_email') || $this->params->get('show_telephone')||$this->params->get('show_fax')||$this->params->get('show_mobile')|| $this->params->get('show_webpage') ) : ?>
		
		<?php if ($this->contact->email_to && $this->params->get('show_email')) : ?>
			<div style="width:100%;">
				<div style="float:left; margin-right:10px;" >
					<?php echo $this->params->get('marker_email'); ?>
				</div>
				<div style="float:left;">
					<?php echo $this->contact->email_to; ?>
				</div>
                            <div style="clear:both;"></div>
			</div>
		<?php endif; ?>
		
		<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
			<div style="width:100%;">
				<div style="float:left; margin-right:10px;" >
					<?php echo $this->params->get('marker_telephone'); ?>
				</div>
				<div style="float:left;">
					<?php echo nl2br($this->contact->telephone); ?>
				</div>
                            <div style="clear:both;"></div>
			</div>
		<?php endif; ?>

		<?php if ($this->contact->fax && $this->params->get('show_fax')) : ?>
			<div style="width:100%;">
				<div style="float:left; margin-right:10px;" >
					<?php echo $this->params->get('marker_fax'); ?>
				</div>
				<div style="float:left;">
					<?php echo nl2br($this->contact->fax); ?>
				</div>
                            <div style="clear:both;"></div>
			</div>
		<?php endif; ?>

		<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
			<div style="width:100%;">
				<div style="float:left; margin-right:10px;" >
					<?php echo $this->params->get('marker_mobile'); ?>
				</div>
				<div style="float:left;">
					<?php echo nl2br($this->contact->mobile); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
			<div style="width:100%;">
				<div style="float:left;margin-right:10px;">
				</div>
				<div style="float:left;">
					<a href="<?php echo $this->contact->webpage; ?>" target="_blank">
					<?php echo $this->contact->webpage; ?></a>
				</div>
                            <div style="clear:both;"></div>
			</div>
		<?php endif; ?>
                   
<?php endif; ?>
