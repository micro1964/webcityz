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

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldHeader extends JFormField {

	protected	$type = 'Header';

	protected function getInput()	{

		$html = '';
//		$html  .= "</td></tr></table>";
		$html .= '<span class="xtcheader">'.$this->element['text'].'</span>';
//		$html .= '<table width="100%" class="paramlist admintable" cellspacing="1">';
		return $html;
	}
}