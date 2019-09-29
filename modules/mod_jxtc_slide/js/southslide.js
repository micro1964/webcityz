/*
	JoomlaXTC Southslide	

	version 1.2.1

	Copyright (C) 2009-2015  Monev Software LLC.
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

function southslide(id, blocks, o) {
	
  var souths = $$('#' + id + ' .southslide');
  var wScrollW = window.getScrollWidth();
  var wScrollH = window.getScrollHeight();
  
  var container = new Element('div',{
    'styles': {
    'display': 'none',
    'overflow': 'hidden',
    'position': 'absolute',
		'width': o.panelW + 'px',
		'height': o.panelH + 'px'
    },
    'class': 'slide-container'
  });
  container.inject(document.body);
  
  var inner = new Element('div',{
    'styles': {
    'position': 'relative',
    'width': o.panelW + 'px',
		'height': o.panelH + 'px'
    },
    'class': 'slide-inner-container'
  });
  inner.inject(container);
  
  var fader = new Element('div',{
    'styles': {
    'display': 'block',
    'position': 'absolute',
    'top': '0px',
    'left': '0px',
    'width': o.panelW + 'px',
    'height': o.panelH + 'px',
    'background-color': '#' + o.panelBg,
    'opacity': (o.panelOpacity/100)
    },
    'class': 'slide-bg'
  });
  fader.inject(inner);
  
  var div1 = new Element('div',{
    'styles':{
      'position': 'absolute',
      'width': o.boxLW + 'px',
      'height': o.boxLH + 'px',
      'left': '0px',
      'opacity': 0
    },
    'class': 'slide-leftbox'
  });
  
  var div2 = new Element('div',{
    'styles':{
      'position': 'absolute',
      'width': o.boxRW + 'px',
      'height': o.boxRH + 'px',
      'right': '0px',
      'opacity': 0
    },
    'class': 'slide-rightbox'
  });
  
  var close = new Element('div',{
    'styles':{
      'position': 'absolute',
			'color': '#FFF',
			'top': 'auto',
			'bottom': 0 + 'px',
			'right': 0 + 'px',
      'opacity': 1,
      'z-index': 9999,
      'cursor': 'pointer'
    },
  	'class': 'slide-close'
  });
  close.innerHTML = o.closeText;
  close.inject(inner);
  if(blocks.s1) {
  	div1.inject(inner);
  }
  if(blocks.s2) {
  	div2.inject(inner);
  }
  
  var fxc = new Fx.Morph(container, {duration: o.panelSpeedIn, transition: o.panelTranIn, fps: 80});
  var fxcl = new Fx.Morph(container, {duration: o.panelSpeedOut, transition: o.panelTranOut, fps: 80});
  var fx1 = new Fx.Morph(div1, {duration: o.boxLeftSpeed, transition: o.boxLeftTran, fps: 80});
  var fx2 = new Fx.Morph(div2, {duration: o.boxRightSpeed, transition: o.boxRightTran, fps: 80});
      
  souths.each(function(s, i){
    s.addEvent('click', function(e){
      container.setStyle('display', 'block');
      div1.empty();
      div2.empty();
      
      var wW = window.getWidth();
      var wH = window.getHeight();
      var wScrollT = window.getScrollTop();
      var wScrollL = window.getScrollLeft();
      
      if(blocks.s1) { div1.adopt(($(blocks.s1).getFirst()).clone()); }
      if(blocks.s2) { div2.adopt(($(blocks.s2).getFirst()).clone()); }
      
			var cent = (wScrollW - o.panelW)/2;
      
			/* Panel Direction */
			switch(o.panelDir) {
				case 'top':
					var fxcStylesI = {'top': wScrollT - wH + 'px', display: 'block', opacity: 1, left: cent + 'px'};
					var fxcStylesF = {'top': wScrollT + 'px'};
				break;
				case 'bottom':
					var fxcStylesI = {'top': wScrollT + wH + 'px', display: 'block', opacity: 1, left: cent + 'px'};
					var fxcStylesF = {'top': wScrollT + wH - o.panelH + 'px'};
				break;
			}
			container.setStyles(fxcStylesI);
			
			o.boxLW = div1.getSize().x;
      o.boxLH = div1.getSize().y;
      o.boxRW = div2.getSize().x;
      o.boxRH = div2.getSize().y;
			
			/* Left Box Slide Direction */
			switch(o.boxLeftDir) {
  			case 'top':
  				switch(o.boxLeftFx) {
    				case 's':
    					var fxlStylesI = {'top': -o.boxLH + 'px', opacity: 1};
      				var fxlStylesF = {'top': 0 + 'px', opacity: 1};
    					break;
    				case 'o':
    					var fxlStylesI = {'top': 0 + 'px', opacity: 0};
      				var fxlStylesF = {'top': 0 + 'px', opacity: 1};
    					break;
    				case 'so':
    					var fxlStylesI = {'top': -o.boxLH + 'px', opacity: 0};
      				var fxlStylesF = {'top': 0 + 'px', opacity: 1};
    					break;
  				}
  			break;
  			case 'bottom':
  				switch(o.boxLeftFx) {
  					case 's':
    					var fxlStylesI = {'bottom': -o.boxLH + 'px', opacity: 1};
      				var fxlStylesF = {'bottom': 0 + 'px', opacity: 1};
    					break;
  					case 'o':
    					var fxlStylesI = {'bottom': 0 + 'px', opacity: 0};
      				var fxlStylesF = {'bottom': 0 + 'px', opacity: 1};
    					break;
  					case 'so':
    					var fxlStylesI = {'bottom': -o.boxLH + 'px', opacity: 0};
      				var fxlStylesF = {'bottom': 0 + 'px', opacity: 1};
    					break;
  				}
  			break;
  			case 'left':
  				switch(o.boxLeftFx) {
    				case 's':
    					var fxlStylesI = {'left': -o.boxLW + 'px', opacity: 1};
      				var fxlStylesF = {'left': 0 + 'px', opacity: 1};
      				break;
    				case 'o':
    					var fxlStylesI = {'left': 0 + 'px', opacity: 0};
      				var fxlStylesF = {'left': 0 + 'px', opacity: 1};
      				break;
    				case 'so':
    					var fxlStylesI = {'left': -o.boxLW + 'px', opacity: 1};
      				var fxlStylesF = {'left': 0 + 'px', opacity: 1};
      				break;
  				}
  			break;
  			case 'right':
  				switch(o.boxLeftFx) {
    				case 's':
    					var fxlStylesI = {'left': o.panelW + 'px', opacity: 1};
      				var fxlStylesF = {'left': 0 + 'px', opacity: 1};
    					break;
    				case 'o':
    					var fxlStylesI = {'left': 0 + 'px', opacity: 0};
      				var fxlStylesF = {'left': 0 + 'px', opacity: 1};
    					break;
    				case 'so':
    					var fxlStylesI = {'left': o.panelW + 'px', opacity: 0};
      				var fxlStylesF = {'left': 0 + 'px', opacity: 1};
    					break;
  				}
  			break;
  		}
  		div1.setStyles(fxlStylesI);
  		
  		/* Right Box Slide Direction */
			switch(o.boxRightDir) {
  			case 'top':
  				switch(o.boxRightFx) {
  					case 's':
  						var fxrStylesI = {'top': -o.boxRH + 'px', opacity: 1};
  	  				var fxrStylesF = {'top': 0 + 'px', opacity: 1};
  						break;
  					case 'p':
  						var fxrStylesI = {'top': 0 + 'px', opacity: 0};
  	  				var fxrStylesF = {'top': 0 + 'px', opacity: 1};
  						break;
  					case 'so':
  						var fxrStylesI = {'top': -o.boxRH + 'px', opacity: 0};
  	  				var fxrStylesF = {'top': 0 + 'px', opacity: 1};
  						break;
  				}
  			break;
  			case 'bottom':
  				switch(o.boxRightFx) {
  					case 's':
  						var fxrStylesI = {'bottom': -o.boxRH + 'px', opacity: 1};
  	  				var fxrStylesF = {'bottom': 0 + 'px', opacity: 1};
  						break;
  					case 'o':
  						var fxrStylesI = {'bottom': 0 + 'px', opacity: 0};
  	  				var fxrStylesF = {'bottom': 0 + 'px', opacity: 1};
  						break;
  					case 'so':
  						var fxrStylesI = {'bottom': -o.boxRH + 'px', opacity: 0};
  	  				var fxrStylesF = {'bottom': 0 + 'px', opacity: 1};
  						break;
  				}
  			break;
  			case 'right':
  				switch(o.boxRightFx) {
    				case 's':
    					var fxrStylesI = {'right': -o.boxRW + 'px', opacity: 1};
      				var fxrStylesF = {'right': 0 + 'px', opacity: 1};
    					break;
    				case 'o':
    					var fxrStylesI = {'right': 0 + 'px', opacity: 0};
      				var fxrStylesF = {'right': 0 + 'px', opacity: 1};
    					break;
    				case 'so':
    					var fxrStylesI = {'right': -o.boxRW + 'px', opacity: 0};
      				var fxrStylesF = {'right': 0 + 'px', opacity: 1};
    					break;
  				}
  			break;
  			case 'left':
  				switch(o.boxRightFx) {
    				case 's':
    					var fxrStylesI = {'right': o.panelW + 'px', opacity: 1};
      				var fxrStylesF = {'right': 0 + 'px', opacity: 1};
    					break;
    				case 'o':
    					var fxrStylesI = {'right': 0 + 'px', opacity: 0};
      				var fxrStylesF = {'right': 0 + 'px', opacity: 1};
    					break;
    				case 'so':
    					var fxrStylesI = {'right': o.panelW + 'px', opacity: 0};
      				var fxrStylesF = {'right': 0 + 'px', opacity: 1};
    					break;
  				}
  			break;
  		}
  		div2.setStyles(fxrStylesI);
				
      /* Animate */
  		switch (o.boxesAnimOrder) {
  		case 'l':
  			fxc.start(fxcStylesF).chain(function(){
          fx1.start(fxlStylesF).chain(function(){
            fx2.start(fxrStylesF);
          });
        });
  			break;
  		case 'r':
  			fxc.start(fxcStylesF).chain(function(){
          fx2.start(fxrStylesF).chain(function(){
            fx1.start(fxlStylesF);
          });
        });
    		break;
  		case 'lr':
  			fxc.start(fxcStylesF).chain(function(){
          fx1.start(fxlStylesF);
          fx2.start(fxrStylesF);
        });
    		break;
  		}
   
  	});// Souths(s) addEvent    
  });// Souths listing
  
  /* Close FXs */
  var closeFX = {};
  switch (o.panelOutAnim) {
  	case 's':
  		closeFX = (o.panelDir == 'top') ? {'top': -o.boxLH + 'px'} : {'bottom': -o.boxLH + 'px'} ;
  	break;
  	case 'o':
  		closeFX = {opacity: 0};
  	break;
  	case 'so':
  		closeFX = (o.panelDir == 'top') ? {'top': -o.boxLH + 'px', opacity: 0} : {'bottom': -o.boxLH + 'px', opacity: 0};
  	break;
  }
  
  close.addEvent('click', function(){
    fxcl.start(closeFX).chain(function(){
      container.setStyle('display', 'none');
    });
  });
  


}/* End of the southslide function */