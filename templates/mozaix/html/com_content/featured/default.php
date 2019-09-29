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

switch ($this->pageclass_sfx) {
	case 'leftlarge': $targetitem = 0; $defaultspan = 'span3'; break;
  case 'centerlarge': $targetitem = 1; $defaultspan = 'span3'; break;
  case 'rightlarge': $targetitem = 2; $defaultspan = 'span3'; break;
  default: $targetitem = -1; $defaultspan = 'span4'; break; //Normal layout
}   
?>
<div class="blog-featured <?php echo $this->pageclass_sfx;?>">
<?php if ( $this->params->get('show_page_heading')!=0) : ?>
	<h1 class="pagetitle"><span>
	<?php echo $this->escape($this->params->get('page_heading')); ?></span>
	</h1>
<?php endif; ?>
 
 <div class="row-fluid">   
    
<?php
$leadingcount=0;
if (!empty($this->lead_items)) : ?>
<div class="items-leading xtc-leading span12">
<?php
foreach($this->lead_items as $count => $item) {
	if (($count+1) % 4 == 0) { // make row breaks
		echo '</div>';
		echo '<div class="row-fluid">';
		$defaultspan = 'span3';
	}

	$class = $count == $targetitem ? 'span6' : $defaultspan;
	echo '<div class="'.$class.'">';
	$this->item = &$item;
	echo $this->loadTemplate('item');
	echo '</div>';
	
}
?>
</div>
<?php endif; ?>

      <div style="clear:both;"></div>
</div>
 <div class="xtc-intro clearfix">
<?php
	$introcount=(count($this->intro_items));
	$counter=0;
       $count = 1;
?>
<?php if (!empty($this->intro_items)) : ?>

	<?php   foreach ($this->intro_items as $key => &$item) : ?>
	<?php
		$key= ($key-$leadingcount)+1;
		$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
		//$row = $counter / $this->columns ;

                
       
        $customSpans = '';
        $cols = $this->columns;
	$spaces = 12; $cs = 0;
	if (is_array($customSpans)) {
		$cs = count($customSpans);
		foreach ($customSpans as $c => $s) { $spaces -= intval($s); }
	}
	
	$spanClass = floor($spaces / ($cols - $cs));
	if ($spanClass == 0) $spanClass = 1;
    if ($count%$this->columns == 1)
    {  
         echo '<div class="row-fluid"><div class="span12">';
    }
?>


	<div class="span<?php echo $spanClass;?> xtc-category-col cols-<?php echo (int) $this->columns;?> item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
		<?php
			$this->item = &$item;
			echo $this->loadTemplate('item');
		?>
	</div>

<?php
    if ($count%$this->columns == 0)
    {
        echo '</div></div>';
    }
    $count++;

//if ($count%$this->columns != 1) echo "</div>";

?>     
		
	<?php endforeach; ?>

<?php endif; ?>
 </div>   

<?php if (!empty($this->link_items)) : ?>
	<div class="items-more">
	<?php echo $this->loadTemplate('links'); ?>
	</div>
<?php endif; ?>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php  endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>

</div>
