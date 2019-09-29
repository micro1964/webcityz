/*

XTC Template Framework 3.2.1

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

function xtcMenu(container, menuClass, sp, del, a, tr, afps, cnt, ali, mobile){
  var topmenu;
  if(container === null) { topmenu = document; }
  else { topmenu = document.getElementById(container); }
  
  if( tr == null ) { tr = new Fx.Transition(Fx.Transitions.Quint.easeInOut); }
  if( afps == null ) {afps = 80; }
  if( cnt == null ) { cnt = false; }
  if( del == null ) { del = 0; }
  if( mobile == null ) { mobile = (!Browser.Platform.mac && !Browser.Platform.win && !Browser.Platform.linux); }
  
  if(topmenu) {
    var menus = topmenu.getElements('ul.' + menuClass);
    
    menus.each(function(menu){
      var menu_lis = menu.getElements('li');
      var uMain = menu.getCoordinates();
          
      menu_lis.each(function(l,i){
        var u = l.getElement('ul');
        if(u) {
          /* u.setStyles({'visibility':'visible'}); */
          if(menu.hasClass('suckerfish')) { u.addClass('suckerfish'); }
          if(menu.hasClass('dualfish')) { u.addClass('dualfish'); }
          if(menu.hasClass('dropline')) { u.addClass('dropline'); }
          
          var uLevel = ((u.getParent()).getParent()).hasClass(menuClass);
          var fx = new Fx.Morph(u, { duration: sp, link:'cancel', transition: tr, fps: afps } );
          var uDim = u.getCoordinates([menu]);
          var uPaddingTop = ( u.getStyle('padding-top') ) ? u.getStyle('padding-top').toInt() : 0;
          var uPaddingBottom = ( u.getStyle('padding-bottom') ) ? u.getStyle('padding-bottom').toInt() : 0;
          var uh = uDim.height - (uPaddingTop + uPaddingBottom);
          var mAnimIn;
          var mAnimOut;
                    
          /* IF 1st level dropdown */
          if(uLevel) {
            if (window.ie){ uDim.left = u.getBoundingClientRect().left; }
            //if( (uMain.left + uMain.width) < (uDim.left + uDim.width) ) { u.setStyles({'right': 0 + 'px'}); }
            if(cnt) {
              if( l.getSize().x <= uDim.width ) {
                u.setStyles({'margin-left': ((l.getSize().x - uDim.width)/2) + 'px'});
              } 
              else {
                u.setStyles({'margin-left': ((uDim.width - l.getSize().x)/2) + 'px'});
              }
              uDim = u.getCoordinates([menu]);
            }
            if( ali && (uMain.left + uMain.width) < (uDim.left + uDim.width) ) { u.setStyles({'right': 0 + 'px', 'margin-left': 0 + 'px'}); }
            if( ali && (uMain.left) > (uDim.left) ) { u.setStyles({'left': 0 + 'px', 'margin-left': 0 + 'px'}); }


          /* IF 2nd or deeper dropdown */
          }else {
            if (window.ie){ uDim.left = u.getBoundingClientRect().left; }
            if(ali) {
              if( (uMain.left + uMain.width) < (uDim.left + uDim.width) ) { u.setStyles({'left': -uDim.width + 'px'}); }
            }
          }
          
          /* u.addClass('xtcHide'); */
                    
          switch(a) {
            case 'h':
              u.setStyles({'height':'0px'});
              mAnimIn = {'height': uh + 'px'};
              mAnimOut = {'height': 0 + 'px'};
            break;
            
            case 'f':
              u.setStyles({'opacity':0});
              mAnimIn = {'opacity': 1};
              mAnimOut = {'opacity': 0};
            break;
            
            case 'hf':
              u.setStyles({'height':'0px', 'opacity':0});
              mAnimIn = {'height': uh + 'px', 'opacity': 1};
              mAnimOut = {'height': 0 + 'px', 'opacity': 0};
            break;
          }
          
          var timer;
          l.addEvent('mouseenter', function(){
            l.addClass('xtcHover');
            if(window.ie7) { u.setStyle('display', 'block'); }
            clearTimeout(timer);
            fx.cancel();
            fx.start(mAnimIn);
          });
          
          var count=0;
          if ( mobile )
          {
              l.addEvent('click', function(){
               
                if ( l.hasClass('xtcHover') )
                {
                  l.removeClass('xtcHover');
                }
                 l.addClass('xtcHover');
                 u.setStyle('z-index',-10);
                if(window.ie7) { u.setStyle('display', 'block'); }
                fx.cancel();
                fx.start(mAnimIn);
                count++;
             
                if ( count > 1 && l.hasClass('xtcHover') )
                {
                  count = 0;
                  return true;  
                } else {
                   clearTimeout(timer);
                   return false;
                }

              });
          }

          l.addEvent('mouseleave', function(){
            timer = (function(){
              fx.start(mAnimOut).chain(function(){
                l.removeClass('xtcHover');
                if(window.ie7) { u.setStyle('display', 'none'); }
              })
            }).delay(del);  
          });
          
        }/* If there is submenu */
        
      });
      
    });/* if(menu) END */
  }/* if(topmenu) END */
}