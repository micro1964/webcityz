<?php
/*
  JoomlaXTC Image Gallery Plugin

  version 1.1.3
  
  Copyright (C) 2012-2015 Monev Software LLC.  All Rights Reserved.
  
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
  
  THIS LICENSE MIGHT NOT APPLY TO OTHER FILES CONTAINED IN THE SAME PACKAGE.
  
  See COPYRIGHT.txt for more information.
  See LICENSE.txt for more information.
  
  Monev Software LLC
  www.joomlaxtc.com
*/

defined('_JEXEC') or die;

jimport('joomla.event.plugin');
jimport('joomla.filesystem.path');
jimport('joomla.filesystem.folder');

class plgContentjxtcimagegallery extends JPlugin {

	function onContentPrepare( $context, &$article, &$params, $limitstart ) {	// for Joomla content
      
    $tag = $this->params->get('tag','gallery');

		$regexp = "#{".$tag."}(.*?){/".$tag."}#s";
		$match = preg_match_all($regexp,$article->text,$hits);
		if (!$match) return;
		
		$this->setupHead();

		for ($i=0; $i < $match; $i++) {
			$tag = $hits[0][$i];
			$tagparam = $hits[1][$i];
			
			$tagparams = explode(':',$tagparam);
			$path = JPath::clean($tagparams[0],'/');
			
			if (is_dir(JPATH_ROOT.'/images/'.$path)) {	// Image Folder
				$html = $this->folder('images/'.$path,$tagparams);
			}
			elseif (is_file(JPATH_ROOT.'/images/'.$path)) {	// Image file
				$html = $this->file('images/'.$path,$tagparams);
			}
			elseif (is_dir(JPATH_ROOT.'/media/'.$path)) {	// Media Folder
				$html = $this->folder('media/'.$path,$tagparams);
			}
			elseif (is_file(JPATH_ROOT.'/media/'.$path)) {	// Media file
				$html = $this->file('media/'.$path,$tagparams);
			}
			elseif (is_dir(JPATH_ROOT.'/media/k2/galleries/'.$path)) {	// K2 Gallery
				$html = $this->folder('media/k2/galleries/'.$path,$tagparams);
			}
			else {
				$html = '';
			}

			$article->text = str_replace($tag,$html,$article->text);
		}
	}

	// Load JS & CSS
	function setupHead() {
		if (defined('IMAGEGALLERYPLG')) { return; }
		define('IMAGEGALLERYPLG',1);
		$boole=array('false','true');
		$script = 'jQuery(document).ready(function($){ 
			jQuery.noConflict();
			jQuery("a[class=imagegalleryplg]").fancybox({
				"padding":'.$this->params->get('padding',10).',
				"margin":'.$this->params->get('margin',20).',
				"opacity":'.$boole[$this->params->get('opacity',0)].',
				"cyclic":'.$boole[$this->params->get('cyclic',0)].',
				"autoScale":'.$boole[$this->params->get('autoScale',1)].',
				"centerOnScroll":'.$boole[$this->params->get('centerOnScroll',1)].',
				"hideOnOverlayClick":'.$boole[$this->params->get('hideOnOverlayClick',1)].',
				"hideOnContentClick":'.$boole[$this->params->get('hideOnContentClick',0)].',
				"overlayShow":'.$boole[$this->params->get('overlayShow',1)].',
				"overlayOpacity":'.$this->params->get('overlayOpacity','0.3').',
				"overlayColor":"#'.$this->params->get('overlayColor','666666').'",
				"titleShow":'.$boole[$this->params->get('titleShow',1)].',
				"titlePosition":"'.$this->params->get('titlePosition','outside').'",
				"transitionIn":"'.$this->params->get('transitionIn','elastic').'",
				"transitionOut":"'.$this->params->get('transitionOut','elastic').'",
				"speedIn":'.$this->params->get('speedIn',300).',
				"speedOut":'.$this->params->get('speedOut',300).',
				"changeSpeed":'.$this->params->get('changeSpeed',300).',
				"changeFade":'.$this->params->get('changeFade',300).',
				"showCloseButton":'.$boole[$this->params->get('showCloseButton',1)].',
				"showNavArrows":'.$boole[$this->params->get('showNavArrows',1)].'
			});
		});';

		$doc = JFactory::getDocument();

		if ($this->params->get('mootools',1) == 1) { JHtml::_('behavior.framework', true); }
		switch ($this->params->get('jquery',1)) {
			case 1: $doc->addScript("//code.jquery.com/jquery-latest.min.js"); break;
			case 2: JHtml::_('jquery.framework'); break;
		}
		if ($this->params->get('mousewheel',0) == 1) { $doc->addScript(JURI::root()."plugins/content/jxtcimagegallery/fancybox/jquery.mousewheel-3.0.4.pack.js"); }
		if ($this->params->get('css',1) == 1) { $doc->addStyleSheet(JURI::root()."plugins/content/jxtcimagegallery/fancybox/jquery.fancybox-1.3.4.css"); }

		$doc->addScript(JURI::root()."plugins/content/jxtcimagegallery/fancybox/jquery.fancybox-1.3.4.pack.js");
		$doc->addScriptDeclaration($script);

	}
	
	// Folder
	function folder($path,&$tagparams) {

		// Get parameters
    $thumbw = (isset($tagparams[1]) && $tagparams[1]) ? $tagparams[1] : $this->params->get('thumbw',120);
    $thumbh = (isset($tagparams[2]) && $tagparams[2]) ? $tagparams[2] : $this->params->get('thumbh',90);
    $single = (isset($tagparams[3]) && $tagparams[3]) ? $tagparams[3] : $this->params->get('single',1);
    $title = $this->params->get('title',1);
    $description = $this->params->get('description',1);
    $numbers = $this->params->get('numbers',0);
		$images = array();
		$jxtc = uniqid('jxtc');
    $zoom = $this->params->get('zoom',0);
    $forcepng = $this->params->get('forcepng',1);
    $thumbbkg = $this->params->get('thumbbkg','FFFFFF');
		$html = '';
		$cnt = 1;

//		$easyImgHelperFile = JPATH_ROOT.'/administrator/components/com_jxtceasyimage/support/helper.php';
//
//		if (JFile::exists($easyImgHelperFile) && substr($path,0,7) == 'images/') {
//			require_once $easyImgHelperFile;
//			$images = ezimgHelper::getImages(substr($path,7));
//		}
//		else {
			$this->loadLabels($path);
			$files = JFolder::files(JPATH_ROOT.'/'.$path,'.gif|.png|.jpg|.jpeg|.GIF|.PNG|.JPG|.JPEG',false,false);
			sort($files);
	  	foreach ($files as $file) {
				$image = $this->getImageInfo($path.'/'.$file);
	    	if ($image) { $images[] = $image; }
	    }
//	  }

		if (empty($images)) { return; }

		$total = count($images);
			
  	foreach ($images as $image) {

			if (!$image->title) { $image->title = isset($this->labels[$image->filename]) ? $this->labels[$image->filename]['title'] : ''; }
			if (!$image->description) { $image->description = isset($this->labels[$image->filename]) ? $this->labels[$image->filename]['description'] : ''; }
			$image->url = Juri::root().$image->folder.'/'.$image->filename;
   		$image->thumbUrl = $this->getCacheUrl('imagegallery',$image,$thumbw,$thumbh,$zoom,$forcepng,$thumbbkg);

			$titleString = '';
			if ($title == 2) { $image->title = $image->filename; }
			if ($title && $image->title) { $titleString .= '<b>'.$image->title.'</b>'; }
			if ($description && $image->description) {
				$titleString .= empty($titleString) 
				 ? $image->description
				 : "<br/>".$image->description;
			}
			if ($numbers) {
				$titleString .= empty($titleString)
					? $cnt.'/'.$total
					: '<span style="float:right">'.$cnt.'/'.$total.'</span>';
			}
			
			if ($cnt == 1) {
				$html .= '<a rel="'.$jxtc.'_group" class="imagegalleryplg" href="'.$image->url.'" title="'.htmlentities($titleString).'"><img alt="" title=" " src="'.$image->thumbUrl.'" /></a>';
			}
			else {
				$html .= $single
					? '<a rel="'.$jxtc.'_group" class="imagegalleryplg" href="'.$image->url.'" title="'.htmlentities($titleString).'" style="display:none"></a>'
					: '<a rel="'.$jxtc.'_group" class="imagegalleryplg" href="'.$image->url.'" title="'.htmlentities($titleString).'" ><img alt="" title=" " src="'.$image->thumbUrl.'" /></a>';
			}
			$cnt++;
  	}

		return $html;
	}
	
	// Do single file
	function file($path,&$tagparams) {
		
    $thumbw = (isset($tagparams[1]) && $tagparams[1]) ? $tagparams[1] : $this->params->get('thumbw',120);
    $thumbh = (isset($tagparams[2]) && $tagparams[2]) ? $tagparams[2] : $this->params->get('thumbh',90);
    $single = (isset($tagparams[3]) && $tagparams[3]) ? $tagparams[3] : $this->params->get('single',1);
    $zoom = $this->params->get('zoom',0);
    $forcepng = $this->params->get('forcepng',1);
    $thumbbkg = $this->params->get('thumbbkg','#FFFFFF');
    $title = $this->params->get('title',1);
    $description = $this->params->get('description',1);

		$image = $this->getImageInfo($path);

		if (!$image) { return; }

		$this->loadLabels($image->folder);
		if ($image->title) { $image->title = isset($this->labels[$image->filename]) ? $this->labels[$image->filename]['title'] : ''; }
		if ($image->description) { $image->description = isset($this->labels[$image->filename]) ? $this->labels[$image->filename]['description'] : ''; }
		$image->url = Juri::root().$image->folder.'/'.$image->filename;
 		$image->thumbUrl = $this->getCacheUrl('imagegallery',$image,$thumbw,$thumbh,$zoom,$forcepng,$thumbbkg);

		$titleString = '';
		if ($title == 2) { $image->title = $image->filename; }
		if ($title && $image->title) { $titleString .= '<b>'.$image->title.'</b>'; }
		if ($description && $image->description) {
			$titleString .= empty($titleString) 
			 ? $image->description
			 : "<br/>".$image->description;
		}
		
		$html = '<a class="imagegalleryplg" href="'.$image->url.'" title="'.htmlentities($titleString).'"><img alt="" title=" " src="'.$image->thumbUrl.'" /></a>';

		return $html;
	}
	
	function getCacheUrl($folder='thumbnails',$image=null,$thumbw=0,$thumbh=0,$zoom=0,$forcepng=1,$thumbbkg='FFFFFF') {

		if (!$image || !$thumbw || !$thumbh) return false;
		
		// Check for existing cache file

		$cacheFolder = JPATH_CACHE.'/'.$folder;
		if (!JFolder::exists($cacheFolder)) { JFolder::create($cacheFolder); }

		if ($thumbbkg) { $red = hexdec(substr($thumbbkg,0,2)); $green = hexdec(substr($thumbbkg,2,2)); $blue = hexdec(substr($thumbbkg,4,2)); } 
		else { $red = $blue = $green = 255; }

		$cacheFile = $folder.'/'.md5($zoom.$thumbbkg.$image->folder.$image->filename).'_'.$thumbw.'x'.$thumbh;

		if ($forcepng) { $cacheFile .='.png'; }
		else {
			switch($image->type) {
				case IMAGETYPE_GIF : $cacheFile .= '.gif'; break;
				case IMAGETYPE_JPEG: $cacheFile .= '.jpg'; break;
				case IMAGETYPE_PNG : $cacheFile .= '.png'; break;
				case IMAGETYPE_BMP : $cacheFile .= '.bmp'; break;
			}
		}

		// Create cache  file if needed

		if (!JFile::exists(JPATH_CACHE.'/'.$cacheFile)) {
			switch($image->type) {
				case IMAGETYPE_GIF : $orig_img = @imagecreatefromgif(JPATH_ROOT.'/'.$image->folder.'/'.$image->filename); break;
				case IMAGETYPE_JPEG: $orig_img = @imagecreatefromjpeg(JPATH_ROOT.'/'.$image->folder.'/'.$image->filename); break;
				case IMAGETYPE_PNG : $orig_img = @imagecreatefrompng(JPATH_ROOT.'/'.$image->folder.'/'.$image->filename); break;
				case IMAGETYPE_BMP : $orig_img = @imagecreatefromwbmp(JPATH_ROOT.'/'.$image->folder.'/'.$image->filename); break;
			}
		
			if (empty($orig_img)) { return false; }

			// Resample
		
		  $new_img = imagecreatetruecolor($thumbw,$thumbh);
			imagesavealpha($new_img,true);
			imagefill( $new_img, 0,0, imagecolorallocatealpha($new_img, $red, $green, $blue, 127) );

			switch ($zoom) {
				case 0:	// Scale to fit
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
				break;
				case 1:	// Zoom to fit
					$dst_x = 0;
					$dst_y = 0;
	
					$ratio = max($thumbw/$image->width, $thumbh/$image->height);
					$src_x = ($image->width - $thumbw / $ratio) / 2;
					$src_y = ($image->height - $thumbh / $ratio) / 2;
	
					$dst_w = $thumbw;
					$dst_h = $thumbh;
	
					$src_w = $image->width - $src_x*2;
					$src_h = $image->height - $src_y*2;
				break;
				case 2:	// Zoom to top
					$dst_x = 0;
					$dst_y = 0;
					$dst_w = $thumbw;
					$dst_h = $thumbh;
					$src_y = 0;
	
					if ($image->width > $image->height) { // wide
						$ratio = $thumbh/$image->height;
						$src_x = ($image->width - $thumbw / $ratio) / 2;
	
						$src_w = $image->width - $src_x*2;
						$src_h = $image->height - $src_y*2;
					}
					else {	// tall
						$ratio = $image->width / $thumbw;
						$src_x = 0;

						$src_w = $image->width;
						$src_h = $thumbh * $ratio;
					}
				break;
			}
			ImageCopyResampled($new_img,$orig_img, $dst_x,$dst_y, $src_x,$src_y, $dst_w,$dst_h, $src_w,$src_h);

			// Save

			if ($forcepng) {
				$orig_img = imagepng($new_img,JPATH_CACHE.'/'.$cacheFile);
			}
			else {
				switch($image->type) {
					case IMAGETYPE_GIF : $orig_img = imagegif($new_img,JPATH_CACHE.'/'.$cacheFile); break;
					case IMAGETYPE_JPEG: $orig_img = imagejpeg($new_img,JPATH_CACHE.'/'.$cacheFile); break;
					case IMAGETYPE_PNG : $orig_img = imagepng($new_img,JPATH_CACHE.'/'.$cacheFile); break;
				}
			}
		}

		return JURI::root().'cache/'.$cacheFile;
	}
	
	function getImageInfo($path) {
		$imageinfo = getimagesize(JPATH_ROOT.'/'.$path);
		if ($imageinfo[2] == IMAGETYPE_GIF || $imageinfo[2] == IMAGETYPE_JPEG || $imageinfo[2] == IMAGETYPE_PNG) {
			$imagestat = stat(JPATH_ROOT.'/'.$path);
			$pathinfo = pathinfo($path);
			$image = new stdClass();
			$image->folder = $pathinfo['dirname'];
			$image->filename = $pathinfo['basename'];
			$image->size = $imagestat['size'];
			$image->width = $imageinfo[0];
			$image->height = $imageinfo[1];
			$image->type = $imageinfo[2];

			$image->title = JFile::exists(JPATH_ROOT.'/'.$image->folder.'/'.$pathinfo['filename'].'_title.txt')
				? JFile::read(JPATH_ROOT.'/'.$image->folder.'/'.$pathinfo['filename'].'_title.txt')
				: '';

			$image->description = JFile::exists(JPATH_ROOT.'/'.$image->folder.'/'.$pathinfo['filename'].'_description.txt')
				? JFile::read(JPATH_ROOT.'/'.$image->folder.'/'.$pathinfo['filename'].'_description.txt')
				: '';

			return $image;
		}
		else return false;
	}

	function loadLabels($path) { // Get labels file (if any)
		$lang = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');

		if (JFile::exists(JPATH_ROOT.'/'.$path.'/'.$lang.'.labels.txt')) {
			$data=file(JPATH_ROOT.'/'.$path.'/'.$lang.'.labels.txt');
		}
		elseif (JFile::exists(JPATH_ROOT.'/'.$path.'/labels.txt')) {
			$data=file(JPATH_ROOT.'/'.$path.'/labels.txt');
		}
		else { return; }
		
		$this->labels = array();
		
		foreach ($data as $row) {
			list($filename,$title,$description) = explode('|',$row,3);
			$this->labels[$filename] = array('title' => $title, 'description' => $description);
		}
	}
}		
?>