/**
 * Easy Video Player PACKAGED v9.1
 * Poster manager.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (window){

	var FWDEVPPoster = function(
			prt, 
			backgroundColor,
			showPoster,
			fillEntireScreenWithPoster_bl
		){

		'use strict';
		
		var _s  = this;
		var prototype = FWDEVPPoster.prototype;
		
		_s.img_img = new Image();
		_s.imgW = 0;
		_s.imgH = 0;
		_s.finalW = 0;
		_s.finalH = 0;
		_s.finalX = 0;
		_s.finalY = 0;
		
		_s.curPath;
		_s.backgroundColor_str = backgroundColor;
		_s.fillEntireScreenWithPoster_bl = fillEntireScreenWithPoster_bl;
		_s.showPoster_bl = showPoster;
		_s.isShowed_bl = true;
		_s.allowToShow_bl = true;
		_s.isMobile_bl = FWDEVPUtils.isMobile;
	
		_s.init = function(){
			_s.pHld_do =  new FWDEVPDO('div');
			_s.pHld_do.setOverflow('visible');
			_s.addChild(_s.pHld_do);
			_s.hide();
			_s.setBkColor(_s.backgroundColor_str);
		};
		
		_s.positionAndResize = function(){
			if(!prt.sW) return;
			_s.setWidth(prt.sW);
			_s.setHeight(prt.sH);
		
			if(!_s.imgW) return;
			var scX = prt.sW/_s.imgW;
			var scY = prt.sH/_s.imgH;
			var ttSc;
			
			if(_s.fillEntireScreenWithPoster_bl){
				if(scX >= scY){
					ttSc = scX;
				}else{
					ttSc = scY;
				}
			}else{
				if(scX <= scY){
					ttSc = scX;
				}else{
					ttSc = scY;
				}
			}
			
			_s.finalW = Math.round(ttSc * _s.imgW);
			_s.finalH = Math.round(ttSc * _s.imgH);
			_s.finalX = parseInt((prt.sW - _s.finalW)/2);
			_s.finalY = parseInt((prt.sH - _s.finalH)/2);
		
			_s.img_do.setX(_s.finalX);
			_s.img_do.setY(_s.finalY);
			_s.img_do.setWidth(_s.finalW);
			_s.img_do.setHeight(_s.finalH);		
		};
		
		_s.setPoster = function(path, o){
			if(!o && path && (FWDEVPUtils.trim(path) == "") || path =="none"){
				_s.showOrLoadOnMobile_bl = true;
				_s.isTransparent_bl = true;
				_s.show();
				return;
			}else if(!o && path == "youtubemobile"){
				_s.isTransparent_bl = false;
				_s.showOrLoadOnMobile_bl = false;
				_s.img_img.src = null;
				_s.imgW = 0;
				return;
			}else if(!o && path == _s.curPath){
				_s.isTransparent_bl = false;
				_s.showOrLoadOnMobile_bl = true;
				_s.show();
				return;
			}
			
			_s.isTransparent_bl = false;
			_s.showOrLoadOnMobile_bl = true;
			_s.curPath = path;
			if(_s.allowToShow_bl) _s.isShowed_bl = false;
			if(!path) return;

			_s.hide(true);
			try{
				_s.pHld_do.removeChild(_s.img_do);
			}catch(e){}
		
			_s.img_img = new Image();
			_s.img_do = new FWDEVPDO("img");
		
			_s.img_img.onload = _s.posterLoadHandler;
			_s.img_img.onerror = function(){}
			_s.img_img.src = _s.curPath;
		};
		
		_s.posterLoadHandler = function(e){
			_s.imgW = _s.img_img.naturalWidth;
			_s.imgH = _s.img_img.naturalHeight;
		
			_s.img_do.setScreen(_s.img_img);
			_s.pHld_do.addChild(_s.img_do);
			_s.show(true);
			_s.positionAndResize();
		};

		_s.reset = function(){
			_s.img_img.src = '';
		}
		

		//################################//
		/* show / hide */
		//################################//
		_s.show = function(allowToShow_bl, overwrite){
			
			if((!_s.allowToShow_bl || _s.isShowed_bl || !_s.showOrLoadOnMobile_bl || !_s.curPath || prt._d.autoPlay_bl) && !overwrite) return;
		
			_s.isShowed_bl = true;
			
			if(_s.isTransparent_bl){
				if(_s.alpha != 0) _s.setAlpha(0);
			}
			
			_s.setVisible(true);
			if(!_s.isTransparent_bl && !prt.isPlaying_bl){
				var dl = 0;
				if(prt.delayPoster) dl = .4;
				FWDAnimation.killTweensOf(_s);
				FWDAnimation.to(_s, .6, {alpha:1, delay:dl});
			}
			
			_s.positionAndResize();
		};
		
		_s.hide = function(o){
			if(!_s.isShowed_bl && !o) return;
			_s.isShowed_bl = false;
			_s.setAlpha(0);
			_s.setVisible(false);
		};
		
		
		_s.init();
	};
	
	
	/* set prototype */
    FWDEVPPoster.setPrototype = function(){
    	FWDEVPPoster.prototype = new FWDEVPDO("div");
    };
    
    FWDEVPPoster.prototype = null;
	window.FWDEVPPoster = FWDEVPPoster;
}(window));