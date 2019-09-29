<?php
/*
Mozaik template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

if ($this->countModules( 'inset' ) ||
$this->countModules( 'topleft1' ) || 
$this->countModules( 'topleft2' ) || 
$this->countModules( 'topright1' ) || 
$this->countModules( 'topright2' ) ) {
?>
	<div id="region1wrap" class="xtc-bodygutter">
		<div id="region1pad" class="xtc-wrapperpad">
			<div id="region1" class="row-fluid xtc-wrapper r1spacer cfade">
  			<jdoc:include type="modules" name="inset" style="xtc"/>
				<div id="region1b" class="row-fluid ">
					<div class="pull-left"><jdoc:include type="modules" name="topleft2" style="xtc"/></div>
					<div class="pull-left"><jdoc:include type="modules" name="topleft1" style="xtc"/></div>
					<div class="pull-right"><jdoc:include type="modules" name="topright2" style="xtc"/></div>
					<div class="pull-right"><jdoc:include type="modules" name="topright1" style="xtc"/></div>
  			</div>
		  </div>
          
			<div id="r1separator"></div>
		</div>
	</div>
   
	
	
<?php } ?>