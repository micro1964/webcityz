<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/


	$centerWidth = $tmplWidth;;	

        $areaWidth =  $centerWidth;

	$order = 'bottom1,bottom2,bottom3,bottom4,bottom5,bottom6';

	$columnArray = array(

		'bottom1' => '<jdoc:include type="modules" name="bottom1" style="xtc" />',

		'bottom2' => '<jdoc:include type="modules" name="bottom2" style="xtc" />',

		'bottom3' => '<jdoc:include type="modules" name="bottom3" style="xtc" />',

		'bottom4' => '<jdoc:include type="modules" name="bottom4" style="xtc" />',

		'bottom5' => '<jdoc:include type="modules" name="bottom5" style="xtc" />',

		'bottom6' => '<jdoc:include type="modules" name="bottom6" style="xtc" />'

	);



	$customWidths = '';

        $customSpans = '';

	$columnClass = '';

	$columnPadding = '';

	$debug = 0;

	$bottom1_6 = xtcBootstrapGrid($columnArray,$order,$customSpans,$columnClass,$debug);

	

	

	if ($bottom1_6) {

        echo '<div id="region9wrap" class="xtc-bodygutter">';

        echo '<div id="region9pad" class="xtc-wrapperpad">';

	echo '<div id="region9" class="row-fluid xtc-wrapper r9spacer">';

        

	echo $bottom1_6;

        echo '</div>';

	echo '</div>';

        echo '</div>';

	}

?>