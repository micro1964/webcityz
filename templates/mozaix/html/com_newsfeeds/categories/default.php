<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

?>
<div class="categories-list<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1 class="pagetitle"><span>
	<?php echo $this->escape($this->params->get('page_heading')); ?></span>
</h1>
<?php endif; ?>

	<?php if ($this->params->get('show_base_description')) : ?>
	<?php 	//If there is a description in the menu parameters use that; ?>
	       		<?php if($this->params->get('categories_description')) : ?>
		 <div class="category-desc base-desc">
			<?php echo  JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_newsfeeds.categories'); ?>
			</div>
		<?php  else: ?>
			<?php //Otherwise get one from the database if it exists. ?>
			<?php  if ($this->parent->description) : ?>
				<div class="category-desc  base-desc">
					<?php  echo JHtml::_('content.prepare', $this->parent->description, '', 'com_newsfeeds.categories'); ?>
				</div>
			<?php  endif; ?>
		<?php  endif; ?>
	<?php endif; ?>
<?php
echo $this->loadTemplate('items');
?>
</div>
