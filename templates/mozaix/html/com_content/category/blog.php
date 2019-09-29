<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Leading articles are custom-fit in 3 columns
switch (trim($this->pageclass_sfx)) {
	case 'leftlarge': $targetitem = 0; $defaultspan = 'span3'; break;
  case 'centerlarge': $targetitem = 1; $defaultspan = 'span3'; break;
  case 'rightlarge': $targetitem = 2; $defaultspan = 'span3'; break;
  default: $targetitem = -1; $defaultspan = 'span12'; break; //Normal layout (full width)
}   
?>
<div class="blog-featured <?php echo $this->pageclass_sfx;?>">
	<?php if ( $this->params->get('show_page_heading')!=0) : ?>
		<h1 class="pagetitle">
			<span>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</span>
		</h1>
	<?php endif; ?>
    
	<?php
		if (!empty($this->lead_items)) {
			$rows = array_chunk($this->lead_items, 3);
			foreach ($rows as $row) {
				echo '<div class="items-leading xtc-leading row-fluid">';
				foreach($row as $count => $item) {
					$class = $count == $targetitem ? 'span6' : $defaultspan;
					echo '<div class="'.$class.'">';
					$this->item = &$item;
					echo $this->loadTemplate('item');
					echo '</div>';
				}
			  echo '</div>';
			}
		}

		$introcount=(count($this->intro_items));
		$counter=0;
    $count = 1;
	?>
	<?php if (!empty($this->intro_items)) : ?>
		<div class="xtc-intro clearfix row-fluid">
			<?php
				$leadingcount=0;
				foreach ($this->intro_items as $key => &$item) :
				$key= ($key-$leadingcount)+1;
				$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
				$row = $counter / $this->columns ;
				if ($counter % $this->columns == 0){
					$item_order = 'gridfirst';
				}elseif ($counter % $this->columns == $this->columns -1){
					$item_order='gridlast';
				}else{
					$item_order='';
				}
        $customSpans = '';
        $cols = $this->columns;
				$spaces = 12; $cs = 0;
				if (is_array($customSpans)) {
					$cs = count($customSpans);
					foreach ($customSpans as $c => $s) { $spaces -= intval($s); }
				}
	
				$spanClass = floor($spaces / ($cols - $cs));
				if ($spanClass == 0) $spanClass = 1;
		    if ($count%$this->columns == 1) {  
         //echo '<div class="row-fluid"><div class="span12">';
		    }
			?>


			<div class="<?php echo $item_order ?> span<?php echo $spanClass;?> xtc-category-col cols-<?php echo (int) $this->columns;?> item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
				<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
			</div>

			<?php
		    if ($count%$this->columns == 0) {
        //echo '</div></div>';
		    }
		    $count++;
			?>     
			<?php $counter++; ?>		
			<?php endforeach; ?>
		</div> 
	<?php endif; ?>
  
	<?php if (!empty($this->link_items)) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
		<div class="cat-children">
			<h3>
				<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
			</h3>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="Pagination">
			<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>

			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php  endif; ?>
</div>
