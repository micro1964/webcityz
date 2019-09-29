<?php
/*
	JoomlaXTC XSpacer
	
	Version 1.1.0

	Copyright (C) 2011  Monev Software LLC.	All Rights Reserved.

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
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;

jimport('joomla.form.formfield');

class JFormFieldXspacer extends JFormField {

	protected	$_name = 'Xspacer';

	protected function getInput()	{
	
		$html = '<span class="label label-info" style="min-width:210px;display:inline-block;padding:5px 5px 5px 10px;';
		if (!empty($this->element['css'])) $html .= $this->element['css'];
		$html .= ';">'.$this->element['title'].'</span>';

		return $html;
	}
}
?>