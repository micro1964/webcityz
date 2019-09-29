<?php
defined('_JEXEC') or die;

$images = json_decode($this->item->images);
$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
?>
<div class="catItemImage">
	<a href="<?php echo $link; ?>"><img src="<?php echo $images->image_intro; ?>" class="intimage"  /></a>
	<div class="zoom">
		<div class="newstext">
			<div class="newstext2">
				<h3><a class="catlink" href="<?php echo $link; ?>"><?php echo $this->item->title; ?></a></h3>
				<div class="introtext"><?php echo $this->item->introtext; ?></div>
				<a class="rmore1" href="<?php echo $link; ?>">Read More</a>
				<a class="rmore2" href="<?php echo $link; ?>">Read More</a>
			</div>
		</div>
	</div>
</div>
