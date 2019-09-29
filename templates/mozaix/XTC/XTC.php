<?php
//
// START OF USER CUSTOMIZABLE VARIABLES
//
// This variables control the default operation of the XTC framework core and can be
// overriden with parameters in the XML_config.xml
//
// if a variable is not needed just comment it
// (everything is case-sensitive, DRIVE CAREFULLY)
//

// Public parameters
// Any parameter from the XML files can be made public by adding it to this array.
// Public parameters can have values set by the URL (eg: index.php?parameter1=value1&parameter2=value2)
$publicParams = array( 'xtcstyle', 'regioncfg', 'columncfg' ); // Allow overrides to some parameters in templateDetails.xml and config.xml

// Show Components
// Only these components will be shown in the site frontpage ( HANDLE WITH CARE! )
// $showComponents = array( 'com_community', 'com_user');

// Custom presets
// Depeding on URL var values, a different presets.ini file can be used
// $customPresets = array( 'option=com_newsfeeds'=>'weblinks' ); // use weblinks.ini for weblinks component

// END OF USER CUSTOMIZABLE VARIABLES


// Create XTC Framework object
require 'XTC_library.php';