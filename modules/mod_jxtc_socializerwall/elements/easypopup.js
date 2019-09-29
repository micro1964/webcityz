/*
	EasyPopup 1.2.0

	Copyright (C) 2011-2012  Monev Software LLC.
	
	All Rights Reserved.
	
	THIS PROGRAM IS NOT FREE SOFTWARE
	
	You shall not modify, copy, duplicate, reproduce, sell, license or
	sublicense the Software, or transfer or convey the Software or
	any right in the Software to anyone else without the prior
	written consent of Developer; provided that Licensee may make
	one copy of the Software for backup or archival purposes.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

var xtcEasyPopup = new Class({
  
  Implements: [Options, Events],
  
  options: {
    targets: null,
    closeOpened: true,
    centered: false,
    margin: 20,
    fade: false,
    duration: 200,
    dark: false
  },
  
  initialize: function(options){
    this.setOptions(options);
    this.targets = $$(this.options.targets);
    this.pops = new Array();
    this.createPop();
  },
  
  createPop: function(){
    this.targets.each(function(t, i){
      var pop = t.getAttribute('rel');
      if(pop){
        pop = $(pop);
        $(document.body).adopt(pop);
        this.pops.push(pop);
        
        pop.setStyle('z-index', 9999);
        pop.set('tween', {duration: this.options.duration});
        
        if( this.options.dark ){
          var dark = new Element('div', {'class': 'ep_dark', styles:{
            visibility:'visible',
            opacity: 0,
            width: $(document.body).getSize().x,
            height: $(document.body).getSize().y,
            'z-index': 9998
          }});
          $(document.body).adopt( dark );
          var dkfade = new Fx.Morph(dark, {duration:this.options.duration, link:'cancel', onComplete:function(){
            if( dark.getStyle('opacity') > 0) dark.setStyle('visibility', 'visible'); 
            else dark.setStyle('visibility', 'hidden');
          }});
        }
        
        if( this.options.centered ) this.centerIn(pop);
        else this.positionate(t, pop);
        
        t.addEvent('click', function(event){
          event.stop();
          if( this.options.centered ) this.centerIn(pop);
          else this.positionate(t, pop);
          
          if( this.options.fade ){
            if(this.options.closeOpened) $$(this.pops).setStyles({'visibility':'hidden', 'opacity':0});
            pop.fade('in');
            
            if( this.options.dark ){
              dark.setStyles({
                visibility: 'visible',
                width: $(document.body).getScrollSize().x,
                height: $(document.body).getScrollSize().y
              });
              dkfade.start({opacity:0.8});
            }
            
          }
          else{
            if(this.options.closeOpened) $$(this.pops).setStyles({'visibility':'hidden', 'opacity':0});
            pop.setStyles({'visibility':'visible', 'opacity':1});
            
            if( this.options.dark ){
              dark.setStyles({
                visibility: 'visible',
                opacity: 0.8,
                width: $(document.body).getScrollSize().x,
                height: $(document.body).getScrollSize().y
              });
            }
            
          }
        }.bind(this));
        var close = new Element('span', {'class':'ep_close','id':'jxtc_ep_close'});
        pop.adopt(close);
        close.addEvent('click', function(){
          if( this.options.fade ){
            pop.fade('out');
            if( this.options.dark ){
              dkfade.start({opacity:0});
            }
          }else{
            pop.setStyles({'visibility':'hidden', 'opacity':0});
            if( this.options.dark ){
              dkfade.set({opacity:0, visibility:'hidden'});
            }
          }
        }.bind(this));
      }
    }, this);
  },
  
  positionate: function(t, p){
    var zero = t.getCoordinates();
    var psize = p.getSize();
    var wwidth = window.getWidth();
    var wheight = window.getHeight();
    var scrollt = window.getScrollTop();
    
    /* Enough space above */
    if( (zero.top - (psize.y + this.options.margin)) > scrollt )
      p.setStyles({top: zero.top - (psize.y + this.options.margin) });
    else /* Try to put it under */
     p.setStyles({top: zero.top }); /* By the trigger side */
    
    var wheight = window.getHeight();
    scrollt = window.getScrollTop();
    
    if( p.getCoordinates().top + psize.y > document.body.getSize().y ) /* Goes under the body */
      p.setStyles({top: document.body.getSize().y - psize.y}); /* Set it on bottom edge (document) */
     
    var wheight = window.getHeight();
    scrollt = window.getScrollTop();
    
    if( p.getCoordinates().top + psize.y > scrollt + wheight ) /* Goes under the viewport */
      p.setStyles({top: scrollt + wheight - psize.y}); /* Set it on botton edge (viewport) */
    
    
    /* Horizontal alignment */
    if( (zero.left + zero.width + psize.x + this.options.margin) < wwidth ) /* There's enough space to put it to the right */  
      p.setStyles({left: zero.left + (zero.width + this.options.margin ) }); /* To the right */
    else{ /* Goes out of view port (right) */
      if( p.getCoordinates().top == zero.top ) /* Popup same top position than trigger */
        p.setStyles({left: wwidth - ( zero.left + psize.x + this.options.margin) }); /* Goes to the left of the trigger */
      else /* Popup above or under the trigger */
        p.setStyles({left: wwidth - (psize.x + this.options.margin) }); /* Goes to the left as much as needed to be 100% visible */
    } 
      
  },
  
  centerIn: function( el ){
    var wwidth = window.getWidth();
    var wheight = window.getHeight();
    var scrollt = window.getScrollTop();
    el.setStyles({
      'left': ((wwidth - el.getSize().x)/2),
      'top': ( scrollt + (wheight - el.getSize().y)/2 )
    });
  }
  
});