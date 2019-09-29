<?php
/*******************************************************************************
********************************************************************************
***                                                                          ***
***   XTC Template Framework 1.0                                             ***
***                                                                          ***
***   Copyright (c) 2010 Monev Software LLC                                  ***
***                                                                          ***
***   All Rights Reserved                                                    ***
***                                                                          ***
********************************************************************************
*******************************************************************************/
defined( '_JEXEC' ) or die;

/*
 *	IMPORTANT:
 *
 * To customize the <HEAD> or <BODY> tags
 * edit the corresponding layout.php files
 * inside the layouts/ folder of the template.
 *
 */

// Call XTC framework
require JPATH_THEMES.'/'.$this->template.'/XTC/XTC.php';

// Load template parameters
$templateParameters = xtcLoadParams();

// Get the selected layout
$layout = $templateParameters->templateLayout;

// Call layout from layouts folder to create HTML

if ($xtc->agent->isMobile && $templateParameters->mobiledetect) {
	require JPATH_THEMES.'/'.$this->template.'/layouts/mobile/layout.php';
}
else {
	require JPATH_THEMES.'/'.$this->template.'/layouts/'.$layout.'/layout.php';
}
