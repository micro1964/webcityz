<?php
/**
 * @version		$Id: category.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$css = '
.mozaix_content_layout .areamain .col-1 {width: 50%!important;float:right!important;}
.mozaix_content_layout .areamain .centercol, .mozaix_content_layout .areamain .lastcol {width: 25%!important;float:left!important;clear:none!important;}
.mozaix_content_layout .areamore .col-1 {width: 50%!important; float:left!important;}
.mozaix_content_layout .areamore .centercol {width: 25%!important;float:right!important;clear:none!important;}
.mozaix_content_layout .areamore .lastcol {width: 25%!important;float:right!important;clear:none!important;}

/* Element Style */

.mozaix_content_layout .newstext2 {color:#fff;}
.mozaix_content_layout .catItemImage {overflow:hidden;}
.mozaix_content_layout .k2wall_introtext {margin:8px 0!important;opacity:0;height:0px;}
.mozaix_content_layout .rmore2 {display:none;font-size: 1em; text-align:center;padding: 10px 12px;background:rgba(0,0,0,0.75)!important; color:#fff; opacity:0}
.mozaix_content_layout .rmore1 {display:inline-block;background:none!important;border:1px solid #FFF;color:#FFF;}
.mozaix_content_layout .col-1 .rmore1 {-webkit-box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.14);-moz-box-shadow:0px 0px 30px rgba(0, 0, 0, 0.14);box-shadow:0px 0px 30px rgba(0, 0, 0, 0.14);}
.mozaix_content_layout h3 {font-size:24px!important;color: rgba(255,255,255,0.35); margin-bottom:0!important;}
.mozaix_content_layout .col-1 .newstext2 {position:relative; z-index:4; vertical-align:bottom!important; padding:4.75%!important;}
.mozaix_content_layout .col-1 h3 a {font-size:44px!important;text-shadow: 0 0 20px rgba(0,0,0,1)!important;}
.mozaix_content_layout .col-1 .newstext2 a {text-shadow: 0 0 20px rgba(0,0,0,1)!important;}
.mozaix_content_layout .col-1 .k2wall_introtext {height:auto;opacity:1;text-shadow: 0 0 20px rgba(0,0,0,1)!important;}
.mozaix_content_layout .k2-zoom{z-index:10!important;position:absolute;width:90%;height:90%;display:block;top:5%;left:5%;opacity:1;}
.mozaix_content_layout .k2-zoom .newstext{position:absolute;width:100%;height:100%;top:0;left:0;display:block;margin:0;position:relative;display:table;margin:0 auto;}
.mozaix_content_layout .k2-zoom .newstext2{display:table-cell;vertical-align:middle;padding:5% 15%;}

/* TEXT HOVER ANIMATIONS */

/* Initial state with no images */

.mozaix_content_layout .wallfloat.col-3 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-4 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-7 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-9 .catItemImage h3 {
			   opacity: 0;
	 -webkit-transform: translateY(-300px);
	    -moz-transform: translateY(-300px);
	         transform: translateY(-300px);
	-webkit-transition: all 0.4s ease-in-out;
	   -moz-transition: all 0.4s ease-in-out;
	     -o-transition: all 0.4s ease-in-out;
	    -ms-transition: all 0.4s ease-in-out;
	        transition: all 0.4s ease-in-out;
}
.mozaix_content_layout .col-3 .catItemImage:hover h3,
.mozaix_content_layout .col-4 .catItemImage:hover h3,
.mozaix_content_layout .col-7 .catItemImage:hover h3,
.mozaix_content_layout .col-9 .catItemImage:hover h3 {
	           opacity: 1;
	 -webkit-transform: translateY(0px);
	    -moz-transform: translateY(0px);
	         transform: translateY(0px);
	-webkit-transition: all 0.4s ease-in-out;
	   -moz-transition: all 0.4s ease-in-out;
	     -o-transition: all 0.4s ease-in-out;
	    -ms-transition: all 0.4s ease-in-out;
	        transition: all 0.4s ease-in-out;
}
.mozaix_content_layout .wallfloat.col-3 .catItemImage .rmore1,
.mozaix_content_layout .wallfloat.col-4 .catItemImage .rmore1,
.mozaix_content_layout .wallfloat.col-7 .catItemImage .rmore1,
.mozaix_content_layout .wallfloat.col-9 .catItemImage .rmore1 {
			   opacity: 0;
	 -webkit-transform: translateX(700px);
	    -moz-transform: translateX(700px);
	         transform: translateX(700px);
    -webkit-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
       -moz-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        -ms-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
         -o-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
            transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}
.mozaix_content_layout .col-3 .catItemImage:hover .rmore1,
.mozaix_content_layout .col-4 .catItemImage:hover .rmore1,
.mozaix_content_layout .col-7 .catItemImage:hover .rmore1,
.mozaix_content_layout .col-9 .catItemImage:hover .rmore1 {
	           opacity: 1;
	 -webkit-transform: translateY(0px);
	    -moz-transform: translateY(0px);
	         transform: translateY(0px);
    -webkit-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
       -moz-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        -ms-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
         -o-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
            transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}
.mozaix_content_layout .wallfloat.col-3 .catItemImage .intimage,
.mozaix_content_layout .wallfloat.col-4 .catItemImage .intimage,
.mozaix_content_layout .wallfloat.col-7 .catItemImage .intimage,
.mozaix_content_layout .wallfloat.col-9 .catItemImage .intimage {
		           opacity: 1;
	 -webkit-transform: translateY(0px);
	    -moz-transform: translateY(0px);
	         transform: translateY(0px);
    -webkit-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
       -moz-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        -ms-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
         -o-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
            transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}
.mozaix_content_layout .wallfloat.col-3 .catItemImage:hover .intimage,
.mozaix_content_layout .wallfloat.col-4 .catItemImage:hover .intimage,
.mozaix_content_layout .wallfloat.col-7 .catItemImage:hover .intimage,
.mozaix_content_layout .wallfloat.col-9 .catItemImage:hover .intimage {
opacity: 0;
	 -webkit-transform: translateY(-300px);
	    -moz-transform: translateY(-300px);
	         transform: translateY(-300px);
    -webkit-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
       -moz-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        -ms-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
         -o-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
            transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}

/* Initial state with images */

.mozaix_content_layout .wallfloat.col-2 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-5 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-6 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-8 .catItemImage h3 {margin-top:18px;}

.mozaix_content_layout .wallfloat.col-2 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-5 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-6 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-8 .catItemImage h3 {
			   opacity: 1;
	 -webkit-transform: translateY(0px);
	    -moz-transform: translateY(0px);
	         transform: translateY(0px);
-webkit-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
   -moz-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
    -ms-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
     -o-transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        transition: all 400ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}
.mozaix_content_layout .col-2 .catItemImage:hover h3,
.mozaix_content_layout .col-5 .catItemImage:hover h3,
.mozaix_content_layout .col-6 .catItemImage:hover h3,
.mozaix_content_layout .col-8 .catItemImage:hover h3 {
	           opacity: 0;
	 -webkit-transform: translateY(300px);
	    -moz-transform: translateY(300px);
	         transform: translateY(300px);
-webkit-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
   -moz-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
    -ms-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
     -o-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}

.mozaix_content_layout .wallfloat.col-2 .catItemImage .rmore1,
.mozaix_content_layout .wallfloat.col-5 .catItemImage .rmore1
.mozaix_content_layout .wallfloat.col-6 .catItemImage .rmore1,
.mozaix_content_layout .wallfloat.col-8 .catItemImage .rmore1 {
			   opacity: 1;
			  
	 -webkit-transform: translateX(0px);
	    -moz-transform: translateX(0px);
	         transform: translateX(0px);
-webkit-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
   -moz-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
    -ms-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
     -o-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}
.mozaix_content_layout .col-2 .catItemImage:hover .rmore1,
.mozaix_content_layout .col-5 .catItemImage:hover .rmore1,
.mozaix_content_layout .col-6 .catItemImage:hover .rmore1,
.mozaix_content_layout .col-8 .catItemImage:hover .rmore1 {
	           opacity: 0;
	 -webkit-transform: translateX(900px);
	    -moz-transform: translateX(900px);
	         transform: translateX(900px);
-webkit-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
   -moz-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
    -ms-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
     -o-transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        transition: all 300ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}

.mozaix_content_layout .wallfloat.col-2 .catItemImage .rmore2,
.mozaix_content_layout .wallfloat.col-5 .catItemImage .rmore2,
.mozaix_content_layout .wallfloat.col-6 .catItemImage .rmore2,
.mozaix_content_layout .wallfloat.col-8 .catItemImage .rmore2 {
	           opacity: 0;
			   display: block;
	 -webkit-transform: translateY(-120px);
	    -moz-transform: translateY(-120px);
	         transform: translateY(-120px);
	-webkit-transition: all 0.2s;
	   -moz-transition: all 0.2s;
	     -o-transition: all 0.2s;
	    -ms-transition: all 0.2s;
	        transition: all 0.2s;
}
.mozaix_content_layout .wallfloat.col-2 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-5 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-6 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-8 .catItemImage:hover .rmore2 {
	           opacity: 1; 
	 -webkit-transform: translateY(-75px);
	    -moz-transform: translateY(-75px);
	         transform: translateY(-75px);
-webkit-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
   -moz-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
    -ms-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
     -o-transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
        transition: all 600ms cubic-bezier(0.650, 0.065, 0.380, 0.890);
}
.mozaix_content_layout .wallfloat.col-2 .catItemImage .intimage,
.mozaix_content_layout .wallfloat.col-5 .catItemImage .intimage,
.mozaix_content_layout .wallfloat.col-6 .catItemImage .intimage,
.mozaix_content_layout .wallfloat.col-8 .catItemImage .intimage {
	           opacity: 0;
	 -webkit-transform: translateY(300px);
	    -moz-transform: translateY(300px);
	         transform: translateY(300px);
	-webkit-transition: all 0.4s ease-in-out;
	   -moz-transition: all 0.4s ease-in-out;
	     -o-transition: all 0.4s ease-in-out;
	    -ms-transition: all 0.4s ease-in-out;
	        transition: all 0.4s ease-in-out;
}
.mozaix_content_layout .wallfloat.col-2 .catItemImage:hover .intimage,
.mozaix_content_layout .wallfloat.col-5 .catItemImage:hover .intimage,
.mozaix_content_layout .wallfloat.col-6 .catItemImage:hover .intimage,
.mozaix_content_layout .wallfloat.col-8 .catItemImage:hover .intimage {
	           opacity: 1;
	 -webkit-transform: translateY(0px);
	    -moz-transform: translateY(0px);
	         transform: translateY(0px);
	-webkit-transition: all 0.4s ease-in-out;
	   -moz-transition: all 0.4s ease-in-out;
	     -o-transition: all 0.4s ease-in-out;
	    -ms-transition: all 0.4s ease-in-out;
	        transition: all 0.4s ease-in-out;
}
@media(max-width:1300px){
.mozaix_content_layout .rmore1 {padding:2px 9px !important; font-size:11px!important;margin-top:10px!important}
.mozaix_content_layout .k2-zoom .newstext2{padding:3% 5%!important;}
.mozaix_content_layout .k2wall_introtext {display:none;}
.mozaix_content_layout .wallfloat.col-2 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-5 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-6 .catItemImage h3,
.mozaix_content_layout .wallfloat.col-8 .catItemImage h3 {margin-top:12px;}
}
@media(max-width:1040px){
.mozaix_content_layout .col-1 h3 a, .mozaix_content_layout .col-1 h3, .mozaix_content_layout h3 {font-size:24px!important;line-height:120%!important;margin-bottom:4px!important;}
.mozaix_content_layout .newstext2, .mozaix_content_layout .col-1 .newstext2 { vertical-align:middle!important; padding:1.8% 3%!important;}
.mozaix_content_layout .areamain .firstcol,
.mozaix_content_layout .areamain .centercol,
.mozaix_content_layout .areamain .lastcol {width: 33.3%!important;}
.mozaix_content_layout .areamore .firstcol,
.mozaix_content_layout .areamore .centercol,
.mozaix_content_layout .areamore .lastcol {width: 33.3%!important;}
.mozaix_content_layout .wallfloat.col-2 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-5 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-6 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-8 .catItemImage:hover .rmore2 {
	 -webkit-transform: translateY(-60px);
	    -moz-transform: translateY(-60px);
	         transform: translateY(-60px);
}
@media (max-width: 920px) {
.mozaix_content_layout .col-1 h3 a, .mozaix_content_layout .col-1 h3, .mozaix_content_layout h3 {font-size:21px!important;margin-bottom:2px!important;}
.mozaix_content_layout .wallfloat.col-2 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-5 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-6 .catItemImage:hover .rmore2,
.mozaix_content_layout .wallfloat.col-8 .catItemImage:hover .rmore2 {
	 -webkit-transform: translateY(-72px);
	    -moz-transform: translateY(-72px);
	         transform: translateY(-72px);	
}
@media (max-width: 767px) {
.mozaix_content_layout .newstext2 { vertical-align:middle!important}
.mozaix_content_layout .col-1 .newstext2 {position:relative; z-index:4; vertical-align:bottom!important; }
.mozaix_content_layout .col-1 h3 a {font-size:40px!important;text-shadow: 0 0 20px rgba(0,0,0,1)!important;}
.mozaix_content_layout h3 {font-size:26px!important;line-height:120%!important;margin-bottom:4px!important;}
.mozaix_content_layout .areamain .firstcol {width: 100%!important;}
.mozaix_content_layout .areamain .centercol,
.mozaix_content_layout .areamain .lastcol {width: 50%!important;}
.mozaix_content_layout .areamore .firstcol {width: 100%!important;}
.mozaix_content_layout .areamore .centercol,
.mozaix_content_layout .areamore .lastcol {width: 50%!important;}
}
@media(max-width:620px){
.mozaix_content_layout .newstext2 { vertical-align:bottom!important}
.mozaix_content_layout .col-1 h3 a, .mozaix_content_layout .col-1 h3, .mozaix_content_layout h3 {font-size:32px!important;line-height:120%!important;margin-bottom:4px!important;}
.mozaix_content_layout .areamain .firstcol,
.mozaix_content_layout .areamain .centercol,
.mozaix_content_layout .areamain .lastcol,
.mozaix_content_layout .areamore .firstcol,
.mozaix_content_layout .areamore .centercol,
.mozaix_content_layout .areamore .lastcol {width: 100%!important;}
';
//$doc->addStyleDeclaration($css);
?>

<!-- Start K2 Category Layout -->
<div id="k2Container" class="itemListView<?php if($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">

	<?php if($this->params->get('show_page_title')): ?>
	<!-- Page title -->
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php endif; ?>

	<?php if($this->params->get('catFeedIcon')): ?>
	<!-- RSS feed icon -->
	<div class="k2FeedIcon">
		<a href="<?php echo $this->feed; ?>" title="<?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
			<span><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></span>
		</a>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

	<?php if(isset($this->category) || ( $this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories) )): ?>
	<!-- Blocks for current category and subcategories -->
	<div class="itemListCategoriesBlock">

		<?php if(isset($this->category) && ( $this->params->get('catImage') || $this->params->get('catTitle') || $this->params->get('catDescription') || $this->category->event->K2CategoryDisplay )): ?>
		<!-- Category block -->
		<div class="itemListCategory">

			<?php if(isset($this->addLink)): ?>
			<!-- Item add link -->
			<span class="catItemAddLink">
				<a class="modal" rel="{handler:'iframe',size:{x:990,y:650}}" href="<?php echo $this->addLink; ?>">
					<?php echo JText::_('K2_ADD_A_NEW_ITEM_IN_THIS_CATEGORY'); ?>
				</a>
			</span>
			<?php endif; ?>

			<?php if($this->params->get('catImage') && $this->category->image): ?>
			<!-- Category image -->
			<img alt="<?php echo K2HelperUtilities::cleanHtml($this->category->name); ?>" src="<?php echo $this->category->image; ?>" style="width:<?php echo $this->params->get('catImageWidth'); ?>px; height:auto;" />
			<?php endif; ?>

			<?php if($this->params->get('catTitle')): ?>
			<!-- Category title -->
			<h2><?php echo $this->category->name; ?><?php if($this->params->get('catTitleItemCounter')) echo ' ('.$this->pagination->total.')'; ?></h2>
			<?php endif; ?>

			<?php if($this->params->get('catDescription')): ?>
			<!-- Category description -->
			<p><?php echo $this->category->description; ?></p>
			<?php endif; ?>

			<!-- K2 Plugins: K2CategoryDisplay -->
			<?php echo $this->category->event->K2CategoryDisplay; ?>

			<div class="clr"></div>
		</div>
		<?php endif; ?>

		<?php if($this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories)): ?>
		<!-- Subcategories -->
		<div class="itemListSubCategories">
			<h3><?php echo JText::_('K2_CHILDREN_CATEGORIES'); ?></h3>

			<?php foreach($this->subCategories as $key=>$subCategory): ?>

			<?php
			// Define a CSS class for the last container on each row
			if( (($key+1)%($this->params->get('subCatColumns'))==0))
				$lastContainer= ' subCategoryContainerLast';
			else
				$lastContainer='';
			?>

			<div class="subCategoryContainer<?php echo $lastContainer; ?>"<?php echo (count($this->subCategories)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('subCatColumns'), 1).'%;"'; ?>>
				<div class="subCategory">
					<?php if($this->params->get('subCatImage') && $subCategory->image): ?>
					<!-- Subcategory image -->
					<a class="subCategoryImage" href="<?php echo $subCategory->link; ?>">
						<img alt="<?php echo K2HelperUtilities::cleanHtml($subCategory->name); ?>" src="<?php echo $subCategory->image; ?>" />
					</a>
					<?php endif; ?>

					<?php if($this->params->get('subCatTitle')): ?>
					<!-- Subcategory title -->
					<h2>
						<a href="<?php echo $subCategory->link; ?>">
							<?php echo $subCategory->name; ?><?php if($this->params->get('subCatTitleItemCounter')) echo ' ('.$subCategory->numOfItems.')'; ?>
						</a>
					</h2>
					<?php endif; ?>

					<?php if($this->params->get('subCatDescription')): ?>
					<!-- Subcategory description -->
					<p><?php echo $subCategory->description; ?></p>
					<?php endif; ?>

					<!-- Subcategory more... -->
					<a class="subCategoryMore" href="<?php echo $subCategory->link; ?>">
						<?php echo JText::_('K2_VIEW_ITEMS'); ?>
					</a>

					<div class="clr"></div>
				</div>
			</div>
			<?php if(($key+1)%($this->params->get('subCatColumns'))==0): ?>
			<div class="clr"></div>
			<?php endif; ?>
			<?php endforeach; ?>

			<div class="clr"></div>
		</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>



	<?php if((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>
	<!-- Item list -->
	<div class="itemList mozaix_content_layout">

		<?php if(isset($this->leading) && count($this->leading)): ?>
			<!-- Leading items -->
			<div id="itemListLeading">
				<?php foreach($this->leading as $key=>$item): ?>
	
				<?php
				// Define a CSS class for the last container on each row
				if( (($key+1)%($this->params->get('num_leading_columns'))==0) || count($this->leading)<$this->params->get('num_leading_columns') )
					$lastContainer= ' itemContainerLast';
				else
					$lastContainer='';
				?>
				
				<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->leading)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_leading_columns'), 1).'%;"'; ?>>
					<?php
						// Load category_item.php by default
						$this->item=$item;
						echo $this->loadTemplate('item');
					?>
				</div>
				<?php if(($key+1)%($this->params->get('num_leading_columns'))==0): ?>
				<div class="clr"></div>
				<?php endif; ?>
				<?php endforeach; ?>
				<div class="clr"></div>
			</div>
		<?php endif; ?>


		<?php if(isset($this->primary) && count($this->primary)): ?>
			<!-- Primary items -->
			<div class="areamain">
				<?php
					$numColumns = $this->params->get('num_primary_columns');
					$rows = array_chunk($this->primary, $numColumns);
					$itemRowsCount = count($rows);
	
					$elementWidth = round(100 / $numColumns,4);
					$elementHeight = round(100 / $itemRowsCount,4);
					
					foreach ($rows as $r => $row) {
						$r++;
						if ($itemRowsCount == 1) { $rowclass = 'singlerow'; }	// Row class
						elseif ($r == 1) { $rowclass = 'firstrow'; }
						elseif ($r == $itemRowsCount) { $rowclass = 'lastrow'; }
						else { $rowclass = 'centerrow'; }
						$rowclass .= ($r%2) ? ' oddrow' : ' evenrow';
	
						$itemColumnCount = count($row);
						foreach ($row as $c => $item) {
							$c++;
							if ($itemColumnCount == 1) { $colclass = 'singlecol'; } 	// Col class
							elseif ($c == 1) { $colclass = 'firstcol'; }
							elseif ($c == $itemColumnCount) { $colclass = 'lastcol'; }
							else { $colclass = 'centercol'; }
							$colclass .= ($c%2) ? ' oddcol' : ' evencol';
							
							echo '<div class="'.$rowclass.' row-'.$r.' '.$colclass.' col-'.$c.'" style="width:'.$elementWidth.'%;height:'.$elementHeight.'%" >';
							$this->item=$item;
							echo $this->loadTemplate('item');
							echo '</div>';
						}
					}
				?>		
				<div class="clr"></div>
			</div>
		<?php endif; ?>

		<?php if(isset($this->secondary) && count($this->secondary)): ?>
			<!-- Secondary items -->
			<div class="areamore">
				<?php
					$numColumns = $this->params->get('num_secondary_columns');
					$rows = array_chunk($this->secondary, $numColumns);
					$itemRowsCount = count($rows);
	
					$elementWidth = round(100 / $numColumns,4);
					$elementHeight = round(100 / $itemRowsCount,4);
					
					foreach ($rows as $r => $row) {
						if ($itemRowsCount == 1) { $rowclass = 'singlerow'; }	// Row class
						elseif ($r == 0) { $rowclass = 'firstrow'; }
						elseif ($r == $itemRowsCount-1) { $rowclass = 'lastrow'; }
						else { $rowclass = 'centerrow'; }
						$rowclass .= ($r%2) ? ' oddrow' : ' evenrow';
	
						$itemColumnCount = count($row);
						foreach ($row as $c => $item) {
							if ($itemColumnCount == 1) { $colclass = 'singlecol'; } 	// Col class
							elseif ($c == 0) { $colclass = 'firstcol'; }
							elseif ($c == $itemColumnCount-1) { $colclass = 'lastcol'; }
							else { $colclass = 'centercol'; }
							$colclass .= ($c%2) ? ' oddcol' : ' evencol';
							
							echo '<div class="'.$rowclass.' row-'.$r.' '.$colclass.' col-'.($c+1).'" style="width:'.$elementWidth.'%;height:'.$elementHeight.'%" >';
							$this->item=$item;
							echo $this->loadTemplate('item');
							echo '</div>';
						}
					}
				?>		
				<div class="clr"></div>
			</div>
		<?php endif; ?>

		<?php if(isset($this->links) && count($this->links)): ?>
			<!-- Link items -->
			<div id="itemListLinks">
				<h4><?php echo JText::_('K2_MORE'); ?></h4>
				<?php foreach($this->links as $key=>$item): ?>
	
				<?php
				// Define a CSS class for the last container on each row
				if( (($key+1)%($this->params->get('num_links_columns'))==0) || count($this->links)<$this->params->get('num_links_columns') )
					$lastContainer= ' itemContainerLast';
				else
					$lastContainer='';
				?>
	
				<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($this->links)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_links_columns'), 1).'%;"'; ?>>
					<?php
						// Load category_item_links.php by default
						$this->item=$item;
						echo $this->loadTemplate('item_links');
					?>
				</div>
				<?php if(($key+1)%($this->params->get('num_links_columns'))==0): ?>
				<div class="clr"></div>
				<?php endif; ?>
				<?php endforeach; ?>
				<div class="clr"></div>
			</div>
		<?php endif; ?>

	</div>

	<!-- Pagination -->
	<?php if(count($this->pagination->getPagesLinks())): ?>
	<div class="k2Pagination">
		<?php if($this->params->get('catPagination')) echo $this->pagination->getPagesLinks(); ?>
		<div class="clr"></div>
		<?php if($this->params->get('catPaginationResults')) echo $this->pagination->getPagesCounter(); ?>
	</div>
	<?php endif; ?>

	<?php endif; ?>
</div>
<!-- End K2 Category Layout -->
