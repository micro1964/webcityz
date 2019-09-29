<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

require 'default_toolbar.php';

jimport('joomla.html.parameter');
JHTML::_('behavior.modal', 'a.modal');
?>

<div id="k2Container">
    <?php if ($item->params->get('itemImage') && !empty($item->imageXLarge)): ?>
        <div class="itemImageBlock">
            <span class="itemImage">
                <a class="modal" rel="{handler: 'image'}" href="<?php echo $item->imageXLarge; ?>" title="<?php echo JText::_('K2_CLICK_TO_PREVIEW_IMAGE'); ?>">
                    <img src="<?php echo $item->image; ?>" style="width:<?php echo $item->imageWidth; ?>px; height:auto;" />
                </a>
            </span>
        </div>
    <?php endif; ?>

    <?php if (!empty($item->fulltext)): ?>
        <?php if ($item->params->get('itemIntroText')): ?>
            <!-- Item introtext -->
            <div class="itemIntroText">
                <?php echo $item->introtext; ?>
            </div>
        <?php endif; ?>
        <?php if ($item->params->get('itemFullText')): ?>
            <!-- Item fulltext -->
            <div class="itemFullText">
                <?php echo $item->fulltext; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- Item text -->
        <div class="itemFullText">
            <?php echo $item->introtext; ?>
        </div>
    <?php endif; ?>

    <div class="clr"></div>

    <?php if ($item->params->get('itemVideo') && !empty($item->video)): ?>
        <!-- Item video -->
        <a name="itemVideoAnchor" id="itemVideoAnchor"></a>

        <div class="itemVideoBlock">
            <h3><?php echo JText::_('K2_MEDIA'); ?></h3>
            
            <?php if ($item->videoType == 'embedded'): ?>
                <div class="itemVideoEmbedded">
                    <?php echo $item->video; ?>
                </div>
            <?php else: ?>
                <span class="itemVideo"><?php echo $item->video; ?></span>
            <?php endif; ?>

            <div class="clr"></div>
        </div>
    <?php endif; ?>

    <?php if ($item->params->get('itemImageGallery') && !empty($item->gallery)): ?>
        <!-- Item image gallery -->
        <a name="itemImageGalleryAnchor" id="itemImageGalleryAnchor"></a>
        <div class="itemImageGallery">
            <h3><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h3>
            <?php echo $item->gallery; ?>
        </div>
    <?php endif; ?>
</div>