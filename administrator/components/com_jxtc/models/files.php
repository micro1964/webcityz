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

// Import Joomla! libraries
jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

// Disable warnings because of open_basedir Warnings
ini_set( 'display_errors', 0 );

require_once( JPATH_ADMINISTRATOR.'/components/com_media/helpers/media.php' );

class xtcModelFiles extends JModelLegacy {
    
  function getFiles($path) {
    static $list;

    // Only process the list once per request
    if (is_array($list)) { return $list; }

		$path = JPath::clean($path,'/');
    $folder = JPath::check(JPATH_ROOT.'/'.$path);

    if(!is_readable( $folder )) { return false; }

    $files = array ();
    $folders = array ();
    $docs = array ();

		$extensions = array('jpg','png','gif','jpeg');

		// Get upload files if any

		include 'getuploads.php';

    // Iterate over the files if they exist
    foreach (JFolder::files($folder) as $file) {
    	$extension = strtolower(pathinfo($folder.'/'.$file,PATHINFO_EXTENSION));
    	if (!in_array($extension,$extensions)) { continue; }
      $tmp = new stdClass();
      $tmp->name = $file;
      $tmp->path = $path.'/'.$file;
      $tmp->url = JURI::root().$path.'/'.$file;
      $tmp->size = filesize(JPATH_ROOT.'/'.$tmp->path);
			$tmp->date = date("Y-m-d H:i:s", filemtime(JPATH_ROOT.'/'.$tmp->path));
      $tmp->isimage = true;
      $info = getimagesize(JPATH_ROOT.'/'.$tmp->path);
      $tmp->width = $info[0];
      $tmp->height = $info[1];
      $tmp->type = $info[2];
      $tmp->mime = $info['mime'];

      $tmp->thumbnailUrl = $this->getThumbnailUrl($tmp,60,60);

      $files[] = $tmp;
    }

   return $files;
	}

	public static function getThumbnailUrl($image=null,$thumbw=0,$thumbh=0,$zoom=0) {

		if (!$image || !$thumbw || !$thumbh) return false;

		$imagePath = JPATH_ROOT.'/'.$image->path;
		$cacheFile = 'xtc/'.md5($zoom.$imagePath).'_'.$thumbw.'x'.$thumbh.'.png';
		
		if (JFile::exists($imagePath) && !JFile::exists(JPATH_CACHE.'/'.$cacheFile)) {

			if (!JFolder::exists(JPATH_CACHE.'/xtc')) { JFolder::create(JPATH_CACHE.'/xtc'); }

			switch($image->type) {
				case IMAGETYPE_GIF : $orig_img = @imagecreatefromgif($imagePath); break;
				case IMAGETYPE_JPEG: $orig_img = @imagecreatefromjpeg($imagePath); break;
				case IMAGETYPE_PNG : $orig_img = @imagecreatefrompng($imagePath); break;
				case IMAGETYPE_BMP : $orig_img = @imagecreatefromwbmp($imagePath); break;
			}
		
			if (empty($orig_img)) { $orig_img = imagecreatefrompng(JPATH_ADMINISTRATOR.'/components/com_jxtc/support/images/broken.png'); }

			// Resample
		
		  $new_img = imagecreatetruecolor($thumbw,$thumbh);
			imagesavealpha($new_img,true);
			imagefill( $new_img, 0,0, imagecolorallocatealpha($new_img, 255, 255, 255, 127) );

			if ($zoom) {
				$dst_x = 0;
				$dst_y = 0;

				$ratio = max($thumbw/$image->width, $thumbh/$image->height);
				$src_x = ($image->width - $thumbw / $ratio) / 2;
				$src_y = ($image->height - $thumbh / $ratio) / 2;

				$dst_w = $thumbw;
				$dst_h = $thumbh;

				$src_w = $image->width - $src_x*2;
				$src_h = $image->height - $src_y*2;
			}
			else {
				$maxX = $thumbw;
				$maxY = $thumbh;
				$dst_w = $maxX;
				$dst_h = $maxY;
				if (($image->width*$maxY)<($image->height*$maxX)) {
					$dst_w = $dst_h * ($image->width/$image->height);
					$dst_x = ($maxX - $dst_w)/2;
					$dst_y = 0;
				}
				else {
					$dst_h = $dst_w / ($image->width/$image->height);
					$dst_x = 0;
					$dst_y = ($maxY - $dst_h)/2;
				}
			
				$src_x = 0;
				$src_y = 0;
				
				$src_w = $image->width;
				$src_h = $image->height;
			}
			ImageCopyResampled($new_img,$orig_img, $dst_x,$dst_y, $src_x,$src_y, $dst_w,$dst_h, $src_w,$src_h);
			imagepng($new_img,JPATH_CACHE.'/'.$cacheFile);
		}

		return JURI::base().'cache/'.$cacheFile;
	}
		
}
?>