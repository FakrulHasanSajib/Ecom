/**
 * Easy Video Player PACKAGED v9.1
 * Context menu button.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (){
	
	var FWDEVPContextMenuButton = function(
			label1, 
			label2, 
			normalColor,
			selectedColor,
			disabledColor,
			padding
		){

		'use strict';
		
		var _s = this;
		var prototype = FWDEVPContextMenuButton.prototype;
		
		_s.label1_str = label1;
		_s.label2_str = label2;
		_s.nBC = normalColor;
		_s.sBC = selectedColor;
		_s.disabledColor_str = disabledColor;
		
		_s.totalWidth = 400;
		_s.totalHeight = 20;
		
		_s.isMobile_bl = FWDEVPUtils.isMobile;
		_s.currentState = 1;
		_s.showSecondButton_bl = label2 != undefined;
		
		//##########################################//
		/* initialize _s */
		//##########################################//
		_s.init = function(){
			_s.setBackfaceVisibility();
			_s.setButtonMode(true);
			_s.setupMainContainers();
			_s.setWidth(_s.totalWidth);
			_s.setHeight(_s.totalHeight);
			_s.setButtonState(0);
		};
		
		//##########################################//
		/* setup main containers */
		//##########################################//
		_s.setupMainContainers = function(){
			
			_s.text1_sdo = new FWDEVPDO("div");
			_s.text1_sdo.setBackfaceVisibility();
			_s.text1_sdo.setDisplay("inline-block");
			_s.text1_sdo.style().fontFamily = "Arial";
			_s.text1_sdo.style().fontSize= "12px";
			_s.text1_sdo.style().color = _s.nBC;
			_s.text1_sdo.style().fontSmoothing = "antialiased";
					_s.text1_sdo.setInnerHTML(_s.label1_str);
			_s.addChild(_s.text1_sdo);
			
			if(_s.showSecondButton_bl){
				_s.text2_sdo = new FWDEVPDO("div");
				_s.text2_sdo.setBackfaceVisibility();
				_s.text2_sdo.setDisplay("inline-block");
				_s.text2_sdo.style().fontFamily = "Arial";
				_s.text2_sdo.style().fontSize= "12px";
				_s.text2_sdo.style().color = _s.nBC;
				_s.text2_sdo.style().fontSmoothing = "antialiased";
				_s.text2_sdo.setInnerHTML(_s.label2_str);
				_s.addChild(_s.text2_sdo);
			}
			
			_s.dumy_sdo = new FWDEVPDO("div");
			if(FWDEVPUtils.isIE){
				_s.dumy_sdo.setBkColor("#FF0000");
				_s.dumy_sdo.setAlpha(0);
			};
			_s.addChild(_s.dumy_sdo);
			
			if(_s.isMobile_bl){
				_s.screen.addEventListener("touchstart", _s.onMD);
			}else if(_s.screen.addEventListener){
				_s.screen.addEventListener("mouseover", _s.onMOV);
				_s.screen.addEventListener("mouseout", _s.onMOU);
				_s.screen.addEventListener("mousedown", _s.onMD);
				_s.screen.addEventListener("click", _s.onCLK);
			}
		};
		
		_s.onMOV = function(animate){
			if(_s.isDisabled_bl) return;
			FWDAnimation.killTweensOf(_s.text1_sdo);
			if(animate){
				FWDAnimation.to(_s.text1_sdo.screen, .5, {css:{color:_s.sBC}, ease:Expo.easeOut});
				if(_s.showSecondButton_bl) FWDAnimation.to(_s.text2_sdo.screen, .5, {css:{color:_s.sBC}, ease:Expo.easeOut});
			}else{
				_s.text1_sdo.style().color = _s.sBC;
				if(_s.showSecondButton_bl){
					FWDAnimation.killTweensOf(_s.text2_sdo);
					_s.text2_sdo.style().color = _s.sBC;
				}
			}
			_s.dispatchEvent(FWDEVPContextMenuButton.MOUSE_OVER);
		};
			
		_s.onMOU = function(e){
			if(_s.isDisabled_bl) return;
			FWDAnimation.killTweensOf(_s.text1_sdo);
			FWDAnimation.to(_s.text1_sdo.screen, .5, {css:{color:_s.nBC}, ease:Expo.easeOut});
			
			if(_s.showSecondButton_bl){
				FWDAnimation.killTweensOf(_s.text2_sdo);
				FWDAnimation.to(_s.text2_sdo.screen, .5, {css:{color:_s.nBC}, ease:Expo.easeOut});
			}
			_s.dispatchEvent(FWDEVPContextMenuButton.MOUSE_OUT);
		};
		
		_s.onCLK = function(e){
			if(_s.isDeveleper_bl){
				window.open("http://www.webdesign-flash.ro", "_blank");
				return;
			}
			if(_s.isDisabled_bl) return;
			if(e.preventDefault) e.preventDefault();
			_s.dispatchEvent(FWDEVPContextMenuButton.CLICK);
		};
		
		_s.onMD = function(e){
			if(_s.isDisabled_bl) return;
			if(e.preventDefault) e.preventDefault();
			_s.dispatchEvent(FWDEVPContextMenuButton.MOUSE_DOWN, {e:e});
		};
		
		//##############################//
		/* toggle button */
		//#############################//
		_s.toggleButton = function(){
			if(!_s.showSecondButton_bl ) return;
			if(_s.currentState == 1){
				_s.text1_sdo.setVisible(true);
				_s.text2_sdo.setVisible(false);
				_s.currentState = 0;
				_s.dispatchEvent(FWDEVPContextMenuButton.FIRST_BUTTON_CLICK);
			}else{
				_s.text1_sdo.setVisible(false);
				_s.text2_sdo.setVisible(true);
				_s.currentState = 1;
				_s.dispatchEvent(FWDEVPContextMenuButton.SECOND_BUTTON_CLICK);
			}
		};
		
		//##############################//
		/* set second buttons state */
		//##############################//
		_s.setButtonState = function(state){
			if(state == 0){
				_s.text1_sdo.setVisible(true);
				if(_s.showSecondButton_bl) _s.text2_sdo.setVisible(false);
				_s.currentState = 0;
			}else if(state == 1){
				_s.text1_sdo.setVisible(false);
				if(_s.showSecondButton_bl) _s.text2_sdo.setVisible(true);
				_s.currentState = 1;
			}
		};		

		//##########################################//
		/* center text */
		//##########################################//
		_s.centerText = function(){
			_s.dumy_sdo.setWidth(_s.totalWidth);
			_s.dumy_sdo.setHeight(_s.totalHeight);
			if(FWDEVPUtils.isIEAndLessThen9){
				_s.text1_sdo.setY(Math.round((_s.totalHeight - _s.text1_sdo.getHeight())/2) - 1);
				if(_s.showSecondButton_bl) _s.text2_sdo.setY(Math.round((_s.totalHeight - _s.text2_sdo.getHeight())/2) - 1);
			}else{
				_s.text1_sdo.setY(Math.round((_s.totalHeight - _s.text1_sdo.getHeight())/2));
				if(_s.showSecondButton_bl) _s.text2_sdo.setY(Math.round((_s.totalHeight - _s.text2_sdo.getHeight())/2));
			}
			_s.text1_sdo.setHeight(_s.totalHeight + 2);
			if(_s.showSecondButton_bl) _s.text2_sdo.setHeight(_s.totalHeight + 2);
		};
		
		//###############################//
		/* get max text width */
		//###############################//
		_s.getMaxTextWidth = function(){
			var w1 = _s.text1_sdo.getWidth();
			var w2 = 0;
			if(_s.showSecondButton_bl) w2 = _s.text2_sdo.getWidth();
			return Math.max(w1, w2);
		};
		
		//##############################//
		/* disable /enable button */
		//##############################//
		_s.disable = function(){
			_s.isDisabled_bl = true;
			FWDAnimation.killTweensOf(_s.text1_sdo);
			FWDAnimation.to(_s.text1_sdo.screen, .5, {css:{color:_s.disabledColor_str}, ease:Expo.easeOut});
			_s.setButtonMode(false);
		};
		
		_s.enable = function(){
			_s.isDisabled_bl = false;
			FWDAnimation.killTweensOf(_s.text1_sdo);
			FWDAnimation.to(_s.text1_sdo.screen, .5, {css:{color:_s.nBC}, ease:Expo.easeOut});
			_s.setButtonMode(true);
		};
		
		_s.init();
	};
	
	/* set prototype */
	FWDEVPContextMenuButton.setPrototype = function(){
		FWDEVPContextMenuButton.prototype = new FWDEVPDO("div");
	};
	
	FWDEVPContextMenuButton.FIRST_BUTTON_CLICK = "onFirstClick";
	FWDEVPContextMenuButton.SECOND_BUTTON_CLICK = "secondButtonOnClick";
	FWDEVPContextMenuButton.MOUSE_OVER = "onMOV";
	FWDEVPContextMenuButton.MOUSE_OUT = "onMOU";
	FWDEVPContextMenuButton.MOUSE_DOWN = "onMD";
	FWDEVPContextMenuButton.CLICK = "onCLK";
	
	FWDEVPContextMenuButton.prototype = null;
	window.FWDEVPContextMenuButton = FWDEVPContextMenuButton;
}(window));