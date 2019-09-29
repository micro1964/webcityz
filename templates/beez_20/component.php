<?php
/*

XTC Template Framework 3.3.0

Copyright (c) 2010-2014 Monev Software LLC,  All Rights Reserved

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA

See COPYRIGHT.txt for more information.
See LICENSE.txt for more information.

www.joomlaxtc.com

*/

defined( '_JEXEC' ) or die;

require JPATH_THEMES.'/'.$this->template.'/XTC/XTC.php';
$templateParameters = xtcLoadParams();
$layout = $templateParameters->templateLayout;
$params = $templateParameters->group->$layout;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<?php echo xtcCSS($params->xtcstyle,$params->xtctypo); ?>
<jdoc:include type="head" />
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo $xtc->templateUrl; ?>css/ie7.css" /><![endif]-->
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="<?php echo $xtc->templateUrl; ?>css/ie8.css" /><![endif]-->
</head>
<body>
<jdoc:include type="message" />
<jdoc:include type="component" />
</body>
</html>