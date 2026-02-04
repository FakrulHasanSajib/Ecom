/**
 * Easy Video Player PACKAGED v9.1
 * Preloader.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (window){

	var FWDEVPPreloader = function(prt, preloaderPostion, radius, backgroundColor, fillClr, strokeSize, animDuration){

		'use strict';
		
		var _s  = this;		
		_s.preloaderPostion = preloaderPostion;
		_s.backgroundColor = backgroundColor;
		_s.fillClr = fillClr;
		_s.radius = radius;
		_s.strokeSize = strokeSize;
		_s.animDuration = animDuration || 300;
		_s.strtAngle = 270;
		_s.countAnimation = 0;
		_s.isShowed_bl = true;
		_s.slideshowAngle = {n:0};
		

		//###################################//
		/* init */
		//###################################//
		_s.init = function(){
			_s.style().pointerEvents = 'none';
			_s.main_do = new FWDEVPDO("div");
			_s.main_do.setOverflow("visible");
			_s.main_do.setWidth(_s.radius * 2 + _s.strokeSize);
			_s.main_do.setHeight(_s.radius * 2 + _s.strokeSize);
			_s.addChild(_s.main_do);
			_s.setOverflow('visible');
			_s.setWidth((_s.radius * 2) + _s.strokeSize);
			_s.setHeight((_s.radius * 2) + _s.strokeSize);
			_s.bkCanvas =  new FWDEVPDO("canvas");
			_s.bkCtx = _s.bkCanvas.screen.getContext('2d');
			_s.fillCircleCanvas = new FWDEVPDO("canvas");
			_s.fillCtx = _s.fillCircleCanvas.screen.getContext('2d');
			_s.main_do.screen.style.transformOrigin = "50% 50%";
		
			_s.main_do.addChild(_s.bkCanvas);
			_s.main_do.addChild(_s.fillCircleCanvas);
			_s.drawBackground();
			_s.drawFill();
			_s.hide();
		};

		/*
			Postion
		*/
		_s.positionAndResize = function(){

			if(_s.preloaderPostion == 'bottomleft'){
				_s.setX(prt.offsetPreloader);
				_s.setY(prt.sH - _s.h - prt.offsetPreloader);
			}else if(_s.preloaderPostion == 'bottomright'){
				_s.setX(prt.sW - _s.w - prt.offsetPreloader);
				_s.setY(prt.sH - _s.h - prt.offsetPreloader);
			}else if(_s.preloaderPostion == 'topright'){
				_s.setX(prt.sW - _s.w - prt.offsetPreloader);
				_s.setY(prt.offsetPreloader);
			}else if(_s.preloaderPostion == 'topleft'){
				_s.setX(prt.offsetPreloader);
				_s.setY(prt.offsetPreloader);
			}else if(_s.preloaderPostion == 'center'){
				_s.setX(Math.round(prt.sW - _s.w)/2);
				_s.setY(Math.round(Math.min(prt.sH, prt.viewportSize.h) - _s.h)/2);
			}
		}	


		/* draw background */
		_s.drawBackground = function(){
			_s.bkCanvas.screen.width = (_s.radius * 2) + _s.strokeSize * 2;
			_s.bkCanvas.screen.height = (_s.radius * 2) + _s.strokeSize * 2;
			_s.bkCtx.lineWidth = _s.thicknessSize;
			_s.bkCtx.translate(_s.strokeSize/2, _s.strokeSize/2);
			_s.bkCtx.shadowColor = '#333333';
		    _s.bkCtx.shadowBlur = 1;
		   
			_s.bkCtx.lineWidth=_s.strokeSize;
			_s.bkCtx.strokeStyle = _s.backgroundColor;
			_s.bkCtx.beginPath();
			_s.bkCtx.arc(_s.radius, _s.radius,  _s.radius, (Math.PI/180) * 0, (Math.PI/180) * 360, false);
			_s.bkCtx.stroke();
			_s.bkCtx.closePath();
		};
		

		/* draw fill */
		_s.drawFill = function(){	
			_s.fillCircleCanvas.screen.width = (_s.radius * 2) + _s.strokeSize * 2;
			_s.fillCircleCanvas.screen.height = (_s.radius * 2) + _s.strokeSize * 2;
			_s.fillCtx.lineWidth = _s.thicknessSize;
			_s.fillCtx.translate(_s.strokeSize/2, _s.strokeSize/2);
			_s.fillCtx.lineWidth=_s.strokeSize;
			_s.fillCtx.strokeStyle = _s.fillClr;
			_s.fillCtx.beginPath();
			_s.fillCtx.arc(_s.radius, _s.radius,  _s.radius, (Math.PI/180) * _s.strtAngle, (Math.PI/180) * (_s.strtAngle +  _s.slideshowAngle.n), false);
			_s.fillCtx.stroke();
			_s.fillCtx.closePath()
		};
		

		//###################################//
		/* start / stop preloader animation */
		//###################################//
		_s.startSlideshow = function(){
			if(_s == null) return;
			FWDAnimation.killTweensOf(_s.slideshowAngle);
			FWDAnimation.to(_s.slideshowAngle, _s.animDuration, {n:360, onUpdate:_s.drawFill, onComplete:_s.stopSlideshow});
		};
		
		_s.stopSlideshow = function(){
			FWDAnimation.killTweensOf(_s.slideshowAngle);
			FWDAnimation.to(_s.slideshowAngle, .8, {n:0, onupdate:_s.drawFill, onUpdate:_s.drawFill, ease:Expo.easiInOut});
		};

		
		_s.startPreloader = function(){
			_s.stopPreloader();
			_s.slideshowAngle = {n:0};
			FWDAnimation.to(_s.slideshowAngle, _s.animDuration, {n:360, onUpdate:_s.drawFill, repeat:100, yoyo:true, ease:Expo.easInOut});
			FWDAnimation.to(_s.main_do.screen, _s.animDuration, {rotation:360,  repeat:100});
		}

		_s.stopPreloader = function(){
			FWDAnimation.killTweensOf(_s.slideshowAngle);
			FWDAnimation.killTweensOf(_s.main_do.screen);
			FWDAnimation.to(_s.main_do.screen, 0.00001, {rotation:0});
		}
		
		
		//###################################//
		/* show / hide preloader animation */
		//###################################//
		_s.show = function(){
			if(_s.isShowed_bl) return;
			_s.setVisible(true);
			FWDAnimation.killTweensOf(_s);
			FWDAnimation.to(_s, 1, {alpha:1, delay:.2});
			_s.stopPreloader();
			_s.startPreloader();
			_s.isShowed_bl = true;
		};
		
		_s.hide = function(animate){
			if(!_s.isShowed_bl) return;
			FWDAnimation.killTweensOf(this);
			if(animate){
				FWDAnimation.to(this, .2, {alpha:0, onComplete:_s.onHideComplete});
			}else{
				_s.setVisible(false);
				_s.setAlpha(0);
			}
			_s.isShowed_bl = false;
		};
		
		_s.onHideComplete = function(){
			_s.setVisible(false);
			_s.stopPreloader();
			_s.dispatchEvent(FWDEVPPreloader.HIDE_COMPLETE);
		};


		/**
		 * Update colors.
		 * @param {String} backgroundColor
		 * @param {String} fillClr
		 */
		_s.updateColors = function(backgroundColor, fillClr){
			_s.backgroundColor = backgroundColor;
			_s.fillClr = fillClr;
			_s.bkCtx.strokeStyle = _s.backgroundColor;
			_s.fillCtx.strokeStyle = _s.fillClr;
			_s.drawBackground();
		}
		
		_s.init();
	};
	
	
	/* set prototype */
    FWDEVPPreloader.setPrototype = function(){
    	FWDEVPPreloader.prototype = new FWDEVPDO("div");
    };
    
    FWDEVPPreloader.HIDE_COMPLETE = "hideComplete";
    
    FWDEVPPreloader.prototype = null;
	window.FWDEVPPreloader = FWDEVPPreloader;
}(window));