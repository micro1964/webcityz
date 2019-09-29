<?php
defined('_JEXEC') or die;

K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);
?>
<div class="catItemImage">
	<a href="<?php echo $this->item->link; ?>"><img src="<?php echo $this->item->image; ?>" class="intimage"  /></a>
	<div class="zoom">
		<div class="newstext">
			<div class="newstext2">
				<h3><a class="catlink" href="<?php echo $this->item->link; ?>"><?php echo $this->item->title; ?></a></h3>
				<div class="introtext"><?php echo $this->item->introtext; ?></div>
				<a class="rmore1" href="<?php echo $this->item->link; ?>">Read More</a>
				<a class="rmore2" href="<?php echo $this->item->link; ?>">Read More</a>
			</div>
		</div>
	</div>
</div>
