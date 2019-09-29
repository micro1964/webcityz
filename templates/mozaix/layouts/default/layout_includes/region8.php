<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/


	$centerWidth = $tmplWidth;

        $left8colgrid 	= $gridParams->left8width;

        $right8colgrid	= $gridParams->right8width;

        if ($this->countModules('left8') == 0){

         $left8colgrid  = "0";

        }



        if ($this->countModules('right8') == 0){

         $right8colgrid  = "0";

        }



        $left8 = $this->countModules( 'left8' );

	$right8 = $this->countModules( 'right8' );



        $areaWidth =  100;

	$order = 'user37,user38,user39,user40,user41,user42';

	$columnArray = array(

	        'user37' => '<jdoc:include type="modules" name="user37" style="xtc" />',

		'user38' => '<jdoc:include type="modules" name="user38" style="xtc" />',

		'user39' => '<jdoc:include type="modules" name="user39" style="xtc" />',

		'user40' => '<jdoc:include type="modules" name="user40" style="xtc" />',

		'user41' => '<jdoc:include type="modules" name="user41" style="xtc" />',

		'user42' => '<jdoc:include type="modules" name="user42" style="xtc" />'

	);



	$columnClass = '';

	$debug = 0;

	$user37_42 = xtcBootstrapGrid($columnArray,$order,'',$columnClass);



	if ($left8 || $user37_42 || $right8) {

        echo '<div id="region8wrap" class="xtc-bodygutter">';

        echo '<div id="region8pad" class="xtc-wrapperpad">';

	echo '<div id="region8" class="row-fluid xtc-wrapper r8spacer">';



        if ($left8) { echo '<div id="left8" class="span'.$left8colgrid.'"><jdoc:include type="modules" name="left8" style="xtc" /></div>';}
        
        if ($user37_42) {

	echo '<div class="center span'.(12-$left8colgrid-$right8colgrid).'">';

        

        if ($user37_42) { echo '<div id="user37_42" class="clearfix">'.$user37_42.'</div>'; }

	echo '</div>';
        }

	if ($right8) { echo '<div id="right8" class="span'.$right8colgrid.'"><jdoc:include type="modules" name="right8" style="xtc" /></div>';}

	echo '</div>';

        echo '</div>';

	echo '</div>';

	}

?>