/*
	JoomlaXTC Reading List

	version 1.3.4
	
	Copyright (C) 2012-2016 Monev Software LLC.	All Rights Reserved.
	
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
	
	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.

	See COPYRIGHT.txt for more information.
	See LICENSE.txt for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/
function jxtcPost( element, posturl ) {
	new Request({
    url: posturl,
    method: 'post',
    onSuccess: function(resp){
			var data = eval('(' + resp + ')');
      element.innerHTML = data[1];
      
      if (resp[0] == 0) {
      	if (document.getElementById('readinglistinfo_noitems') != null) { document.getElementById('readinglistinfo_noitems').style.display='block';}
      	if (document.getElementById('readinglistinfo_items') != null) { document.getElementById('readinglistinfo_items').style.display='none';}
      }
      else {
      	if (document.getElementById('readinglistinfo_noitems') != null) { document.getElementById('readinglistinfo_noitems').style.display='none';}
      	if (document.getElementById('readinglistinfo_items') != null) { document.getElementById('readinglistinfo_items').style.display='block';}
      	if (document.getElementById('readinglistinfo_count') != null) { document.getElementById('readinglistinfo_count').innerHTML=data[0];}
      }
    }
	}).send();
}
