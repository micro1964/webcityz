<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

	$centerWidth = $tmplWidth;
        $left5colgrid 	= $gridParams->left5width;
        $right5colgrid	= $gridParams->right5width;
        if ($this->countModules('left5') == 0){
         $left5colgrid  = "0";
        }

        if ($this->countModules('right5') == 0){
         $right5colgrid  = "0";
        }

        $left5 = $this->countModules( 'left5' );
	$right5 = $this->countModules( 'right5' );

        $areaWidth =  100;
	$order = 'user19,user20,user21,user22,user23,user24';
	$columnArray = array(
	        'user19' => '<jdoc:include type="modules" name="user19" style="xtc" />',
		'user20' => '<jdoc:include type="modules" name="user20" style="xtc" />',
		'user21' => '<jdoc:include type="modules" name="user21" style="xtc" />',
		'user22' => '<jdoc:include type="modules" name="user22" style="xtc" />',
		'user23' => '<jdoc:include type="modules" name="user23" style="xtc" />',
		'user24' => '<jdoc:include type="modules" name="user24" style="xtc" />'
	);

	$columnClass = '';
	$debug = 0;
	$user19_24 = xtcBootstrapGrid($columnArray,$order,'',$columnClass);

	if ($left5 || $user19_24 || $right5) {
        echo '<div id="region5wrap" class="xtc-bodygutter">';
        echo '<div id="region5pad" class="xtc-wrapperpad">';
	echo '<div id="region5" class="row-fluid xtc-wrapper r5spacer">';

        if ($left5) { echo '<div id="left5" class="span'.$left5colgrid.'"><jdoc:include type="modules" name="left5" style="xtc" /></div>';}
       if ($user19_24) {
echo '<div class="center span'.(12-$left5colgrid-$right5colgrid).'">';
     
        if ($user19_24) { echo '<div id="user19_24" class="clearfix r5spacer_top">'.$user19_24.'</div>'; }
	echo '</div>';
        }
	if ($right5) { echo '<div id="right5" class="span'.$right5colgrid.'"><jdoc:include type="modules" name="right5" style="xtc" /></div>';}
	echo '</div>';
        echo '</div>';
	echo '</div>';
	}
?>