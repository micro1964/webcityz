<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

	$centerWidth = $tmplWidth;

        $left7colgrid 	= $gridParams->left7width;

        $right7colgrid	= $gridParams->right7width;

        if ($this->countModules('left7') == 0){

         $left7colgrid  = "0";

        }



        if ($this->countModules('right7') == 0){

         $right7colgrid  = "0";

        }



        $left7 = $this->countModules( 'left7' );

	$right7 = $this->countModules( 'right7' );



        $areaWidth =  100;

	$order = 'user31,user32,user33,user34,user35,user36';

	$columnArray = array(

	        'user31' => '<jdoc:include type="modules" name="user31" style="xtc" />',

		'user32' => '<jdoc:include type="modules" name="user32" style="xtc" />',

		'user33' => '<jdoc:include type="modules" name="user33" style="xtc" />',

		'user34' => '<jdoc:include type="modules" name="user34" style="xtc" />',

		'user35' => '<jdoc:include type="modules" name="user35" style="xtc" />',

		'user36' => '<jdoc:include type="modules" name="user36" style="xtc" />'

	);



	$columnClass = '';

	$debug = 0;

	$user31_36 = xtcBootstrapGrid($columnArray,$order,'',$columnClass);



	if ($left7 || $user31_36 || $right7) {

        echo '<div id="region7wrap" class="xtc-bodygutter">';

        echo '<div id="region7pad" class="xtc-wrapperpad">';

	echo '<div id="region7" class="row-fluid xtc-wrapper r7spacer">';



        if ($left7) { echo '<div id="left7" class="span'.$left7colgrid.'"><jdoc:include type="modules" name="left7" style="xtc" /></div>';}
        if ($user31_36) {
	echo '<div class="center span'.(12-$left7colgrid-$right7colgrid).'">';

        

        if ($user31_36) { echo '<div id="user31_36" class="clearfix">'.$user31_36.'</div>'; }

	echo '</div>';
}
	if ($right7) { echo '<div id="right7" class="span'.$right7colgrid.'"><jdoc:include type="modules" name="right7" style="xtc" /></div>';}

	echo '</div>';

        echo '</div>';

	echo '</div>';

	}

?>