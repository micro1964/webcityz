<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

	$centerWidth = $tmplWidth;
        $left4colgrid 	= $gridParams->left4width;
        $right4colgrid	= $gridParams->right4width;
        if ($this->countModules('left4') == 0){
         $left4colgrid  = "0";
        }

        if ($this->countModules('right4') == 0){
         $right4colgrid  = "0";
        }

        $left4 = $this->countModules( 'left4' );
	$right4 = $this->countModules( 'right4' );
	$banner = $this->countModules( 'banner' );

        $areaWidth =  100;
	$order = 'user13,user14,user15,user16,user17,user18';
	$columnArray = array(
	        'user13' => '<jdoc:include type="modules" name="user13" style="xtc" />',
		'user14' => '<jdoc:include type="modules" name="user14" style="xtc" />',
		'user15' => '<jdoc:include type="modules" name="user15" style="xtc" />',
		'user16' => '<jdoc:include type="modules" name="user16" style="xtc" />',
		'user17' => '<jdoc:include type="modules" name="user17" style="xtc" />',
		'user18' => '<jdoc:include type="modules" name="user18" style="xtc" />'
	);

	$columnClass = '';
	$debug = 0;
	$user13_18 = xtcBootstrapGrid($columnArray,$order,'',$columnClass);

	if ($left4 || $banner || $user13_18 || $right4) {
        echo '<div id="region4wrap" class="xtc-bodygutter">';
        echo '<div id="region4pad" class="xtc-wrapperpad">';
	echo '<div id="region4" class="row-fluid xtc-wrapper r4spacer">';
 
 
        if ($left4) { echo '<div id="left4" class="span'.$left4colgrid.'"><jdoc:include type="modules" name="left4" style="xtc" /></div>';}
        
        if ($banner || $user13_18) {
	echo '<div class="center span'.(12-$left4colgrid-$right4colgrid).'">';
        if ($banner) { echo '<div id="region4_banner" class="r4spacer_top"><jdoc:include type="modules" name="banner" style="xtc" /></div>';}
        if ($user13_18) { echo '<div id="user13_18" class="clearfix r4spacer_top">'.$user13_18.'</div>'; }
	echo '</div>';
        }
        
	if ($right4) { echo '<div id="right4" class="span'.$right4colgrid.'"><jdoc:include type="modules" name="right4" style="xtc" /></div>';}
	echo '</div>';
        echo '</div>';
	echo '</div>';
	}
?>