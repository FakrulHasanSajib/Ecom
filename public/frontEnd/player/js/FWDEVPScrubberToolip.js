/**
 * Easy Video Player PACKAGED v9.1
 * Scrubber tooltip.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (window){

	var FWDEVPScrubberToolip = function(
			buttonRef_do,
			bkColor,
			fontColor_str,
			toolTipLabel_str,
			toolTipsButtonsHideDelay
		){

		'use strict';
		
		var _s = this;
		
		_s.buttonRef_do = buttonRef_do;
		_s.bkColor = bkColor;
		_s.fontColor_str = fontColor_str;
		_s.toolTipLabel_str = toolTipLabel_str;
		_s.toolTipsButtonsHideDelay = toolTipsButtonsHideDelay * 1000;
		_s.pointerWidth = 7;
		_s.pointerHeight = 4;		
		_s.isMobile_bl = FWDEVPUtils.isMobile;
		_s.isShowed_bl = true;
	

		//##########################################//
		/* initialize */
		//##########################################//
		_s.init = function(){
			_s.setOverflow("visible");
			_s.screen.className = 'EVP-tooltip-bk';
			_s.setupMainContainers();
			_s.setLabel(toolTipLabel_str);
			_s.hide();
			_s.setVisible(false);
			_s.style().backgroundColor = _s.bkColor;
			_s.style().zIndex = 9999999999999;
			_s.style().pointerEvents = "none";

		};
		

		//##########################################//
		/* setup main containers */
		//##########################################//
		_s.setupMainContainers = function(){
			_s.pointerHolder_do = new FWDEVPDO("div");
			_s.pointerHolder_do.setOverflow('visible');
			_s.addChild(_s.pointerHolder_do);

			_s.text_do = new FWDEVPDO("div");
			_s.text_do.screen.className = 'EVP-tooltip-text';
			_s.text_do.hasT3D = false;
			_s.text_do.hasT2D = false;
			_s.text_do.setBackfaceVisibility();
			_s.text_do.setDisplay("inline-block");
			_s.text_do.style().fontFamily = "Arial";
			_s.text_do.style().fontSize= "12px";
			_s.text_do.style().color = _s.fontColor_str;

			_s.text_do.style().whiteSpace= "nowrap";
			_s.text_do.style().fontSmoothing = "antialiased";
			_s.text_do.style().webkitFontSmoothing = "antialiased";
			_s.text_do.style().textRendering = "optimizeLegibility";
			_s.text_do.style().padding = "6px";
			_s.text_do.style().paddingTop = "4px";
			_s.text_do.style().paddingBottom = "4px";
		
			_s.addChild(_s.text_do);
			
			 _s.pointer_do = new FWDEVPDO("div");
			_s.pointer_do.screen.className = 'EVP-scrubber-pointer';
			_s.pointer_do.style().border = "4px solid transparent"
			_s.pointer_do.style().borderTopColor = _s.bkColor ;
			_s.pointerHolder_do.addChild(_s.pointer_do);
		}
		

		//##########################################//
		/* set label */
		//##########################################//
		_s.setLabel = function(label){
			
			if(label === undefined ) return;
			_s.text_do.setInnerHTML(label);
		
			if(_s == null) return;
			_s.setWidth(_s.text_do.getWidth());
			_s.setHeight(_s.text_do.getHeight());
			_s.positionPointer();
				
		};
		
		_s.positionPointer = function(offsetX){
			var finalX;
			var finalY;
			
			if(!offsetX) offsetX = 0;
			
			finalX = parseInt((_s.w - 8)/2) + offsetX;
			finalY = _s.h;
			_s.pointerHolder_do.setX(finalX);
			_s.pointerHolder_do.setY(finalY);
			
		};
		

		//##########################################//
		/* show / hide*/
		//##########################################//
		_s.show = function(){
			_s.isShowed_bl = true;
			clearTimeout(_s.hideWithDelayId_to);

			FWDAnimation.killTweensOf(_s);
			clearTimeout(_s.showWithDelayId_to);
			_s.showWithDelayId_to = setTimeout(_s.showFinal, _s.toolTipsButtonsHideDelay);
		};
		
		_s.showFinal = function(){
			_s.setVisible(true);
			FWDAnimation.to(_s, .4, {alpha:1, onComplete:function(){_s.setVisible(true);}, ease:Quart.easeOut});
		};
		
		_s.hide = function(){
			
			if(!_s.isShowed_bl) return;
			clearTimeout(_s.hideWithDelayId_to);
			_s.hideWithDelayId_to = setTimeout(function(){
				clearTimeout(_s.showWithDelayId_to);
				FWDAnimation.killTweensOf(_s);
				_s.setVisible(false);
				_s.isShowed_bl = false;	
				_s.setAlpha(0);
			}, 100);
			
		};
		
	
		_s.init();
	};
	
	
	/* set prototype */
	FWDEVPScrubberToolip.setPrototype = function(){
		FWDEVPScrubberToolip.prototype = null;
		FWDEVPScrubberToolip.prototype = new FWDEVPDO("div");
	};
	
	FWDEVPScrubberToolip.CLICK = "onClick";
	FWDEVPScrubberToolip.MOUSE_DOWN = "onMouseDown";
	
	FWDEVPScrubberToolip.prototype = null;
	window.FWDEVPScrubberToolip = FWDEVPScrubberToolip;
}(window));