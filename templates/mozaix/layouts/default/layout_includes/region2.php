<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

	$centerWidth = $tmplWidth;;
        $left2colgrid 	= $gridParams->left2width;
        $right2colgrid	= $gridParams->right2width;
        if ($this->countModules('left2') == 0){
         $left2colgrid  = "0";
        }

        if ($this->countModules('right2') == 0){
         $right2colgrid  = "0";
        }
        $left2 = $this->countModules( 'left2' );
	$right2 = $this->countModules( 'right2' );

	//if ($left2) {$centerWidth -= ($gridParams->left2width + $gridParams->columnseparatorwidth); }
	//if ($right2) {$centerWidth -= ($gridParams->right2width + $gridParams->columnseparatorwidth); }
		

        $areaWidth =  100;
	$order = 'user1,user2,user3,user4,user5,user6';
	$columnArray = array(
		'user1' => '<jdoc:include type="modules" name="user1" style="xtc" />',
		'user2' => '<jdoc:include type="modules" name="user2" style="xtc" />',
		'user3' => '<jdoc:include type="modules" name="user3" style="xtc" />',
		'user4' => '<jdoc:include type="modules" name="user4" style="xtc" />',
		'user5' => '<jdoc:include type="modules" name="user5" style="xtc" />',
		'user6' => '<jdoc:include type="modules" name="user6" style="xtc" />'
	);

	$customWidths = '';
	$columnClass = '';
	$columnPadding = '';
	$debug = 0;
	$user1_6 = xtcBootstrapGrid($columnArray,$order,'',$columnClass,$debug);
	
	if ($left2 || $user1_6 || $right2) {
    echo '<div id="region2wrap" class="xtc-bodygutter">';
    echo '<div id="region2pad" class="xtc-wrapperpad">';
	echo '<div id="region2" class="row-fluid xtc-wrapper r2spacer">';
    if ($left2) { echo '<div id="left2" class="span'.$left2colgrid.'"><jdoc:include type="modules" name="left2" style="xtc" /></div>';}

	if ($user1_6) { echo '<div id="user1_6" class="span'.(12-$left2colgrid-$right2colgrid).'">'.$user1_6.'</div>'; }
	 if ($right2) { echo '<div id="right2" class="span'.$right2colgrid.'"><jdoc:include type="modules" name="right2" style="xtc" /></div>';}
	echo '</div>';
        echo '</div>';
	echo '</div>';
	}
?>