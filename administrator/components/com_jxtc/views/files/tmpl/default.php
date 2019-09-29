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

if ( empty( $this->files )) {
    $message = JText::_( 'No files found' );
}

// js & styling
$css = 'html,body {margin:0 !important; padding:0 !important}
#browser {height:425px;width:100%;overflow:auto;position:relative;margin:20px 0 20px 0}
.item { float: left; margin:5px 5px 6px 5px; text-align:center }
.item img {
	clear:both;
	margin-bottom:6px;
}
.item img:hover {
	filter: alpha(opacity=50);
	opacity: 0.5;
	cursor:pointer;
}
';
$script="function setFieldValue( file ) {
		/*document.getElementById('folderTitle').innerHTML = file;*/
		window.parent.document.getElementById('".$this->fld."').value = file;
		window.parent.SqueezeBox.close();
	}";
$doc = JFactory::getDocument();
$doc->addScriptDeclaration($script);
$doc->addStyleDeclaration($css);
?>
<form action="index.php" name="adminForm" id="adminForm" method="post" enctype="multipart/form-data">
	<fieldset>
		<div class="well" style="margin:0px">
			<h3 id="folderTitle" class="pull-left" style="margin:0px">
				<?php echo $this->path; ?>
			</h3>
			<div class="btn-toolbar pull-right" style="margin:0px">
				<button type="button" class="btn active">
					<i class="icon-grid-view-2"> </i> <?php echo JText::_('JXTC_GRIDVIEW'); ?>
				</button>
				<button type="button" class="btn" onclick="javascript:document.adminForm.layout.value='list';document.adminForm.submit();">
					<i class="icon-list-view"> </i> <?php echo JText::_('JXTC_LISTVIEW'); ?>
				</button>
			</div>
		</div>

		<div id="browser">
			<?php
			  if( count( $this->files ) > 0 ) {
			    foreach ($this->files as $file) {
						$this->assignRef('file',$file);
						echo $this->loadTemplate('item');
			    }
			  }
			?>
		</div>

		<div class="well" style="margin:0px">
			<?php echo JText::_('JXTC_UPLOADFILE') ?>
			<input type=file name="xtcFile">
			<button type="submit" class="btn btn-primary"><i class="icon-upload"> </i> <?php echo JText::_('JXTC_UPLOAD'); ?></button>
		</div>
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="option" value="com_jxtc" />
		<input type="hidden" name="view" value="files" />
		<input type="hidden" name="layout" value="default" />
		<input type="hidden" name="id" value="<?php echo $this->id; ?>" />
		<input type="hidden" name="f" value="<?php echo $this->f; ?>" />
		<input type="hidden" name="fld" value="<?php echo $this->fld; ?>" />
	</fieldset>
</form>
