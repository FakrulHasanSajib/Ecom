/* hider */
(function (window){
	
    var FWDEVPHider = function(screenToTest, screenToTest2, hideDelay){

    	'use strict';
    	
    	var _s = this;
    	var prototype = FWDEVPHider.prototype;
   
    	_s.screenToTest = screenToTest;
    	_s.screenToTest2 = screenToTest2;
    	_s.hideDelay = hideDelay;
    	_s.globalX = 0;
    	_s.globalY = 0;

       	_s.dispatchOnceShow_bl = true;

    	_s.isStopped_bl = true;
    	_s.isMbl = FWDEVPUtils.isMobile;
    	_s.hasPointerEvent_bl = FWDEVPUtils.hasPointerEvent;
    	
		_s.init = function(){};
	
		_s.start = function(){
			_s.currentTime = new Date().getTime();
			clearInterval(_s.checkIntervalId_int);
			_s.checkIntervalId_int = setInterval(_s.update, 100);
			_s.addMouseOrTouchCheck();
			_s.isStopped_bl = false;
		};
		
		_s.stop = function(){
			clearInterval(_s.checkIntervalId_int);
			_s.isStopped_bl = true;
			_s.removeMouseOrTouchCheck();
			_s.removeMouseOrTouchCheck2();
		};
		
		_s.addMouseOrTouchCheck = function(){	
			if(_s.hasInitialTestEvents_bl) return;
			_s.hasInitialTestEvents_bl = true;
			if(_s.isMbl){
				if(_s.hasPointerEvent_bl){
					_s.screenToTest.screen.addEventListener("pointerdown", _s.onMTU);
					_s.screenToTest.screen.addEventListener("MSPointerMove", _s.onMTU);
				}else{
					_s.screenToTest.screen.addEventListener("touchstart", _s.onMTU);
				}
			}else if(window.addEventListener){
				window.addEventListener("mousemove", _s.onMTU);
			}else if(document.attachEvent){
				document.attachEvent("onmousemove", _s.onMTU);
			}
		};
		
		_s.removeMouseOrTouchCheck = function(){	
			if(!_s.hasInitialTestEvents_bl) return;
			_s.hasInitialTestEvents_bl = false;
			if(_s.isMbl){
				if(_s.hasPointerEvent_bl){
					_s.screenToTest.screen.removeEventListener("pointerdown", _s.onMTU);
					_s.screenToTest.screen.removeEventListener("MSPointerMove", _s.onMTU);
				}else{
					_s.screenToTest.screen.removeEventListener("touchstart", _s.onMTU);
				}
			}else if(window.removeEventListener){
				window.removeEventListener("mousemove", _s.onMTU);
			}else if(document.detachEvent){
				document.detachEvent("onmousemove", _s.onMTU);
			}
		};
		
		_s.addMouseOrTouchCheck2 = function(){	
			if(_s.addSecondTestEvents_bl) return;
			_s.addSecondTestEvents_bl = true;
			if(_s.screenToTest.screen.addEventListener){
				_s.screenToTest.screen.addEventListener("mousemove", _s.onSecondTest);
			}else if(_s.screenToTest.screen.attachEvent){
				_s.screenToTest.screen.attachEvent("onmousemove", _s.onSecondTest);
			}
		};
		
		_s.removeMouseOrTouchCheck2 = function(){	
			if(!_s.addSecondTestEvents_bl) return;
			_s.addSecondTestEvents_bl = false;
			if(_s.screenToTest.screen.removeEventListener){
				_s.screenToTest.screen.removeEventListener("mousemove", _s.onSecondTest);
			}else if(_s.screenToTest.screen.detachEvent){
				_s.screenToTest.screen.detachEvent("onmousemove", _s.onSecondTest);
			}
		};
		
		_s.onSecondTest = function(){
			_s.removeMouseOrTouchCheck2();
			_s.addMouseOrTouchCheck();
		};
		
		_s.onMTU = function(e){
			var vc = FWDEVPUtils.getViewportMouseCoordinates(e);	
			
			if(_s.globalX != vc.screenX
			   && _s.globalY != vc.screenY){
				_s.currentTime = new Date().getTime();
			}
			
			_s.globalX = vc.screenX;
			_s.globalY = vc.screenY;
			
			if(!_s.isMbl){
				if(!FWDEVPUtils.hitTest(_s.screenToTest.screen, _s.globalX, _s.globalY)){
					_s.removeMouseOrTouchCheck();
					_s.addMouseOrTouchCheck2();
				}
			}
		};
	
		_s.update = function(e){
			if(new Date().getTime() > _s.currentTime + _s.hideDelay){
				if(_s.dispatchOnceShow_bl){	
					_s.dispatchOnceHide_bl = true;
					_s.dispatchOnceShow_bl = false;	
					_s.dispatchEvent(FWDEVPHider.HIDE);
					clearTimeout(_s.hideCompleteId_to);
					_s.hideCompleteId_to = setTimeout(function(){
						_s.dispatchEvent(FWDEVPHider.HIDE_COMPLETE);
					}, 1000);
				}
			}else{
				if(_s.dispatchOnceHide_bl){
					clearTimeout(_s.hideCompleteId_to);
					_s.dispatchOnceHide_bl = false;
					_s.dispatchOnceShow_bl = true;
					_s.dispatchEvent(FWDEVPHider.SHOW);
				}
			}
		};

		_s.reset = function(){
			clearTimeout(_s.hideCompleteId_to);
			_s.currentTime = new Date().getTime();
			_s.dispatchEvent(FWDEVPHider.SHOW);
		};
		
		
		/* destroy */
		_s.destroy = function(){
		
			_s.removeMouseOrTouchCheck();
			clearInterval(_s.checkIntervalId_int);
			prototype.destroy();
			prototype = null;
			_s = null;
			FWDEVPHider.prototype = null;
		};
		
		_s.init();
     };
     
	 FWDEVPHider.HIDE = "hide";
	 FWDEVPHider.SHOW = "show";
	 FWDEVPHider.HIDE_COMPLETE = "hideComplete";
	 
	 FWDEVPHider.setPrototype = function(){
		 FWDEVPHider.prototype = new FWDEVPEventDispatcher();
	 };
	 

	 window.FWDEVPHider = FWDEVPHider;
}(window));