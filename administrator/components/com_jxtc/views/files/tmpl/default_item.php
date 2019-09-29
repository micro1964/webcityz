<?php
/***********************************************************************************
************************************************************************************
***                                                                              ***
***   XTC Template Framework helper   3.4.0                                      ***
***                                                                              ***
***   Copyright (c) 2010-2017                                                    ***
***   Monev Software LLC,  All Rights Reserved                                   ***
***                                                                              ***
***   This program is free software; you can redistribute it and/or modify       ***
***   it under the terms of the GNU General Public License as published by       ***
***   the Free Software Foundation; either version 2 of the License, or          ***
***   (at your option) any later version.                                        ***
***                                                                              ***
***   This program is distributed in the hope that it will be useful,            ***
***   but WITHOUT ANY WARRANTY; without even the implied warranty of             ***
***   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              ***
***   GNU General Public License for more details.                               ***
***                                                                              ***
***   You should have received a copy of the GNU General Public License          ***
***   along with this program; if not, write to the Free Software                ***
***   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA   ***
***                                                                              ***
***   See COPYRIGHT.txt for more information.                                    ***
***   See LICENSE.txt for more information.                                      ***
***                                                                              ***
***   www.joomlaxtc.com                                                          ***
***                                                                              ***
************************************************************************************
***********************************************************************************/

defined('_JEXEC') or die;

$max_chars = 12;
$short_name = $this->file->name;
if( strlen( $short_name ) > $max_chars ) {
    $short_name = substr( $short_name, 0, $max_chars ) . '...';
}
$title = $this->file->name." (".$this->file->realwidth."x".$this->file->realheight.") ".number_format($this->file->size)." bytes";

$a = $this->file->size; $unim = array("B","KB","MB","GB","TB","PB"); $c = 0;
while ($a>=1024) { $c++; $a = $a/1024; }
$size = number_format($a,($c>1 ? 1 : 0),".",".")." ".$unim[$c];
?>
<div class="item">
	<img src="<?php echo $this->file->thumbnailUrl; ?>" class="hasTip img-polaroid" title="<b><?php echo $this->file->name."</b><br>".$this->file->width.'x'.$this->file->height." pixels<br>".$size; ?>" alt="<?php echo $this->file->name; ?>" onclick="javascript:setFieldValue('<?php echo $this->file->name; ?>')"/>
	<br>
	<button class="hasTip btn btn-mini" type="button" title="View Image" onclick="window.open('<?php echo $this->file->url; ?>','_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=<?php echo $this->file->width; ?>,height=<?php echo $this->file->height; ?>');"><i class="icon-picture"> </i></button>
</div>
