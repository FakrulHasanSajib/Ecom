/**
 * Easy Video Player PACKAGED v9.1
 * Vanila javascript video player.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (window){
	
	var FWDEVPlayer = function(props){

		'use strict';
		
		FWDEVPlayer.V = '8.4';
		var _s = this;
	
		_s.props = props;
		_s.isInstantiate_bl = false;
		_s.displayType = props.displayType || FWDEVPlayer.RESPONSIVE;
		_s.delayPoster = !props.delayPoster;
		
		if(_s.displayType.toLowerCase() != FWDEVPlayer.RESPONSIVE 
		   && _s.displayType.toLowerCase() != FWDEVPlayer.FULL_SCREEN
		   && _s.displayType.toLowerCase() != FWDEVPlayer.AFTER_PARENT
		   && _s.displayType.toLowerCase() != FWDEVPlayer.STICKY
		   && _s.displayType.toLowerCase() != FWDEVPlayer.LIGHTBOX
		){
			_s.displayType = FWDEVPlayer.RESPONSIVE;
		}
		
		if(props.displayType.toLowerCase() == FWDEVPlayer.BACKGROUND_VIDEO){
			_s.displayType = FWDEVPlayer.BACKGROUND_VIDEO;
		}
		
		_s.displayType = _s.displayType.toLowerCase();

		if(FWDEVPlayer.videoStartBehaviour != "pause" 
			&& FWDEVPlayer.videoStartBehaviour != "stop"
			&& FWDEVPlayer.videoStartBehaviour != "default"
		){
			FWDEVPlayer.videoStartBehaviour = "pause";
		}

		_s.stickyOnScroll = props.stickyOnScroll || "no";
		_s.stickyOnScroll = _s.stickyOnScroll == "yes" ? true : false;
		if(_s.displayType != FWDEVPlayer.RESPONSIVE) _s.stickyOnScroll = false;
		_s.isMinShowed = true;
		
		_s.stickyOnScrollWidth = props.stickyOnScrollWidth || 700;
		_s.stickyOnScrollHeight = props.stickyOnScrollHeight || 394; 

		_s.fontIcon = props.fontIcon || 'fwdicon';

		_s.maxWidth = props.maxWidth || 640;
		_s.maxHeight = props.maxHeight || 380;
	
		_s.showPreloader_bl = props.showPreloader; 
		_s.showPreloader_bl = _s.showPreloader_bl == "no" ? false : true;
	
		_s.disableDoubleClickFullscreen_bl = props.disableDoubleClickFullscreen || "no"; 
		_s.disableDoubleClickFullscreen_bl = _s.disableDoubleClickFullscreen_bl == "yes" ? true : false;
			
		_s.mainFolderPath_str = props.mainFolderPath;
		if((_s.mainFolderPath_str.lastIndexOf("/") + 1) != _s.mainFolderPath_str.length){
			_s.mainFolderPath_str += "/";
		}
		
		_s.sknPth = props.skinPath;
		if((_s.sknPth.lastIndexOf("/") + 1) != _s.sknPth.length){
			_s.sknPth += "/";
		}
		
		_s.warningIconPath_str = _s.mainFolderPath_str + _s.sknPth + "warningIcon.png";
		_s.fillEntireVideoScreen_bl = false;
		_s.isShowedFirstTime_bl = true;
		FWDEVPlayer.instaces_ar.push(this);
	

		/* init */
		_s.init = function(){
			
			if(_s.isInstantiate_bl) return;
			
			FWDTweenLite.ticker.useRAF(true);
			_s.props = props;
			
			_s.instanceName_str = _s.props.instanceName;
			
			_s.mustHaveHolderDiv_bl = false;
			
			if(!_s.instanceName_str){
				alert("FWDEVPlayer instance name is requires please make sure that the instanceName parameter exsists and it's value is uinique.");
				return;
			}
			
			if(window[_s.instanceName_str]){
				alert("FWDEVPlayer instance name " + _s.instanceName_str +  " is already defined and contains a different instance reference, set a different instance name.");
				return;
			}else{
				window[_s.instanceName_str] = this;
			}
		
			if(!_s.props){
				alert("FWDEVPlayer constructor properties object is not defined!");
				return;
			}
			
			if(!_s.props.parentId){		
				alert("Property parentId is not defined in the FWDEVPlayer constructor, _s property represents the div id into which the megazoom is added as a child!");
				return;
			}
			
			if(_s.displayType == FWDEVPlayer.RESPONSIVE || _s.displayType == FWDEVPlayer.AFTER_PARENT) _s.mustHaveHolderDiv_bl = true;
		
			if(_s.mustHaveHolderDiv_bl && !FWDEVPUtils.getChildById(_s.props.parentId)){
				alert("FWDEVPlayer holder div is not found, please make sure that the div exsists and the id is correct! " + _s.props.parentId);
				return;
			}
			
			var args = FWDEVPUtils.getUrlArgs(window.location.search);
			var embedTest = args.EVPInstanceName;
			
			if(_s.instanceName_str == embedTest){
				FWDEVPlayer.isEmbedded_bl = true;
				_s.isEmbedded_bl = true;
			}
			
			_s.position_str = _s.props.verticalPosition;
			if(!_s.position_str) _s.position_str = FWDEVPlayer.POSITION_TOP;
			if(_s.position_str == "bottom"){
				_s.position_str = FWDEVPlayer.POSITION_BOTTOM;
			}else{
				_s.position_str = FWDEVPlayer.POSITION_TOP;
			}
			
			_s.horizontalPosition_str = _s.props.horizontalPosition;
			if(!_s.horizontalPosition_str) _s.horizontalPosition_str = FWDEVPlayer.CENTER;
			if(_s.horizontalPosition_str == "center"){
				_s.horizontalPosition_str = FWDEVPlayer.CENTER;
			}else if(_s.horizontalPosition_str == "left"){
				_s.horizontalPosition_str = FWDEVPlayer.LEFT;
			}else if(_s.horizontalPosition_str == "right"){
				_s.horizontalPosition_str = FWDEVPlayer.RIGHT;
			}else{
				_s.horizontalPosition_str = FWDEVPlayer.CENTER;
			}
			
			_s.isShowed_bl = _s.props.showPlayerByDefault; 
			_s.isShowed_bl = _s.isShowed_bl == "no" ? false : true;
			
			_s.preloaderBackgroundColor = _s.props.preloaderBackgroundColor || "#000000";
			_s.preloaderFillColor = _s.props.preloaderFillColor || "#FFFFFF";
			_s.offsetX = parseInt(props.offsetX) || 0;
			_s.offsetY = parseInt(props.offsetY) || 0
		
			if(_s.isEmbedded_bl) _s.displayType = FWDEVPlayer.FULL_SCREEN;
			
			_s.body = document.getElementsByTagName("body")[0];
			_s.stageContainer = null;
			
			if(_s.displayType == FWDEVPlayer.STICKY){
				_s.stageContainer = document.createElement("div");
				_s.stageContainer.style.position = "fixed";
				_s.stageContainer.style.width = "100%";
				_s.stageContainer.style.zIndex = "999999";
				_s.stageContainer.style.height = "0px";
			
				document.documentElement.appendChild(_s.stageContainer);
				_s.stageContainer.style.overflow = "visible";
				
			}else if(_s.displayType == FWDEVPlayer.FULL_SCREEN || _s.displayType == FWDEVPlayer.BACKGROUND_VIDEO || _s.displayType == FWDEVPlayer.LIGHTBOX){
				_s.stageContainer = document.documentElement;
			}else{
				_s.stageContainer = FWDEVPUtils.getChildById(_s.props.parentId);
			}

			_s.listeners = {events_ar:[]};
			
			_s.lightBoxBackgroundOpacity = _s.props.lightBoxBackgroundOpacity || 1;
			_s.lightBoxBackgroundColor_str = _s.props.lightBoxBackgroundColor || "transparent";
			_s.lightBoxWidth = _s.props.maxWidth || 500;
			_s.lightBoxHeight =  _s.props.maxHeight || 400;
			
			_s.backgroundColor_str = _s.props.backgroundColor || "transparent";
			_s.videoBackgroundColor_str = "#000000";
			_s.flashObjectMarkup_str =  null;
			_s.controllerHeight = parseInt(_s.props.controllerHeight) || 70;
			_s.lastX = 0;
			_s.lastY = 0;
			_s.sW = 0;
			_s.sH = 0;
			
			_s.isFullScreen_bl = false;
			_s.posterPath_str = _s.props.posterPath;
			
			_s.autoScale_bl = _s.props.autoScale;
			_s.autoScale_bl = _s.autoScale_bl == "yes" ? true : false;
			_s.showErrorInfo_bl = _s.props.showErrorInfo; 
			_s.showErrorInfo_bl = _s.showErrorInfo_bl == "no" ? false : true;
			_s.isVideoPlayingWhenOpenWindows_bl = false;
			_s.useWithoutVideoScreen_bl = _s.props.useWithoutVideoScreen; 
			_s.useWithoutVideoScreen_bl = _s.useWithoutVideoScreen_bl == "yes" ? true : false;
			_s.totalTime = 100;
			_s.percent_played = 0;

			_s.mainBackgroundImagePath_str = _s.props.mainBackgroundImagePath;
			if(_s.mainBackgroundImagePath_str && _s.mainBackgroundImagePath_str.length < 3) _s.mainBackgroundImagePath_str = undefined;
			_s.isMobile_bl = FWDEVPUtils.isMobile;
			_s.hasPointerEvent_bl = FWDEVPUtils.hasPointerEvent;
		
			_s.initializeOnlyWhenVisible_bl = _s.props.initializeOnlyWhenVisible; 
			_s.initializeOnlyWhenVisible_bl = _s.initializeOnlyWhenVisible_bl == "yes" ? true : false;
			
			_s.googleAnalyticsMeasurementId = _s.props.googleAnalyticsMeasurementId; 
			if(!window['gtag'] && _s.googleAnalyticsMeasurementId){
				var script = document.createElement('script');
			    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + _s.googleAnalyticsMeasurementId;
			    script.setAttribute("async", "true");
			    script.onload = function() {
			    	_s.initGoogleAnalytics();
			    };
			    document.head.appendChild(script);
			}else if(window['gtag'] && _s.googleAnalyticsMeasurementId){
				_s.initGoogleAnalytics();
			}

		
			if(_s.displayType == FWDEVPlayer.LIGHTBOX){
				_s.setupLightBox();
			}else if(_s.displayType == FWDEVPlayer.STICKY){
				_s.setupPlayer();
				_s.startResizeHandler();
			}else{
				_s.setupMainDo();
				if(_s.initializeOnlyWhenVisible_bl){
					_s.startResizeHandler();
					window.addEventListener("scroll", _s.onInitlalizeScrollHandler);
					setTimeout(_s.onInitlalizeScrollHandler, 500);
				}else{
					_s.setupPlayer();
					_s.startResizeHandler();
				}
			}
		};

		_s.initGoogleAnalytics = function(){
			  // Once the script is loaded, initialize Google Analytics
		      window.dataLayer = window.dataLayer || [];
			  _s.gtag = function(){dataLayer.push(arguments);}

		      _s.gtag('js', new Date());
		      _s.gtag('config', _s.googleAnalyticsMeasurementId);
		}


		//#############################################//
		/* add min on scroll */
		//#############################################//
		_s.addMinOnScroll = function(){
			if(_s.displayType != FWDEVPlayer.RESPONSIVE) return;
			if(_s.stickyOnScroll) window.addEventListener("scroll", _s.minimizeOnScrollHandler);
		}

		_s.removeMinOnScroll = function(){
			if(_s.stickyOnScroll) window.removeEventListener("scroll", _s.minimizeOnScrollHandler);
		}

		_s.minimizeOnScrollHandler = function(e){
			var scrollOffsets = FWDEVPUtils.getScrollOffsets();
			_s.pageXOffset = scrollOffsets.x;
			_s.pageYOffset = scrollOffsets.y;
			
			if(_s.stageContainer.getBoundingClientRect().bottom < 0){
				_s.setMinimized();
			}else{
				_s.setNormal();
			}
		}

		_s.setMinimized = function(){
			if(_s.isMin || _s.isFullscreen_bl) return;
			_s.isMin = true;
			_s.main_do.style().position = 'fixed';
			_s.main_do.style().zIndex = 9999999999999;
			_s.main_do.setAlpha(0);
			_s.startPosisionOnMin();
		}

		_s.startPosisionOnMin = function(){
			_s.resizeHandler();
			_s.positionOnMin();
		}

		_s.setNormal = function(){
			if(!_s.isMin) return;
			_s.isMinShowed = true;
			_s.isMin = false;
			_s.main_do.style().position = "relative";
			_s.main_do.style().zIndex = 0;
			FWDAnimation.killTweensOf(_s.main_do);
			_s.main_do.setAlpha(1);
			_s.main_do.setX(0);
			_s.main_do.setY(0);
			if(_s.opener_do) _s.opener_do.setX(-1000);
						
			_s.startPosisionOnNormal();
		}

		_s.startPosisionOnNormal = function(){
			if(_s.opener_do) _s.opener_do.showCloseButton();
			_s.resizeHandler();
		}
		
		_s.positionOnMin = function(animate){
			if(!_s.isMin && !animate) return;
			var offset = 5;
			var dl = .2;
			if(_s.isMobile_bl) offset= 0;
			var offsetTop = 0;
			if(!_s.isMinShowed){
				dl = 0;
				offsetTop = Math.round(_s.sH) + offset;
			} 

			if(_s.opener_do){
				var oX = _s.ws.w - _s.opener_do.w - offset;
				var oY = _s.ws.h - _s.sH - offset + offsetTop - _s.opener_do.h;
			}

			_s.main_do.setX(_s.ws.w - _s.sW - offset);
			if(_s.main_do.alpha == 0 || animate){
				if(_s.main_do.alpha == 0){
					_s.main_do.setY(_s.ws.h);
					if(_s.opener_do){
						_s.opener_do.setX(oX);
						_s.opener_do.setY(_s.ws.h);
					}
				}
				FWDAnimation.to(_s.main_do, .8, {alpha:1, y:_s.ws.h - _s.sH - offset + offsetTop, delay:dl, ease:Expo.easeInOut});
				if(_s.opener_do){
					FWDAnimation.killTweensOf(_s.opener_do);
					FWDAnimation.to(_s.opener_do, .8, {x:oX, y:oY, delay:dl, ease:Expo.easeInOut});
				}
			}else{
				FWDAnimation.killTweensOf(_s.main_do);
				_s.main_do.setAlpha(1);
				_s.main_do.setY(_s.ws.h - _s.sH - offset + offsetTop);
				if(_s.opener_do){
					FWDAnimation.killTweensOf(_s.opener_do);
					_s.opener_do.setX(oX);
					_s.opener_do.setY(oY);
				}
			}			
		}
		

		//#############################################//
		/* setup  lighbox...*/
		//#############################################//
		_s.setupLightBox = function(){
			
			FWDEVPLightBox.setPrototype();
			_s.lightBox_do =  new FWDEVPLightBox(_s, 
					_s.lightBoxBackgroundColor_str, 
					_s.backgroundColor_str, 
					_s.lightBoxBackgroundOpacity, 
					_s.lightBoxWidth, 
					_s.lightBoxHeight);
					
			_s.lightBox_do.addListener(FWDEVPLightBox.SHOW, _s.lightBoxShowHandler);
			_s.lightBox_do.addListener(FWDEVPLightBox.CLOSE, _s.lightBoxCloseHandler);
			_s.lightBox_do.addListener(FWDEVPLightBox.HIDE_COMPLETE, _s.lightBoxHideCompleteHandler);
			_s.lighboxAnimDoneId_to = setTimeout(_s.setupPlayer, 1200);
		};
		
		_s.lightBoxShowHandler = function(){}
		
		_s.lightBoxCloseHandler = function(){
			_s.stop();
			_s.stopResizeHandler();
		};
		
		_s.lightBoxHideCompleteHandler = function(){
			_s.dispatchEvent(FWDEVPlayer.HIDE_LIGHTBOX_COMPLETE);
		};
		
		_s.onInitlalizeScrollHandler = function(){
			
			var scrollOffsets = FWDEVPUtils.getScrollOffsets();
			_s.pageXOffset = scrollOffsets.x;
			_s.pageYOffset = scrollOffsets.y;
			
			if(_s.main_do.getRect().top >= -_s.sH && _s.main_do.getRect().top < _s.ws.h){
				window.removeEventListener("scroll", _s.onInitlalizeScrollHandler);
				_s.setupPlayer();
			}
		};
		
		_s.setupPlayer = function(){
			if(!_s.info_do){
				_s.setupMainDo();
				_s.setupInfo();
				_s.setupData();
			}
		}
		

		//#############################################//
		/* setup main do */
		//#############################################//
		_s.setupMainDo = function(){
			if(_s.main_do) return;
			_s.main_do = new FWDEVPDO("div", "relative");
			if(_s.hasPointerEvent_bl) _s.main_do.style().touchAction = "none";
			_s.main_do.style().webkitTapHighlightColor = "rgba(0, 0, 0, 0)";
			_s.main_do.style().webkitFocusRingColor = "rgba(0, 0, 0, 0)";
			_s.main_do.screen.className = 'fwdevp';
			_s.main_do.style().width = "100%";
			_s.main_do.style().height = "100%";
			_s.main_do.setBackfaceVisibility();
			_s.main_do.setBkColor(_s.backgroundColor_str);
			if(!FWDEVPUtils.isMobile || (FWDEVPUtils.isMobile && FWDEVPUtils.hasPointerEvent)) _s.main_do.setSelectable(false);	

			if(_s.displayType ==  FWDEVPlayer.STICKY){
				_s.background_do = new FWDEVPDO("div");
				_s.background_do.style().width = "100%";
				if(_s.mainBackgroundImagePath_str){
					_s.mainBackground_do =  new FWDEVPDO("div");
					_s.stageContainer.appendChild(_s.mainBackground_do.screen);
				}
				_s.stageContainer.appendChild(_s.background_do.screen);
				_s.stageContainer.appendChild(_s.main_do.screen);
			}else if(_s.displayType == FWDEVPlayer.FULL_SCREEN){	
				_s.stageContainer.style.overflow = "hidden";
				_s.main_do.style().position = "absolute";
				document.documentElement.appendChild(_s.main_do.screen);
				_s.stageContainer.style.zIndex = 9999999999998;
				_s.main_do.style().zIndex = 9999999999998;
			}else if(_s.displayType == FWDEVPlayer.BACKGROUND_VIDEO){	
				document.documentElement.appendChild(_s.main_do.screen);
				_s.main_do.style().zIndex = -9999999999998;
				_s.main_do.style().position = "fixed";
				_s.main_do.setAlpha(0);
				document.documentElement.insertBefore(_s.main_do.screen, document.documentElement.firstChild);
			}else if(_s.displayType == FWDEVPlayer.LIGHTBOX){
				_s.main_do.style().position = "absolute";
				_s.stageContainer = _s.lightBox_do.mainLightBox_do.screen;
				_s.stageContainer.appendChild(_s.main_do.screen);
				_s.main_do.setX(-10000);
				_s.main_do.setY(-10000);
				_s.main_do.setWidth(0);
				_s.main_do.setHeight(0);
			}else{
				_s.stageContainer.style.overflow = "hidden";
				_s.stageContainer.appendChild(_s.main_do.screen);
			}	


			if(_s.useWithoutVideoScreen_bl){
				setTimeout(function(){
					_s.stageContainer.style.overflow = "visible";
					_s.main_do.style().overflow = 'visible';
				}, 1000);
			}
	
			if(_s.isEmbedded_bl) _s.main_do.style().zIndex = 9999999999998;
			
		};


		//#####################################//
		/* Setup disable click */
		//#####################################//
		_s.setupDisableClick = function(){
			_s.disableClick_do = new FWDEVPDO("div");
			if(FWDEVPUtils.isIE){
				_s.disableClick_do.setBkColor("#ff0000");
				_s.disableClick_do.setAlpha(0.001);
			}
	
			_s.main_do.addChild(_s.disableClick_do);
		};
		
		_s.disableClick = function(){
			_s.disableClick_bl = true;
			clearTimeout(_s.disableClickId_to);
			if(_s.disableClick_do){
				_s.disableClick_do.setWidth(_s.sW);
				_s.disableClick_do.setHeight(_s.sH);
			}
			_s.disableClickId_to =  setTimeout(function(){
				if(_s.disableClick_do){
					_s.disableClick_do.setWidth(0);
					_s.disableClick_do.setHeight(0);
				}
				_s.disableClick_bl = false;
			}, 500);
		};
		
		_s.showDisable = function(){
			if(_s.disableClick_do.w == _s.sW) return;
			_s.disableClick_do.setWidth(_s.sW);
			_s.disableClick_do.setHeight(_s.sH);
		};
		
		_s.hideDisable = function(){
			if(!_s.disableClick_do) return;
			if(_s.disableClick_do.w == 0) return;
			_s.disableClick_do.setWidth(0);
			_s.disableClick_do.setHeight(0);
		};
		

		//#############################################//
		/* setup info_do */
		//#############################################//
		_s.setupInfo = function(){
			FWDEVPInfo.setPrototype();
			_s.info_do = new FWDEVPInfo(_s, _s.warningIconPath_str, _s.showErrorInfo_bl);
		};	
		

		//#############################################//
		/* resize handler */
		//#############################################//
		_s.startResizeHandler = function(){
			window.addEventListener("resize", _s.onResizeHandler);
			
			if(_s.displayType != FWDEVPlayer.AFTER_PARENT){
				window.addEventListener("orientationchange", _s.orientationChange);
			}

			if(_s.displayType == FWDEVPlayer.STICKY){	
				window.addEventListener("scroll", _s.onScrollHandler);
			}
			
			if(_s.displayType == FWDEVPlayer.LIGHTBOX){
				window.addEventListener("scroll", _s.onScrollHandler);
			}
			
			_s.onResizeHandler(true);
			_s.resizeHandlerId_to = setTimeout(function(){_s.resizeHandler(true);}, 500);
			if(_s.displayType == FWDEVPlayer.BACKGROUND_VIDEO){
				_s.resizeHandlerId_to = setTimeout(function(){_s.resizeHandler(true);}, 900);
			}
		};
		
		_s.onScrollHandler = function(e){
			if(_s.displayType == FWDEVPlayer.STICKY) _s.onResizeHandler();
			if(_s.lightBox_do && !_s.lightBox_do.isShowed_bl) return;
			_s.scrollHandler();
			var scrollOffsets = FWDEVPUtils.getScrollOffsets();
			_s.scrollOffsets = scrollOffsets;
		};
		
		_s.scrollHandler = function(){
			var scrollOffsets = FWDEVPUtils.getScrollOffsets();
			_s.pageXOffset = scrollOffsets.x;
			_s.pageYOffset = scrollOffsets.y;
			if(_s.displayType == FWDEVPlayer.LIGHTBOX){
				_s.lightBox_do.setX(scrollOffsets.x);
				_s.lightBox_do.setY(scrollOffsets.y);
			}else if(_s.isFullScreen_bl || _s.displayType == FWDEVPlayer.FULL_SCREEN){	
				_s.main_do.setX(scrollOffsets.x);
				_s.main_do.setY(scrollOffsets.y);
			}
		};
		
		_s.stopResizeHandler = function(){
			if(window.removeEventListener){
				window.removeEventListener("resize", _s.onResizeHandler);
				window.removeEventListener("scroll", _s.onScrollHandler);
				window.removeEventListener("orientationchange", _s.orientationChange);
			}else if(window.detachEvent){
				window.detachEvent("onresize", _s.onResizeHandler);
			}	
			clearTimeout(_s.resizeHandlerId_to);
		};
		
		_s.onResizeHandler = function(e){
			_s.resizeHandler();
			clearTimeout(_s.resizeHandler2Id_to);
			_s.resizeHandler2Id_to = setTimeout(function(){_s.resizeHandler();}, 300);
		};
		
		_s.orientationChange = function(){
			_s.orintationChangeComplete_bl = false;	
			clearTimeout(_s.resizeHandlerId_to);
			clearTimeout(_s.resizeHandler2Id_to);
			clearTimeout(_s.orientationChangeId_to);
			
			_s.orientationChangeId_to = setTimeout(function(){
				_s.orintationChangeComplete_bl = true; 
				_s.resizeHandler(true);
				}, 150);
			
			_s.stageContainer.style.left = "-5000px";
			if(_s.preloader_do) _s.preloader_do.setX(-5000);	
		};
		

		_s.resizeHandler = function(animate){
		
			var vwSize = FWDEVPUtils.getViewportSize();
			var scrollOffsets = FWDEVPUtils.getScrollOffsets();
			_s.ws = vwSize;
			
			if(_s.displayType == FWDEVPlayer.STICKY && !_s.isFullScreen_bl){
				_s.main_do.style().width = "100%";
				if(_s.main_do.getWidth() > _s.maxWidth){
					_s.main_do.setWidth(_s.maxWidth);
				}
				
				_s.sW = _s.main_do.getWidth();
				if(_s.autoScale_bl){
					_s.sH = parseInt(_s.maxHeight * (_s.sW/_s.maxWidth));
				}else{
					_s.sH = _s.maxHeight;
				}
			
			}else if(_s.displayType == FWDEVPlayer.LIGHTBOX && !_s.isFullScreen_bl){
				if(!_s.lightBox_do.isShowed_bl ||  !_s.main_do) return;
				if(_s.lightBoxWidth > vwSize.w){
					_s.finalLightBoxWidth = vwSize.w;
					_s.finalLightBoxHeight = parseInt(_s.lightBoxHeight * (vwSize.w/_s.lightBoxWidth));
				}else{
					_s.finalLightBoxWidth = _s.lightBoxWidth;
					_s.finalLightBoxHeight = _s.lightBoxHeight;
				}
				_s.lightBox_do.setWidth(vwSize.w);
				_s.lightBox_do.setHeight(vwSize.h);
				_s.lightBox_do.setX(scrollOffsets.x);
				_s.lightBox_do.setY(scrollOffsets.y);
				_s.lightBox_do.mainLightBox_do.setX(parseInt((vwSize.w - _s.finalLightBoxWidth)/2));
				_s.lightBox_do.mainLightBox_do.setY(parseInt((vwSize.h - _s.finalLightBoxHeight)/2));
				if(_s.lightBox_do.clsBtn && _s.lightBox_do.isShowed_bl){ 
					_s.lightBox_do.clsBtn.setX(vwSize.w - _s.lightBox_do.clsBtn.w - 15);
					_s.lightBox_do.clsBtn.setY(15);
				}
				_s.main_do.setX(0);
				_s.main_do.setY(0);
				_s.lightBox_do.mainLightBox_do.setWidth(_s.finalLightBoxWidth);
				_s.lightBox_do.mainLightBox_do.setHeight(_s.finalLightBoxHeight);	
				_s.sW = _s.finalLightBoxWidth;
				_s.sH = _s.finalLightBoxHeight;
			}else if((_s.isFullScreen_bl || _s.displayType == FWDEVPlayer.FULL_SCREEN || _s.displayType == FWDEVPlayer.BACKGROUND_VIDEO) && !_s.doNotDisplyFS){	
				_s.main_do.setX(0);
				_s.main_do.setY(0);
				_s.sW = vwSize.w + 2;
				_s.sH = vwSize.h + 2;
			}else if(_s.displayType == FWDEVPlayer.AFTER_PARENT){
				_s.sW = _s.stageContainer.offsetWidth;
				_s.sH = _s.stageContainer.offsetHeight;
			}else{
				_s.stageContainer.style.width = "100%";
				if(_s.stageContainer.offsetWidth > _s.maxWidth){
					_s.stageContainer.style.width = _s.maxWidth + "px";
				}
				_s.sW = _s.stageContainer.offsetWidth;
				if(_s.autoScale_bl){
					_s.sH = parseInt(_s.maxHeight * (_s.sW/_s.maxWidth));
				}else{
					_s.sH = _s.maxHeight;
				}
				
				if(_s.useWithoutVideoScreen_bl){
					_s.sH = _s.controllerHeight;
				}
				_s.stageContainer.style.height = _s.sH + "px";
			}

			if(_s.isMin && !_s.isFullScreen_bl){
				_s.sW = Math.min(_s.stickyOnScrollWidth - 10, _s.ws.w - 10)
				_s.sH = parseInt(_s.stickyOnScrollHeight * (_s.sW/_s.stickyOnScrollWidth));
				_s.sH = _s.sH;
			}

			_s.tempVidStageWidth = _s.sW;
			_s.tempVidStageHeight = _s.sH;
			_s.main_do.setWidth(_s.sW);
			_s.main_do.setHeight(_s.sH);
			
			if(_s.fillEntireVideoScreen_bl  && (_s.videoType_str == FWDEVPlayer.VIDEO || _s.videoType_str == FWDEVPlayer.HLS_JS) && !_s.isFullScreen_bl){
				if(_s.videoScreen_do && _s.videoScreen_do.video_el && _s.videoScreen_do.video_el.videoWidth != 0){
					var originalW = _s.videoScreen_do.video_el.videoWidth;
					var originalH = _s.videoScreen_do.video_el.videoHeight
					var scaleX = _s.sW/originalW;
					var scaleY = _s.sH/originalH;
					
					var totalScale = 1;
					if(scaleX > scaleY){
						totalScale = scaleX;
					}else if(scaleX < scaleY){
						totalScale = scaleY;
					}
					
					var finalW = Math.round(originalW * totalScale) + 2;
					var finalH = Math.round(originalH * totalScale) + 2;
					var finalX = Math.round((_s.sW - finalW)/2) - 1;
					var finalY = Math.round((_s.sH - finalH)/2) - 1;
					
					_s.videoScreen_do.resizeAndPosition(finalW, finalH, finalX, finalY)

					if(_s.main_do.alpha == 0){
						FWDAnimation.to(_s.main_do, 3, {alpha:1});
					}
				}
			}else if(_s.audioScreen_do && _s.videoType_str == FWDEVPlayer.MP3){
				_s.audioScreen_do.resizeAndPosition(_s.sW, _s.sH);
				_s.audioScreen_do.setX(0);
				_s.audioScreen_do.setY(0);
			}else if(_s.videoScreen_do && (_s.videoType_str == FWDEVPlayer.VIDEO || _s.videoType_str == FWDEVPlayer.HLS_JS || _s.videoType_str == FWDEVPlayer.DASH)){
				_s.videoScreen_do.resizeAndPosition(_s.sW, _s.sH);
				_s.videoScreen_do.setX(0);
				_s.videoScreen_do.setY(0);
			}
			
			if(_s.popw_do && _s.popw_do.isShowed_bl) _s.popw_do.positionAndResize();
		
			if(_s.ytb_do && _s.videoType_str == FWDEVPlayer.YOUTUBE){
				_s.ytb_do.setWidth(_s.sW);
				_s.ytb_do.setHeight(_s.sH);
			}
			
			if(_s.vimeo_do && _s.videoType_str == FWDEVPlayer.VIMEO) _s.vimeo_do.resizeAndPosition();
			
			_s.positionAdsImage();
			
			if(_s.logo_do) _s.logo_do.positionAndResize();
		
			if(_s.controller_do) _s.controller_do.resizeAndPosition();
		
			if(_s.ytb_do && _s.ytb_do.ytb && _s.videoType_str == FWDEVPlayer.YOUTUBE){
				_s.ytb_do.resizeAndPosition();
			}
			
			if(_s.isIMA && _s.IMA) _s.IMA.resizeAndPosition();
			
			if(_s.preloader_do) _s.positionPreloader();
			_s.resizeDumyHandler();
			
			if(_s.largePlayButton_do) _s.positionLargePlayButton();
			if(_s.videoPoster_do && _s.videoPoster_do.allowToShow_bl) _s.videoPoster_do.positionAndResize();
			if(_s.embedWindow_do && _s.embedWindow_do.isShowed_bl) _s.embedWindow_do.positionAndResize();
			if(_s.passWindow_do && _s.passWindow_do.isShowed_bl) _s.passWindow_do.positionAndResize();
			if(_s.lg_do && _s.lg_do.isShowed_bl) _s.lg_do.positionAndResize();
			if(_s.shareWindow_do && _s.shareWindow_do.isShowed_bl) _s.shareWindow_do.positionAndResize();
			if(_s.adsStart_do) _s.positionAds();
			if(_s.subtitle_do) _s.subtitle_do.position();
			if(_s.popupAds_do) _s.popupAds_do.position();
			if(_s.annotations_do) _s.annotations_do.position();
			
			if(_s.mainBackground_do){
				_s.mainBackground_do.setWidth(_s.ws.w);
				_s.mainBackground_do.setHeight(_s.sH);
			}
			
			if(_s.displayType == FWDEVPlayer.STICKY) _s.setStageContainerFinalHeightAndPosition(animate);

			_s.positionOnMin();
			
		};
		
		_s.resizeDumyHandler = function(){
			if(_s.dClk_do){
				if(_s.is360 && _s.videoType_str == FWDEVPlayer.YOUTUBE){
					_s.dClk_do.setWidth(0);
				}else if(_s.videoType_str == FWDEVPlayer.VIMEO && !_s._d.showDefaultControllerForVimeo_bl){
					_s.dClk_do.setWidth(_s.sW);
					_s.dClk_do.setHeight(0);
				}else{
					_s.dClk_do.setWidth(_s.sW);
					if(_s.isMobile_bl){
						_s.dClk_do.setHeight(_s.sH);
					}else{
						_s.dClk_do.setHeight(_s.sH);
					}
				}
			}
		}
		
		_s.setStageContainerFinalHeightAndPosition = function(animate){
			
			if(_s.isMin) return;
			_s.allowToResizeAndPosition_bl = true;
			clearTimeout(_s.showPlaylistWithDelayId_to);
			
			
			if(_s.horizontalPosition_str == FWDEVPlayer.LEFT){
				_s.main_do.setX(_s.offsetX);
				if(_s.opener_do){
					if(_s._d.openerAlignment_str == "right"){
						_s.opener_do.setX(Math.round(_s.sW - _s.opener_do.w + _s.offsetX));
					}else{
						_s.opener_do.setX(_s.offsetX);
					}
				}
			}else if(_s.horizontalPosition_str == FWDEVPlayer.CENTER){
				_s.main_do.setX(Math.round((_s.ws.w - _s.sW)/2));
				if(_s.opener_do){
					if(_s._d.openerAlignment_str == "right"){
						_s.opener_do.setX(parseInt((_s.ws.w - _s.sW)/2) + _s.sW - _s.opener_do.w);
					}else{
						_s.opener_do.setX(_s.main_do.x);
					}
				}
			}else if(_s.horizontalPosition_str == FWDEVPlayer.RIGHT){
				_s.main_do.setX(Math.round(_s.ws.w - _s.sW - _s.offsetX));
				if(_s.opener_do){
					if(_s._d.openerAlignment_str == "right"){
						_s.opener_do.setX(Math.round(_s.ws.w - _s.opener_do.w - _s.offsetX));
					}else{
						_s.opener_do.setX(Math.round(_s.ws.w - _s.sW - _s.offsetX));
					}
				}
			}
			
			if(animate){		
				if(FWDAnimation.isTweening(_s.stageContainer)) return;
				if(_s.opener_do) FWDAnimation.killTweensOf(_s.opener_do);
				if(_s.position_str ==  FWDEVPlayer.POSITION_TOP){
					if(_s.isShowed_bl && !_s.isShowedFirstTime_bl){
						FWDAnimation.to(_s.stageContainer, .8, {css:{top:_s.offsetY}, ease:Expo.easeInOut});
					}else{
						FWDAnimation.to(_s.stageContainer, .8, {css:{top:-_s.sH}, ease:Expo.easeInOut});
					}
					
					if(_s.isShowedFirstTime_bl){
						if(_s.opener_do) FWDAnimation.to(_s.opener_do, .8, {y:_s.sH - _s.opener_do.h, ease:Expo.easeInOut});
					}else{
						if(_s.opener_do) FWDAnimation.to(_s.opener_do, .8, {y:_s.sH, ease:Expo.easeInOut});
					}
				}else{
					if(_s.isShowed_bl && !_s.isShowedFirstTime_bl){
						FWDAnimation.to(_s.stageContainer, .8, {css:{top:_s.ws.h - _s.sH - _s.offsetY}, ease:Expo.easeInOut});
					}else{
						FWDAnimation.to(_s.stageContainer, .8, {css:{top:_s.ws.h}, ease:Expo.easeInOut, onComplete:_s.moveWheyLeft});
					}
					
					if(_s.isShowedFirstTime_bl){
						if(_s.opener_do) FWDAnimation.to(_s.opener_do, .8, {y:0, ease:Expo.easeInOut});
					}else{
						if(_s.opener_do) FWDAnimation.to(_s.opener_do, .8, {y:-_s.opener_do.h, ease:Expo.easeInOut});
					}
				}
			}else{
				if(_s.position_str ==  FWDEVPlayer.POSITION_TOP){
					if(_s.isShowed_bl && !_s.isShowedFirstTime_bl){
						_s.stageContainer.style.top = _s.offsetY + "px";
					}else{
						_s.stageContainer.style.top = -_s.sH + "px";
					}
					if(_s.isShowedFirstTime_bl){
						if(_s.opener_do) _s.opener_do.setY(_s.sH - _s.opener_do.h);
					}else{
						if(_s.opener_do) _s.opener_do.setY(_s.sH);
					}
				}else{
					if(_s.isShowed_bl && !_s.isShowedFirstTime_bl){
						_s.stageContainer.style.top = (_s.ws.h - _s.sH - _s.offsetY) + "px";
					}else{
						_s.stageContainer.style.top = _s.ws.h + "px";
					}
					
					if(_s.isShowedFirstTime_bl){
						if(_s.opener_do) _s.opener_do.setY(0);
					}else{
						if(_s.opener_do) _s.opener_do.setY(-_s.opener_do.h);
					}
				}
			}
		}
		
		
		//###############################################//
		/* Setup click screen */
		//###############################################//
		_s.setupClickScreen = function(){
			_s.dClk_do = new FWDEVPDO("div");
			_s.dClk_do.screen.className = 'fwdevp-dumy-click';
			
			if(_s.disableDoubleClickFullscreen_bl){
				_s.dClk_do.style().pointerEvents = 'none';
			}

			if(_s.displayType !=  FWDEVPlayer.BACKGROUND_VIDEO){
				if(_s.hasPointerEvent_bl){
					_s.dClk_do.screen.addEventListener("pointerdown", _s.playPauseDownHandler);
					_s.dClk_do.screen.addEventListener("pointerup", _s.playPauseClickHandler);
					_s.dClk_do.screen.addEventListener("pointermove", _s.playPauseMoveHandler);
				}else{	
					if(!_s.isMobile_bl){
						_s.dClk_do.screen.addEventListener("mousedown", _s.playPauseDownHandler);
						_s.dClk_do.screen.addEventListener("mouseup", _s.playPauseClickHandler);
						_s.dClk_do.screen.addEventListener("mousemove", _s.playPauseMoveHandler);
					}else{
						if(_s.inIGPGrid){
							_s.dClk_do.screen.addEventListener("touchend", _s.playPauseClickHandler);
						}else{
							_s.dClk_do.screen.addEventListener("click", _s.playPauseClickHandler);
						}
					}
				}
			}
		
			_s.hideClickScreen();
			_s.main_do.addChild(_s.dClk_do);
		};
		
		_s.playPauseDownHandler = function(e){
			
			_s.isClickHandlerMoved_bl = false;
			var viewportMouseCoordinates = FWDEVPUtils.getViewportMouseCoordinates(e);
			_s.firstDommyTapX = viewportMouseCoordinates.screenX;
			_s.firstDommyTapY = viewportMouseCoordinates.screenY;
			if(_s.is360) _s.dClk_do.style().cursor = 'url(' + _s._d.grabPath_str + '), default';
		}
		
		_s.playPauseMoveHandler = function(e){
			if(window['FWDIGP'] && (FWDIGP.isDragged_bl || FWDIGP.isZoomed)) return;
			var viewportMouseCoordinates = FWDEVPUtils.getViewportMouseCoordinates(e);
			var dx;
			var dy;
			
			if(e.touches && e.touches.length != 1) return;
			dx = Math.abs(viewportMouseCoordinates.screenX - _s.firstDommyTapX);   
			dy = Math.abs(viewportMouseCoordinates.screenY - _s.firstDommyTapY); 
			
			if(_s.isMobile_bl && (dx > 10 || dy > 10)){
				_s.isClickHandlerMoved_bl = true;
			}else if(!_s.isMobile_bl && (dx > 2 || dy > 2)){
				_s.isClickHandlerMoved_bl = true;
			}
		}
		
		_s.playPauseClickHandler = function(e){
			if(window['FWDIGP'] && (FWDIGP.isDragged_bl || FWDIGP.isZoomed)) return;
			if(e.button == 2) return;
			if(_s.is360) _s.dClk_do.style().cursor = 'url(' + _s._d.handPath_str + '), default';
			if(_s.isClickHandlerMoved_bl) return;
			
			if(_s.isAdd_bl){	
				if(_s._d.adsPageToOpenURL_str && _s._d.adsPageToOpenURL_str != "none" && !_s.skipOnDb_bl){
					if(_s.ClickTracking) _s.executeVastEvent(_s.ClickTracking);
					window.open(_s._d.adsPageToOpenURL_str, _s._d.adsPageToOpenTarget_str);
					_s.pause();
				}
				return;
			}

			if(_s.disableClick_bl) return;
			_s.firstTapPlaying_bl = _s.isPlaying_bl;
			
			FWDEVPlayer.keyboardCurInstance = _s;
			
			if(_s.controller_do && _s.controller_do.mainHolder_do.y != 0 && _s.isMobile_bl) return;
			if(!_s.isMobile_bl){
				if(FWDEVPlayer.videoStartBehaviour == FWDEVPlayer.PAUSE_ALL_VIDEOS){
					FWDEVPlayer.pauseAllVideos(_s);
				}else if(FWDEVPlayer.videoStartBehaviour == FWDEVPlayer.STOP_ALL_VIDEOS){
					FWDEVPlayer.stopAllVideos(_s);
				}
			}
			
			if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
				_s.togglePlayPause();
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE){
				_s.ytb_do.togglePlayPause();
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO){
				_s.vimeo_do.togglePlayPause();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				_s.audioScreen_do.togglePlayPause();
			}else{
				if(_s.videoScreen_do) _s.videoScreen_do.togglePlayPause();
			}
		};
		
		_s.showClickScreen = function(){
			_s.dClk_do.setVisible(true);
			if(_s.isAdd_bl && _s._d.adsPageToOpenURL_str && _s._d.adsPageToOpenURL_str != "none"){
				_s.dClk_do.setButtonMode(true);
			}else{	
				if(_s.is360){
					_s.dClk_do.style().cursor = 'url(' + _s._d.handPath_str + '), default';
				}else{
					_s.dClk_do.setButtonMode(false);
				}
			}
			_s.dispatchEvent(FWDEVPlayer.SHOW_CURSOR);
		};
		
		_s.hideClickScreen = function(){
			_s.dClk_do.setVisible(false);
		};
		
		_s.disableClick = function(){
			_s.disableClick_bl = true;
			clearTimeout(_s.disableClickId_to);
			_s.disableClickId_to =  setTimeout(function(){
				_s.disableClick_bl = false;
			}, 500);
		};

		
		//########################################//
		/* add double click and tap support */
		//########################################//
		_s.addDoubleClickSupport = function(){
			if(_s.hasPointerEvent_bl){
				_s.dClk_do.screen.addEventListener("pointerdown", _s.onFirstDown);
			}else{
				if(!_s.isMobile_bl){
					_s.dClk_do.screen.addEventListener("mousedown", _s.onFirstDown);
					if(FWDEVPUtils.isIEWebKit) _s.dClk_do.screen.addEventListener("dblclick", _s.onSecondDown);
				}
				_s.dClk_do.screen.addEventListener("touchstart", _s.onFirstDown);
			}
			_s.setupVisualization();
		};
		
		_s.onFirstDown = function(e){
			if(e.button == 2) return;
			if(_s.isFullscreen_bl && e.preventDefault) e.preventDefault();
			var viewportMouseCoordinates = FWDEVPUtils.getViewportMouseCoordinates(e);
			_s.firstTapX = viewportMouseCoordinates.screenX - _s.main_do.getGlobalX();
			_s.firstTapY = viewportMouseCoordinates.screenY - _s.main_do.getGlobalY();
			
			_s.firstTapPlaying_bl = _s.isPlaying_bl;
			
			if(FWDEVPUtils.isIEWebKit) return;
			if(_s.hasPointerEvent_bl){
				_s.dClk_do.screen.removeEventListener("pointerdown", _s.onFirstDown);
				_s.dClk_do.screen.addEventListener("pointerdown", _s.onSecondDown);
			}else{
				if(!_s.isMobile_bl){
					_s.dClk_do.screen.addEventListener("mousedown", _s.onSecondDown);
					_s.dClk_do.screen.removeEventListener("mousedown", _s.onFirstDown);
				}
				_s.dClk_do.screen.addEventListener("touchstart", _s.onSecondDown);
				_s.dClk_do.screen.removeEventListener("touchstart", _s.onFirstDown);
			}
			clearTimeout(_s.secondTapId_to);
			_s.secondTapId_to = setTimeout(_s.doubleTapExpired, 500);
		};
		
		_s.doubleTapExpired = function(){
			clearTimeout(_s.secondTapId_to);
			if(_s.hasPointerEvent_bl){
				_s.dClk_do.screen.removeEventListener("pointerdown", _s.onSecondDown);
				_s.dClk_do.screen.addEventListener("pointerdown", _s.onFirstDown);
			}else{
				_s.dClk_do.screen.removeEventListener("touchstart", _s.onSecondDown);
				_s.dClk_do.screen.addEventListener("touchstart", _s.onFirstDown);
				if(!_s.isMobile_bl){
					_s.dClk_do.screen.removeEventListener("mousedown", _s.onSecondDown);
					_s.dClk_do.screen.addEventListener("mousedown", _s.onFirstDown);
				}
			}
		};
		
		_s.onSecondDown = function(e){
			if(e.preventDefault) e.preventDefault();
			var viewportMouseCoordinates = FWDEVPUtils.getViewportMouseCoordinates(e);
			var dx;
			var dy;
			
			if(FWDEVPUtils.isIEWebKit) _s.firstTapPlaying_bl = _s.isPlaying_bl;
			if(e.touches && e.touches.length != 1) return;
			dx = Math.abs((viewportMouseCoordinates.screenX - _s.main_do.getGlobalX()) - _s.firstTapX);
			dy = Math.abs(viewportMouseCoordinates.screenY -  _s.main_do.getGlobalY() - _s.firstTapY); 
		
			if((dx > 10 || dy > 10)) return;
				
			if(_s.firstTapX < _s.tempVidStageWidth * 0.33){
				if(!_s.isPlaying_bl){
					_s.skipOnDb_bl = true;
					_s.rewind(10);
					_s.addVisualization('left');
					setTimeout(function(){
						if(!_s.isPlaying_bl) _s.play();
					}, 200);
					setTimeout(function(){
						_s.skipOnDb_bl = false;
					}, 500);
				} 
			}else if(_s.firstTapX > _s.tempVidStageWidth * 0.67){
					if(!_s.isPlaying_bl){
						_s.skipOnDb_bl = true;
						_s.rewind(-10);
						_s.addVisualization('right');
						_s.rewindId_to = setTimeout(function(){
							if(!_s.isPlaying_bl) _s.play();
						}, 200);
						setTimeout(function(){
							_s.skipOnDb_bl = false;
						}, 500);
				} 
			}else{
				_s.switchFullScreenOnDoubleClick();
				if(_s.firstTapPlaying_bl){
					_s.play();
				}else{
					_s.pause();
				}
			}
		};
		
		_s.switchFullScreenOnDoubleClick = function(e){
			_s.disableClick();
			if(!_s.isFullScreen_bl){
				_s.goFullScreen();
			}else{
				_s.goNormalScreen();
			}
		};


		//############################################//
		/* Setup double click visualization */
		//############################################//
		_s.lasPosition;
		_s.setupVisualization = function(){
			_s.mainVz_do = new FWDEVPDO('div');
			_s.mainVz_do.style().pointerEvents = 'none';
			_s.mainVz_do.style().backgroundColor = 'rgba(0,0,0,0.01)';
			_s.mainVzBackgrond_do = new FWDEVPDO('div');
			_s.mainVzBackgrond_do.style().width = '100%';
			_s.mainVzBackgrond_do.style().height = '100%';
			_s.mainVzBackgrond_do.style().backgroundColor = 'rgba(255,255,255, .15)';
			_s.mainVz_do.style().borderRadius = '100%';
			_s.mainVz_do.addChild(_s.mainVzBackgrond_do);

			_s.circle_do = new FWDEVPDO('div',0, 0, true);
			_s.circle_do.style().backgroundColor = 'rgba(255,255,255, .15)';
			_s.circle_do.style().borderRadius = '100%';
			_s.mainVz_do.addChild(_s.circle_do);


			var vzImg1 = new Image();
			vzImg1.src = _s.mainFolderPath_str + _s.sknPth + 'vis.png';
			_s.vzImg1_do = new FWDEVPDO('img', 0, 0, true);
			_s.vzImg1_do.setScreen(vzImg1);
			_s.vzImg1_do.setWidth(17);
			_s.vzImg1_do.setHeight(23);
			_s.mainVz_do.addChild(_s.vzImg1_do);

			var vzImg2 = new Image();
			vzImg2.src = _s.mainFolderPath_str + _s.sknPth + 'vis.png';
			_s.vzImg2_do = new FWDEVPDO('img', 0, 0, true);
			_s.vzImg2_do.setScreen(vzImg2);
			_s.vzImg2_do.setWidth(17);
			_s.vzImg2_do.setHeight(23);
			_s.mainVz_do.addChild(_s.vzImg2_do);

			var vzImg3 = new Image();
			vzImg3.src = _s.mainFolderPath_str + _s.sknPth + 'vis.png';
			_s.vzImg3_do = new FWDEVPDO('img', 0 ,0, true);
			_s.vzImg3_do.setScreen(vzImg3);
			_s.vzImg3_do.setWidth(17);
			_s.vzImg3_do.setHeight(23);
			_s.mainVz_do.addChild(_s.vzImg3_do);
		}

		_s.addVisualization = function(pos){
			clearTimeout(_s.vizFinisedId_to);
			clearTimeout(_s.vizFinished2Id_to);
			var w = Math.round(_s.tempVidStageWidth/2);
			var h = Math.round(_s.tempVidStageHeight * 1.5);

			FWDAnimation.killTweensOf(_s.mainVzBackgrond_do);
			if(_s.lasPosition != pos) _s.mainVzBackgrond_do.setAlpha(0);
			FWDAnimation.to(_s.mainVzBackgrond_do, .4, {alpha:1});

			_s.mainVz_do.setVisible(true);
			_s.mainVz_do.setWidth(w);
			_s.mainVz_do.setHeight(h);
			_s.mainVz_do.setY((_s.tempVidStageHeight - h)/2);
			var offsetY = Math.abs(_s.mainVz_do.y);
			if(_s.controller_do && _s.controller_do.isShowed_bl) offsetY -= _s.controller_do.sH/2;
			if(!_s.main_do.contains(_s.mainVz_do)){
				if(_s.controller_do){
					_s.main_do.addChildAt(_s.mainVz_do, _s.main_do.getChildIndex(_s.controller_do) - 1);
				}else{
					_s.main_do.addChild(_s.mainVz_do);
				}
			} 
			if(pos == 'right'){
				_s.mainVz_do.style().borderRadius = '100% 0% 0% 100%';
				_s.mainVz_do.setX(w);
				_s.vzImg1_do.setRotation(0);
				_s.vzImg2_do.setRotation(0);
				_s.vzImg3_do.setRotation(0);
			}else{
				_s.mainVz_do.style().borderRadius = '0% 100% 100% 0%';
				_s.mainVz_do.setX(0);
				_s.vzImg1_do.setRotation(180);
				_s.vzImg2_do.setRotation(180);
				_s.vzImg3_do.setRotation(180);
			}

			_s.vzImg1_do.setX(Math.round(w - (_s.vzImg1_do.w * 3))/2);
			_s.vzImg1_do.setY(Math.round(offsetY + (_s.tempVidStageHeight - _s.vzImg1_do.h)/2));
			_s.vzImg2_do.setX(_s.vzImg1_do.x + _s.vzImg1_do.w);
			_s.vzImg2_do.setY(_s.vzImg1_do.y);
			_s.vzImg3_do.setX(_s.vzImg2_do.x + _s.vzImg2_do.w);
			_s.vzImg3_do.setY(_s.vzImg2_do.y);

			
			FWDAnimation.killTweensOf(_s.vzImg1_do);
			FWDAnimation.killTweensOf(_s.vzImg2_do);
			FWDAnimation.killTweensOf(_s.vzImg3_do);
			_s.vzImg1_do.setAlpha(0);
			_s.vzImg2_do.setAlpha(0);
			_s.vzImg3_do.setAlpha(0);
			if(pos == 'right'){
				FWDAnimation.to(_s.vzImg1_do, .4, {alpha:1});
				FWDAnimation.to(_s.vzImg1_do, .4, {alpha:0, delay:.3});
				FWDAnimation.to(_s.vzImg2_do, .4, {alpha:1, delay:.3});
				FWDAnimation.to(_s.vzImg2_do, .4, {alpha:0, delay:.6});
				FWDAnimation.to(_s.vzImg3_do, .4, {alpha:1, delay:.6});
				FWDAnimation.to(_s.vzImg3_do, .4, {alpha:0, delay:.9});
			}else{
				FWDAnimation.to(_s.vzImg3_do, .4, {alpha:1});
				FWDAnimation.to(_s.vzImg3_do, .4, {alpha:0, delay:.3});
				FWDAnimation.to(_s.vzImg2_do, .4, {alpha:1, delay:.3});
				FWDAnimation.to(_s.vzImg2_do, .4, {alpha:0, delay:.6});
				FWDAnimation.to(_s.vzImg1_do, .4, {alpha:1, delay:.6});
				FWDAnimation.to(_s.vzImg1_do, .4, {alpha:0, delay:.9});
			}

			FWDAnimation.killTweensOf(_s.circle_do);
			_s.circle_do.setAlpha(1);
			_s.circle_do.setScale2(1);
			_s.circle_do.setWidth(w);
			_s.circle_do.setHeight(w);
			_s.circle_do.setScale2(0);
			_s.circle_do.setX(_s.firstTapX - _s.mainVz_do.x - _s.circle_do.w/2);
			_s.circle_do.setY(_s.firstTapY + offsetY - _s.circle_do.w/2);
			FWDAnimation.to(_s.circle_do, .8, {scale:2, ease:Expo.easeInOut});

			_s.vizFinisedId_to = setTimeout(function(){
				FWDAnimation.to(_s.mainVzBackgrond_do, .4, {alpha:0});
				FWDAnimation.to(_s.circle_do, .4, {alpha:0});
				_s.vizFinished2Id_to = setTimeout(function(){
					_s.mainVz_do.setVisible(false);
				}, 400)
			}, 800);

			_s.lasPosition = pos;
		}

		_s.stopVisualization =  function(){
			if(!_s.mainVz_do) return;
			clearTimeout(_s.vizFinisedId_to);
			clearTimeout(_s.vizFinished2Id_to);
			_s.mainVz_do.setVisible(false);
		}
		

		//############################################//
		/* Setup Vimeo API */
		//############################################//
		_s.setupVimeoAPI = function(){
			if(_s.vimeo_do) return;
			_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_LOAD);
			if(typeof Vimeo != "undefined" && Vimeo.Player){
				_s.setupVimeoPlayer();
				return;
			}else{
				if(FWDEVPlayer.isVimeoAPILoadedOnce_bl){
					_s.keepCheckingVimeoAPI_int =  setInterval(function(){
						if(typeof Vimeo != "undefined" && Vimeo && Vimeo.Player){
							if(_s.videoSourcePath_str.indexOf("vimeo.") == -1) clearInterval(_s.keepCheckingVimeoAPI_int);
							clearInterval(_s.keepCheckingVimeoAPI_int);
							_s.setupVimeoPlayer();
						}
					}, 50);
					return;
				}
				
				var tag = document.createElement("script");
				tag.src = "https://player.vimeo.com/api/player.js";
				var firstScriptTag = document.getElementsByTagName("script")[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			
				tag.onload = function(){
					_s.keepCheckingVimeoAPI_int = setInterval(function(){
						if(typeof Vimeo != "undefined" && Vimeo && Vimeo.Player){
							clearInterval(_s.keepCheckingVimeoAPI_int);
							_s.setupVimeoPlayer();
						}
					}, 50);
					FWDEVPlayer.isVimeoAPILoadedOnce_bl = true;
				}
										
				tag.onerror = function(){
					setTimeout(function(){
						_s.main_do.addChild(_s.info_do);
						var error = "Error loading Vimeo API";
						_s.displayError(error);
					}, 500);
					return;
				}
				if(_s.largePlayButton_do) _s.hideLargePlayButton();
			}
		};
		
		
		//############################################//
		/* Setup Vimeo player */
		//############################################//
		_s.isVimeoReady_bl = false;
		_s.setupVimeoPlayer = function(){
			if(_s.vimeo_do) return;
			FWDEVPVimeoScreen.setPrototype();
			_s.vimeo_do = new FWDEVPVimeoScreen(_s, _s._d.volume);
		
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.ERROR, _s.videoScreenErrorHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.READY, _s.vimeoReadyHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.STOP, _s.videoScreenStopHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.SAFE_TO_SCRUBB, _s.videoScreenSafeToScrubbHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.PLAY, _s.videoScreenPlayHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.PAUSE, _s.videoScreenPauseHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.UPDATE, _s.videoScreenUpdateHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.UPDATE_TIME, _s.videoScreenUpdateTimeHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.LOAD_PROGRESS, _s.videoScreenLoadProgressHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.PLAY_COMPLETE, _s.videoScreenPlayCompleteHandler);
			_s.vimeo_do.addListener(FWDEVPVimeoScreen.UPDATE_SUBTITLE, _s.videoScreenUpdateSubtitleHandler);

			_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_DONE);
		};
		
		_s.vimeoReadyHandler = function(e){
			_s.isVimeoReady_bl = true;
			clearInterval(_s.hidePreloaderId_to);
			if(_s.vimeo_do.iFrame_do) _s.vimeo_do.iFrame_do.screen.style.left = "0px";
			_s.setSource(_s.videoSourcePath_str, true);
			if(_s.preloader_do){
				_s.preloader_do.hide(false);
				_s.preloader_do.stopPreloader();
			}
		};		
		

		//############################################//
		/* Setup youtube player */
		//############################################//
		_s.setupYoutubeAPI = function(){
			if(_s.ytb_do) return;
			
			_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_LOAD);
			if(typeof YT != "undefined" && YT.Player || FWDEVPlayer.isYoutubeAPILoadedOnce_bl){
				_s.setupYoutubePlayer();
				return;
			}else{
				if(FWDEVPlayer.isYoutubeAPILoadedOnce_bl){
					_s.keepCheckingYoutubeAPI_int =  setInterval(function(){
						if(typeof YT != "undefined" && YT && YT.Player){
							if(_s.videoSourcePath_str.indexOf("youtube.") == -1) clearInterval(_s.keepCheckingYoutubeAPI_int);
							clearInterval(_s.keepCheckingYoutubeAPI_int);
							_s.setupYoutubePlayer();
						}
					}, 50);
					return;
				}
				
				var tag = document.createElement("script");
				tag.src = "https://www.youtube.com/iframe_api";
				var firstScriptTag = document.getElementsByTagName("script")[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			
				tag.onload = function(){
					_s.checkIfYoutubePlayerIsReadyId_int = setInterval(function(){
						if(YT && YT.Player){
							clearInterval(_s.checkIfYoutubePlayerIsReadyId_int);
							_s.setupYoutubePlayer();
						}
					}, 50);
				}
				
				tag.onerror = function(){
					setTimeout(function(){
						_s.main_do.addChild(_s.info_do);
						_s.info_do.allowToRemove_bl = false;
						var error = "Error loading Youtube API";
						_s.displayError(error);
					}, 500);
					return;
				}
			}
		};
		
		_s.setupYoutubePlayer = function(){
			if(_s.ytb_do) return;
			FWDEVPYoutubeScreen.setPrototype();
			_s.ytb_do = new FWDEVPYoutubeScreen(_s, _s._d.volume);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.READY, _s.youtubeReadyHandler);
			_s.ytb_do.addListener(FWDEVPVideoScreen.ERROR, _s.videoScreenErrorHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.SAFE_TO_SCRUBB, _s.videoScreenSafeToScrubbHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.STOP, _s.videoScreenStopHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.PLAY, _s.videoScreenPlayHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.PAUSE, _s.videoScreenPauseHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.UPDATE, _s.videoScreenUpdateHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.UPDATE_TIME, _s.videoScreenUpdateTimeHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.LOAD_PROGRESS, _s.videoScreenLoadProgressHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.PLAY_COMPLETE, _s.videoScreenPlayCompleteHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.CUED, _s.youtubeScreenCuedHandler);
			_s.ytb_do.addListener(FWDEVPYoutubeScreen.QUALITY_CHANGE, _s.youtubeScreenQualityChangeHandler);
			_s.ytb_do.addListener(FWDEVPVideoScreen.UPDATE_SUBTITLE, _s.videoScreenUpdateSubtitleHandler);
			
			FWDEVPlayer.isYoutubeAPILoadedOnce_bl = true;
			if(!_s.isMobile_bl) _s.ytb_do.showDisable();
			clearTimeout(_s.ytb_do);
			 _s.dispatchEvent(FWDEVPlayer.FRAMEWORK_DONE);
		};
		
		_s.youtubeReadyHandler = function(e){
		
			_s.isYoutubeReady_bl = true;
			if(_s.videoType_str != FWDEVPlayer.YOUTUBE) return;
		
			if(_s.ytb_do.hasBeenCreatedOnce_bl){
				if(_s.videoSourcePath_str.indexOf(".") != -1) return;
				if(!_s.isMobile_bl){
					_s.setPosterSource(_s.posterPath_str);
				}else{
					_s.setPosterSource(undefined);
					_s.videoPoster_do.hide();
				}
				if(_s.videoSourcePath_str.indexOf(".") == -1){
					_s.setSource(_s.videoSourcePath_str, true, _s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"]);
				}
				return;
			}
			
			if(_s.isMobile_bl){
				setTimeout(function(){
					try{
						_s.ytb_do.ytb.a.style.left = "0px";
					}catch(e){}
				}, 500);
			}
			
			_s.setSource(_s.videoSourcePath_str, true, _s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"]);
			
			if(_s.preloader_do){
				_s.preloader_do.hide(false);
				_s.preloader_do.stopPreloader();
			}
		};
		
		_s.youtubeScreenCuedHandler = function(){
			if(_s.main_do) if(_s.main_do.contains(_s.info_do)) _s.main_do.removeChild(_s.info_do);
		};
		
		_s.youtubeScreenQualityChangeHandler = function(e){
			if(_s.videoType_str == FWDEVPlayer.VIDEO) _s.curDurration = _s.videoScreen_do.curDuration;
			if(_s.controller_do) _s.controller_do.updateQuality(e.levels, e.qualityLevel);
		};
		

		//#############################################//
		/* setup context menu */
		//#############################################//
		_s.setupContextMenu = function(){
			FWDEVPContextMenu.setPrototype();
			_s.customContextMenu_do = new FWDEVPContextMenu(_s, _s._d);
		};
		

		//###########################################//
		/* setup opener */
		//###########################################//
		_s.setupOpener = function(){
			
			FWDEVPOpener.setPrototype();
			_s.opener_do = new FWDEVPOpener(_s, _s._d, _s.position_str, _s.isShowed_bl);
			_s.opener_do.style().zIndex = "99999999994";
			
			_s.opener_do.setX(-1000);
			if(_s.isShowed_bl){
				_s.opener_do.showCloseButton();
			}else{
				_s.opener_do.showOpenButton();
			}
		
			_s.opener_do.addListener(FWDEVPOpener.SHOW, _s.openerShowHandler);
			_s.opener_do.addListener(FWDEVPOpener.HIDE, _s.openerHideHandler);
			_s.opener_do.addListener(FWDEVPOpener.PLAY, _s.controllerOnPlayHandler);
			_s.opener_do.addListener(FWDEVPOpener.PAUSE, _s.controllerOnPauseHandler);
			_s.stageContainer.appendChild(_s.opener_do.screen);
			if(_s.stickyOnScroll){
				 _s.opener_do.style().position = 'fixed';
				 document.documentElement.appendChild(_s.opener_do.screen);
			}
		};
		
		_s.openerShowHandler = function(){
			_s.showPlayer();
		};
		
		_s.openerHideHandler = function(){
			_s.hidePlayer();
		};


		//#############################################//
		/* setup RSM */
		//#############################################//
		_s.setupRSM = function(){
			if(_s._d.useResumeOnPlay_bl){
				window.addEventListener("beforeunload", function (e) {
					var test = Math.random() * 1000;
					if(_s.isPlaying_bl){
						document.cookie = "fwdevp_video_path=" + _s.videoSourcePath_str + "; expires=Thu, 18 Dec 2040 00:00:01 GMT; path=/";
						var curTime = _s.getCurrentTime();
						if(curTime.length == 5) curTime = "00:" + curTime;
						document.cookie = "fwdevp_time=" + curTime + "; expires=Thu, 18 Dec 2040 00:00:01 GMT; path=/";	
					}
				});
			}
		};
		

		//#############################################//
		/* setup data */
		//#############################################//
		_s.setupData = function(){
			FWDEVPData.setPrototype();
			_s._d = new FWDEVPData(_s.props, _s.rootElement_el, _s);
			
			if(_s.mainBackground_do) _s.mainBackground_do.style().background = "url('" + _s.mainBackgroundImagePath_str + "')";
			_s._d.addListener(FWDEVPData.VAST_LOADING, _s.vastLoading);
			_s._d.addListener(FWDEVPData.VAST_LOADED_DONE, _s.vastLoadedDone);
			_s._d.addListener(FWDEVPData.PRELOADER_LOAD_DONE, _s.onPreloaderLoadDone);
			_s._d.addListener(FWDEVPData.LOAD_ERROR, _s.dataLoadError);
			_s._d.addListener(FWDEVPData.SKIN_PROGRESS, _s.dataSkinProgressHandler);
			_s._d.addListener(FWDEVPData.SKIN_LOAD_COMPLETE, _s.dataSkinLoadComplete);
			_s._d.addListener(FWDEVPData.IMA_READY, _s.dataImaReady);
			_s._d.addListener(FWDEVPData.IMA_ERROR, _s.dataImaError);
		};
		
		_s.vastLoading = function(){
			_s.isVastLoading_bl = true;
			_s.preloader_do.show(true);
			_s.preloader_do.startPreloader();
		}
		
		_s.vastLoadedDone = function(){
			_s.isAdd_bl = false
			_s.isVastLoading_bl = false;
			_s.updateAds(0, true);
			_s.preloader_do.hide(true);
			_s.dispatchEvent(FWDEVPlayer.VAST_LOADED_DONE);
		}
		
		_s.onPreloaderLoadDone = function(){
			_s.setupPreloader();
			if(!_s.isMobile_bl) _s.setupContextMenu();
			if(_s.displayType == FWDEVPlayer.BACKGROUND_VIDEO){
				_s._d.useChromeless_bl = true;
				_s._d.autoPlay_bl = true;
				_s._d.loop_bl = true;
				_s._d.fillEntireVideoScreen_bl = _s.fillEntireVideoScreen_bl = true;
			}else{
				_s.fillEntireVideoScreen_bl = _s._d.fillEntireVideoScreen_bl;
			}
		
			_s.resizeHandler();
		};
		
		_s.dataLoadError = function(e, text){
			_s.main_do.addChild(_s.info_do);
			_s.info_do.showText(e.text);
			if(_s.preloader_do){
				_s.preloader_do.hide(false);
				_s.preloader_do.stopPreloader();
			}
			_s.resizeHandler();
			_s.dispatchEvent(FWDEVPlayer.ERROR, {error:e.text});
		};
		
		
		_s.dataSkinProgressHandler = function(e){};
		
		_s.dataSkinLoadComplete = function(){
			window.removeEventListener("scroll", _s.onScrollHandler);
			_s.volume = _s._d.volume;
			if(_s.displayType == FWDEVPlayer.FULL_SCREEN  && !FWDEVPUtils.hasFullScreen){
				_s._d.showFullScreenButton_bl = false;
			}
			
			clearInterval(_s.hidePreloaderId_to);
			_s.hidePreloaderId_to = setTimeout(function(){
				if(_s.preloader_do && !_s.isVastLoading_bl){
					_s.preloader_do.hide(false);
					_s.preloader_do.stopPreloader();
				}
			}, 500);
			
			if(_s.useWithoutVideoScreen_bl){
				_s._d.showFullScreenButton_bl = false;
				_s._d.showDownloadVideoButton_bl = false;
				_s._d.showSubtitleButton_bl = false;
				_s._d.showEmbedButton_bl = false;
				_s._d.showYoutubeQualityButton_bl = false;
				_s._d.showShareButton_bl = false;
				_s._d.showPlaybackRateButton_bl = false;
				_s._d.controllerHideDelay = 10000000;
			}

			_s.setupNormalVideoPlayer();
			
			_s.animate_bl = _s._d.animate_bl;

			if((_s._d.showOpener_bl && _s.displayType == FWDEVPlayer.STICKY)
				|| (_s._d.stickyOnScrollShowOpener_bl && _s.stickyOnScroll)){
				_s.setupOpener();
			} 
		
			if(_s._d.useVectorIcons_bl){
				_s.checkFinalButtonSizezId_int = setInterval(function(){
					if(_s.controller_do){
						if(_s.controller_do.playPauseButton_do.w != 0){
							setTimeout(function(){
								_s.isShowedFirstTime_bl = false;
								_s.resizeHandler(_s.animate_bl);
								clearInterval(_s.checkFinalButtonSizezId_int);
							}, 100);
						}
					}else{
						if(_s.controller_do) clearInterval(_s.checkFinalButtonSizezId_int);
					}
				}, 50);
			}else{
				setTimeout(function(){
					_s.isShowedFirstTime_bl = false;
					_s.resizeHandler(_s.animate_bl);
				}, 50);
			}
		};
		
		_s.dataImaReady = function(){
			if(_s.isIMA){
				var dSrc = _s._d.videosSource_ar[_s._d.startAtVideoSource];
				_s.setSource(dSrc["source"], true, _s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"], dSrc["source2"]);
			} 
		}
		
		_s.dataImaError = function(){
			_s.errorImaSDK = true;
			var dSrc = _s._d.videosSource_ar[_s._d.startAtVideoSource];
			_s.setSource(dSrc["source"],false, _s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"], dSrc["source2"]);
		}
		
		_s.setupNormalVideoPlayer = function(){
			if(_s.normalVideoPlayersCreated_bl) return;
			_s.normalVideoPlayersCreated_bl = true;
			
			_s.isAPIReady_bl = true;
			_s.setupVideoScreen();
			_s.setupAudioScreen();
			_s.setupVideoPoster();
			if(_s.showPreloader_bl) _s.main_do.addChild(_s.preloader_do);	
			_s.setupSubtitle();
			_s.setupClickScreen();
			_s.setupPopupAds();
			if(!_s.disableDoubleClickFullscreen_bl) _s.addDoubleClickSupport();
			if(!_s._d.useChromeless_bl && _s._d.showController_bl) _s.setupController();
			if(!_s._d.useChromeless_bl && _s._d.showLogo_bl) _s.setupLogo();
			_s.setupHider();
			if(!_s._d.useChromeless_bl && _s._d.showController_bl && _s._d.showEmbedButton_bl) _s.setupEmbedWindow();
			if(!_s._d.useChromeless_bl && _s._d.showController_bl) _s.setupPasswordWindow();
			if(!_s._d.isLoggedIn_bl && _s._d.showController_bl) _s.setupLoginWindow();
			if(!_s._d.useChromeless_bl  && _s._d.showController_bl && _s._d.showShareButton_bl) _s.setupShareWindow();
			if(_s._d.showAopwWindow_bl) _s.setupAopw();
			if(!_s._d.useChromeless_bl && _s._d.showController_bl) _s.setupAdsStart();
			if(_s._d.hasAnnotiations_bl) _s.setupAnnotations();
			if(!_s._d.useChromeless_bl) _s.setupLargePlayPauseButton();
			if(_s._d.showChromecastButton_bl) _s.setupChormecast();
			_s.addMinOnScroll();
			_s.setupDisableClick();
			_s.setupRSM();
			_s.updateAds(0, true);

			setTimeout(function(){
				_s.dispatchEvent(FWDEVPlayer.READY);
			}, 50);
			
			if(_s.displayType == FWDEVPlayer.BACKGROUND_VIDEO && _s.isMobile_bl){
				if(_s.hasPointerEvent_bl){
					window.addEventListener("pointerdown", _s.playVideoBackgroundOnMobileOnInteraction);
				}else{
					window.addEventListener("touchstart", _s.playVideoBackgroundOnMobileOnInteraction);
				}	
			}
			
			if(_s._d.addKeyboardSupport_bl) _s.addKeyboardSupport();
			_s.resizeHandler();
		};
		
		_s.setupAopw = function(){
			
			FWDEVPOPWindow.setPrototype();
			_s.popw_do = new FWDEVPOPWindow(_s._d, _s);
		}
		
		_s.playVideoBackgroundOnMobileOnInteraction = function(){
			if(_s.hasPointerEvent_bl){
				window.removeEventListener("pointerdown", _s.playVideoBackgroundOnMobileOnInteraction);
			}else{
				window.removeEventListener("touchstart", _s.playVideoBackgroundOnMobileOnInteraction);
			}	
			_s.play();
		}

		
		//###########################################//
		/* Setup autoplay click. */
		//###########################################//
		_s.setupAPT = function(){
			if(!_s.apt && _s._d.autoPlayText && _s._d.autoPlay_bl){
				_s.removeAPT();
				_s.apt = new FWDEVPDO('div', 0, 0, true);
				_s.apt.screen.className = 'fwdevp-autoplay-text';
				_s.apt.setButtonMode(true);
				_s.apt.setInnerHTML(_s._d.autoPlayText + '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M9.4272 0.430497C9.4272 0.267667 9.33293 0.113407 9.18724 0.0448468C9.03298 -0.0322832 8.86158 -0.00657319 8.73303 0.0962667L4.93652 3.12147L9.4272 7.61215V0.430497Z" fill="black"/><path d="M11.8742 11.2702L0.733188 0.129242C0.566073 -0.0378725 0.294404 -0.0378725 0.127289 0.129242C-0.0398256 0.296357 -0.0398256 0.568026 0.127289 0.735141L2.82341 3.43212H2.57231C2.30664 3.43212 2.07525 3.5521 1.92099 3.74064C1.79244 3.88633 1.71531 4.08344 1.71531 4.28912V7.71712C1.71531 8.18847 2.10096 8.57412 2.57231 8.57412H4.56055L8.73413 11.9078C8.81126 11.9678 8.90553 12.0021 8.9998 12.0021C9.05979 12.0021 9.12835 11.985 9.18834 11.9593C9.33403 11.8907 9.4283 11.7364 9.4283 11.5736V10.037L11.2674 11.8761C11.3514 11.9601 11.4611 12.0021 11.5708 12.0021C11.6805 12.0021 11.7902 11.9601 11.8742 11.877C12.0413 11.709 12.0413 11.4382 11.8742 11.2702Z" fill="black"/></g></svg>');
				_s.main_do.addChild(_s.apt);
				
				if(_s.isMobile_bl){
					_s.apt.screen.addEventListener('touchend', _s.aptSetVolume, {passive:false});
				}else{
					_s.apt.screen.addEventListener('click', _s.aptSetVolume);
				}
				
			}
			_s.showAPT();
		}

		_s.aptSetVolume = function(e){
			if(e.preventDefault) e.preventDefault();
			_s.setVolume(_s._d.volume, true);
		}

		_s.removeAPT = function(e){
			if(e && e.preventDefault) e.preventDefault();
			if(_s.apt && _s.main_do.contains(_s.apt)) _s.main_do.removeChild(_s.apt);
			FWDAnimation.killTweensOf(_s.apt);
			_s.aptRemoved = true;
			_s.apt = null;
		}

		_s.hideAPT = function(){
			if(_s.apt) _s.apt.setX(-5000);
		}

		_s.showAPT = function(){
			if(_s.apt){
				 _s.apt.setX(0);
				 _s.apt.setScale2(0);
				 FWDAnimation.to(_s.apt, 1, {scale:1, ease:Elastic.easeInOut});
			}
		}


		//###########################################//
		/* Setup popup ads */
		//###########################################//
		_s.setupPopupAds = function(){
			FWDEVPPopupAds.setPrototype();
			_s.popupAds_do =  new FWDEVPPopupAds(_s, _s._d);
			_s.main_do.addChild(_s.popupAds_do);
		};
		

		//#############################################//
		/* setup preloader */
		//#############################################//
		_s.setupPreloader = function(){
			FWDEVPPreloader.setPrototype();
		
			_s.preloader_do = new FWDEVPPreloader(_s, 'center', 10, _s.preloaderBackgroundColor, _s.preloaderFillColor, 3, 0.8);
			_s.preloader_do.show(false);
			_s.preloader_do.startPreloader();
			
			if(_s.showPreloader_bl){
				if(_s.displayType == FWDEVPlayer.STICKY){
					document.documentElement.appendChild(_s.preloader_do.screen);
				}else{
					_s.main_do.addChild(_s.preloader_do);
				}
				
			}
		};
		
		_s.positionPreloader = function(){

			if(_s.displayType == FWDEVPlayer.STICKY){
				
				if(!_s.main_do.contains(_s.preloader_do)){
					
					_s.preloader_do.setX(Math.round((_s.ws.w - _s.preloader_do.w)/2));
					if(_s.position_str == FWDEVPlayer.POSITION_BOTTOM){
						_s.preloader_do.setY(Math.round((_s.ws.h - _s.preloader_do.h) - 10) + FWDEVPUtils.getScrollOffsets().y);
					}else{
						_s.preloader_do.setY(10);
					}
				}else{
					_s.preloader_do.setX(Math.round((_s.sW - _s.preloader_do.w)/2));
					_s.preloader_do.setY(Math.round((_s.sH - _s.preloader_do.h)/2));
				}
			}else{
				_s.preloader_do.setX(parseInt((_s.sW - _s.preloader_do.w)/2));
				_s.preloader_do.setY(parseInt((_s.sH - _s.preloader_do.h)/2));
			}
		};
		

		//##########################################//
		/* setup video poster */
		//##########################################//
		_s.setupVideoPoster = function(){
			FWDEVPPoster.setPrototype();
			_s.videoPoster_do = new FWDEVPPoster(_s, _s._d.posterBackgroundColor_str, _s._d.show, _s._d.fillEntireScreenWithPoster_bl);
			_s.main_do.addChild(_s.videoPoster_do);
		};


		//##########################################//
		/* setup chromecast */
		//##########################################//
		_s.setupChormecast = function(){
			FWDEVPCC.setPrototype();
			_s.cc = new FWDEVPCC(_s.controller_do);
		}
		
		
		//###########################################//
		/* Setup large play / pause button */
		//###########################################//
		_s.setupLargePlayPauseButton = function(){
			if(_s._d.useVectorIcons_bl){					
				var ic = _s.fontIcon + ' ' + _s.fontIcon + '-play';
				FWDEVPSimpleButton.setTransformPrototype();
				_s.largePlayButton_do = new FWDEVPSimpleButton(
						undefined, 0, 0, true, 0, 0, 0,
						"<div class='table-fwdevp-button'><span class='table-cell-fwdevp-button " + ic + "'></span></div>",
						undefined,
						"EVPLargePlayButtonNormalState",
						"EVPLargePlayButtonSelectedState"
				);
			}else{
				FWDEVPSimpleButton.setTransformPrototype();
				_s.largePlayButton_do = new FWDEVPSimpleButton(
														 _s._d.largePlayN_img, 
														 _s._d.largePlayS_str, 
														 undefined, 
														 true,
														 _s._d.useHEX,
														 _s._d.nBC,
														 _s._d.sBC,
														 undefined, undefined, undefined, undefined, true);
			}

			_s.largePlayButton_do.addListener(FWDEVPSimpleButton.MOUSE_UP, _s.largePlayButtonUpHandler);
			_s.largePlayButton_do.setOverflow("visible");
			_s.hideLargePlayButton();
			if(!_s.notShowLargePlayButton_bl) _s.main_do.addChild(_s.largePlayButton_do);
		};
		
		_s.largePlayButtonUpHandler = function(){
			if(window['FWDIGP']){
				if(FWDIGP.isCategoryChanging_bl || FWDIGP.disableOnDragOrSwipe_bl || FWDIGP.isMobileScrolling_bl){
					return;
				} 
			}
			if(_s.isIMA && _s.IMA && !_s.IMA.isReady) return;
			_s.disableClick();
			if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				if(_s.vimeo_do.hasError) return;
				_s.vimeo_do.hideDisable();
			}
			_s.hideLargePlayButton();
			_s.play();
			if(_s._d.goFullScreenOnPlay_bl) _s.goFullScreen();
		};
		
		_s.positionLargePlayButton =  function(){
			_s.largePlayButton_do.setX(parseInt((_s.sW - _s.largePlayButton_do.w)/2));
			_s.largePlayButton_do.setY(parseInt((_s.sH - _s.largePlayButton_do.h)/2));
		};

		_s.showLargePlayButton = function(dl){
			if(_s.notShowPlayBtnExternal && !_s.isFullScreen_bl) return;
			 _s.largePlayButton_do.show(dl);
			_s.dispatchEvent(FWDEVPlayer.SHOW_PLAY_BUTTON);
		}

		_s.hideLargePlayButton = function(){
			_s.largePlayButton_do.hide();
			_s.dispatchEvent(FWDEVPlayer.HIDE_PLAY_BUTTON);
		}

		
		//###########################################//
		/* Setup logo */
		//###########################################//
		_s.setupLogo = function(){
			FWDEVPLogo.setPrototype();
			_s.logo_do = new FWDEVPLogo(_s, _s._d.logoPath_str, _s._d.logoPosition_str, _s._d.logoMargins);
			_s.main_do.addChild(_s.logo_do);
		};
		

		//###########################################//
		/* Setup subtitle */
		//###########################################//
		_s.setupSubtitle = function(){
			FWDEVPSubtitle.setPrototype();
			_s.subtitle_do =  new FWDEVPSubtitle(_s, _s._d);
			_s.subtitle_do.addListener(FWDEVPSubtitle.LOAD_COMPLETE, _s.subtitleLoadComplete);
		};
		
		_s.subtitleLoadComplete = function(){
			if(_s.controller_do) _s.controller_do.enableSubtitleButton();
		};
		
		_s.loadSubtitle = function(path){
			if(!path) return;
			if(_s.isCasting){
				_s.cc.loadSubtitle();
				return;
			}
			if(path){
				_s.subtitle_do.loadSubtitle(path);
				_s.main_do.addChildAt(_s.subtitle_do, _s.main_do.getChildIndex(_s.dClk_do) - 1);
			}
		}
		
		
		//###########################################//
		/* setup controller */
		//###########################################//
		_s.setupController = function(){
			
			FWDEVPController.setPrototype();
			_s.controller_do = new FWDEVPController(_s._d, _s);
			
			_s.controller_do.addListener(FWDEVPController.CHANGE_AUDIO_TRACKS, _s.audioTracksHanlder);
			_s.controller_do.addListener(FWDEVPController.VR, _s.vrHanlder);
			_s.controller_do.addListener(FWDEVPController.REWIND, _s.rewindHandler);
			_s.controller_do.addListener(FWDEVPData.LOAD_ERROR, _s.thumbnailsPreviewLoadError);
			_s.controller_do.addListener(FWDEVPController.CHANGE_PLAYBACK_RATES, _s.changePlaybackRateHandler);
			_s.controller_do.addListener(FWDEVPController.CHANGE_SUBTITLE, _s.changeSubtitileHandler);
			_s.controller_do.addListener(FWDEVPController.PLAY, _s.controllerOnPlayHandler);
			_s.controller_do.addListener(FWDEVPController.PAUSE, _s.controllerOnPauseHandler);
			_s.controller_do.addListener(FWDEVPController.START_TO_SCRUB, _s.controllerStartToScrubbHandler);
			_s.controller_do.addListener(FWDEVPController.SCRUB, _s.controllerScrubbHandler);
			_s.controller_do.addListener(FWDEVPController.STOP_TO_SCRUB, _s.controllerStopToScrubbHandler);
			_s.controller_do.addListener(FWDEVPController.CHANGE_VOLUME, _s.controllerChangeVolumeHandler);
			_s.controller_do.addListener(FWDEVPController.DOWNLOAD_VIDEO, _s.controllerDownloadVideoHandler);
			_s.controller_do.addListener(FWDEVPController.SHARE, _s.controllerShareHandler);
			_s.controller_do.addListener(FWDEVPController.CHANGE_YOUTUBE_QUALITY, _s.controllerChangeYoutubeQualityHandler);
			_s.controller_do.addListener(FWDEVPController.FULL_SCREEN, _s.controllerFullScreenHandler);
			_s.controller_do.addListener(FWDEVPController.NORMAL_SCREEN, _s.controllerNormalScreenHandler);
			_s.controller_do.addListener(FWDEVPController.SHOW_EMBED_WINDOW, _s.showEmbedWindowHandler);
			_s.controller_do.addListener(FWDEVPController.SHOW_SUBTITLE, _s.showSubtitleHandler);
			_s.controller_do.addListener(FWDEVPController.HIDE_SUBTITLE, _s.hideSubtitleHandler);
			_s.main_do.addChild(_s.controller_do);
		};

		_s.audioTracksHanlder = function(e){
			if(_s.hlsJS){
				_s.hlsJS.audioTrack = e.id;
			}else{
				_s.videoScreen_do.setAudioTrack(e.id);
			}
		}

		_s.vrHanlder = function(){
			_s.play();
			if(!_s.videoScreen_do.vrSupport_bl){
				_s.videoScreen_do.showVrMessage();
			}else{
				_s.videoScreen_do.startVR();
			}
		}
		
		_s.rewindHandler = function(){
			_s.rewind(10);
		}
		
		_s.rewind = function(offset){
			var curTime = _s.getCurrentTime();
			if(curTime.length == 5) curTime = "00:" + curTime;
			if(curTime.length == 7) curTime = "0" + curTime;
			curTime = FWDEVPUtils.getSecondsFromString(curTime);
			curTime -= offset;
			curTime = FWDEVPUtils.formatTime(curTime);
			if(curTime.length == 5) curTime = "00:" + curTime;
			if(curTime.length == 7) curTime = "0" + curTime;
			_s.scrubbAtTime(curTime);
		}
		
		_s.thumbnailsPreviewLoadError = function(e){
			console.log(e);
		}
		
		_s.changePlaybackRateHandler = function(e){
			_s.setPlaybackRate(e.rate);
			_s.dispatchEvent(FWDEVPlayer.PLAYBACK_RATE_CHANGE, {rate:e.rate});
		}
		
		_s.changeSubtitileHandler = function(e){
			_s._d.startAtSubtitle = e.id;
			_s.controller_do.updateSubtitleButtons(_s._d.subtitles_ar, _s._d.startAtSubtitle);
			_s._d.subtitlePath_str = _s._d.subtitles_ar[_s._d.subtitles_ar.length - 1 - _s._d.startAtSubtitle]["source"];
			_s._d.startAtSubtitle = e.id;
			_s.ccSS = e.id;
		
			if(!_s.isAdd_bl) _s.loadSubtitle(_s._d.subtitlePath_str);
		}
		
		_s.controllerDownloadVideoHandler = function(){
			_s.downloadVideo();
		};
		
		_s.showSubtitleHandler = function(){
			_s.subtitle_do.isShowed_bl = true;
			_s.subtitle_do.show();
		};
		
		_s.hideSubtitleHandler = function(){
			_s.subtitle_do.isShowed_bl = false;
			_s.subtitle_do.hide();
		};
		
		_s.controllerOnPlayHandler = function(e){
			_s.play();
			if(_s._d.goFullScreenOnPlay_bl) _s.goFullScreen();
		};
		
		_s.controllerOnPauseHandler = function(e){
			_s.pause();
		};
		
		_s.controllerStartToScrubbHandler = function(e){
			if(_s.isCasting){
				_s.cc.startToScrub();
				return;
			}
			_s.startToScrub();
		};
		
		_s.controllerScrubbHandler = function(e){
			if(_s.isCasting){
				_s.cc.seek(e.percent);
				return;
			}
			_s.scrub(e.percent);
		};
		
		_s.controllerStopToScrubbHandler = function(e){
			if(_s.isCasting){
				_s.cc.stopToScrub();
				return;
			}
			_s.stopToScrub();
		};
		
		_s.controllerChangeVolumeHandler = function(e){
			_s.setVolume(e.percent, true);
		};
		
		_s.controllerShareHandler = function(e){
			_s.setVideoPlayingStateOnWindowShow();
			_s.pause();
			
			_s.shareWindow_do.show();
			if(_s.controller_do){
				_s.controller_do.shareButton_do.setSelectedState();
				_s.controller_do.shareButton_do.isDisabled_bl = true;
			}
		};
		
		_s.controllerChangeYoutubeQualityHandler = function(e){
			if(_s.videoType_str == FWDEVPlayer.HLS_JS){
				_s.curHlsLevel = Math.abs(e.id - _s.hlsLevels_ar.length + 1);
				_s.hlsJS.currentLevel = _s.curHlsLevel;
				//_s.controller_do.disableQualityButtons(_s.hlsLevels_ar[_s.curHlsLevel]);
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE){
				_s.ytb_do.setQuality(e.quality);
			}else{
				_s._d.startAtVideoSource = _s._d.videosSource_ar.length -1 - e.id;
				var dSrc = _s._d.videosSource_ar[_s._d.startAtVideoSource];
				_s.isQualityChangingError_bl = _s.isQualityChanging_bl = _s.isQualityChangingStop_bl = true;
				_s.updateAds(0);
				_s.play();
			}
		};
		
		_s.controllerFullScreenHandler = function(){
			_s.goFullScreen();
		};
		
		_s.controllerNormalScreenHandler = function(){
			_s.goNormalScreen();
		};
		
		_s.showEmbedWindowHandler = function(){
			
			_s.setVideoPlayingStateOnWindowShow();
			_s.pause();
			
			if(_s.customContextMenu_do) _s.customContextMenu_do.disable();
			_s.embedWindow_do.show();
			
			if(_s.controller_do){
				_s.controller_do.embedButton_do.setSelectedState();
				_s.controller_do.embedButton_do.isDisabled_bl = true;
			}
		};
		
		_s.setVideoPlayingStateOnWindowShow =  function(){
			if(_s.isCasting){
				_s.isVideoPlayingWhenOpenWindows_bl = _s.cc.playerState == 'PLAYING';
			}else if(_s.isIMA && _s.IMA.started){
				_s.isVideoPlayingWhenOpenWindows_bl = _s.IMA.isPlaying;
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do){
				_s.isVideoPlayingWhenOpenWindows_bl = _s.ytb_do.isPlaying_bl;
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.isVideoPlayingWhenOpenWindows_bl = _s.vimeo_do.isPlaying_bl;
			}else if(FWDEVPlayer.hasHTML5Video){
				if(_s.videoScreen_do) _s.isVideoPlayingWhenOpenWindows_bl = _s.videoScreen_do.isPlaying_bl;
			}
		}
		

		//###########################################//
		/* setup FWDEVPAudioScreen */
		//###########################################//
		_s.setupAudioScreen = function(){	
			FWDEVPAudioScreen.setPrototype();
			_s.audioScreen_do = new FWDEVPAudioScreen(_s, _s._d.volume);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.ERROR, _s.videoScreenErrorHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.SAFE_TO_SCRUBB, _s.videoScreenSafeToScrubbHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.STOP, _s.videoScreenStopHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.PLAY, _s.videoScreenPlayHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.PAUSE, _s.videoScreenPauseHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.UPDATE, _s.videoScreenUpdateHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.UPDATE_TIME, _s.videoScreenUpdateTimeHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.LOAD_PROGRESS, _s.videoScreenLoadProgressHandler);
			_s.audioScreen_do.addListener(FWDEVPVideoScreen.START_TO_BUFFER, _s.videoScreenStartToBuferHandler);
			_s.audioScreen_do.addListener(FWDEVPVideoScreen.STOP_TO_BUFFER, _s.videoScreenStopToBuferHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.PLAY_COMPLETE, _s.videoScreenPlayCompleteHandler);
			_s.audioScreen_do.addListener(FWDEVPAudioScreen.UPDATE_SUBTITLE, _s.videoScreenUpdateSubtitleHandler);
			_s.main_do.addChild(_s.audioScreen_do);
		};

		
		//###########################################//
		/* setup FWDEVPVideoScreen */
		//###########################################//
		_s.setupVideoScreen = function(){
			
			FWDEVPVideoScreen.setPrototype();
			_s.videoScreen_do = new FWDEVPVideoScreen(_s, _s.backgroundColor_str, _s._d.volume);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.ERROR, _s.videoScreenErrorHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.SAFE_TO_SCRUBB, _s.videoScreenSafeToScrubbHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.STOP, _s.videoScreenStopHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.START, _s.videoScreenStartHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.PLAY, _s.videoScreenPlayHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.PAUSE, _s.videoScreenPauseHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.UPDATE, _s.videoScreenUpdateHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.UPDATE_TIME, _s.videoScreenUpdateTimeHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.UPDATE_SUBTITLE, _s.videoScreenUpdateSubtitleHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.LOAD_PROGRESS, _s.videoScreenLoadProgressHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.START_TO_BUFFER, _s.videoScreenStartToBuferHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.STOP_TO_BUFFER, _s.videoScreenStopToBuferHandler);
			_s.videoScreen_do.addListener(FWDEVPVideoScreen.PLAY_COMPLETE, _s.videoScreenPlayCompleteHandler);
			_s.main_do.addChild(_s.videoScreen_do);
		};
		
		_s.videoScreenStartHandler = function(){
			_s.callVastEvent("start");
			_s.executeVastEvent(_s.Impression);
		}
		
		_s.checkSecondSource = function(){
			//Check second source.
			_s.videoSource2_str = _s._d.videosSource_ar[_s._d.startAtVideoSource]["source2"];
			if(_s.videoSource2_str && !_s.playSecondSource && !_s.isAdd_bl){
				_s.playSecondSource = true;
				_s.prevIsIMA = '-1';
				_s.setSource(_s.videoSource2_str, false, _s.videoType);

				if(_s.isQualityChangingError_bl){
					_s.play();
					_s.isQualityChangingError_bl = false;
				}
				return true;
			}
		}
		
		_s.videoScreenErrorHandler = function(e){
			var error;
			_s.isPlaying_bl = false;

			if(_s.checkSecondSource()){
				return;
			}
			
			// Execute error.
			if(FWDEVPlayer.hasHTML5Video || _s.videoType_str == FWDEVPlayer.YOUTUBE){
				error = e.text;
				if(window.console) console.log(e.text);
				if(_s.main_do) _s.main_do.addChild(_s.info_do);
				if(_s.info_do) _s.info_do.showText(error);
				
				if(_s.controller_do){
					_s.controller_do.disableMainScrubber();
					if(!_s._d.showControllerWhenVideoIsStopped_bl) _s.controller_do.hide(!_s.isMobile_bl, true);
					_s.hideClickScreen();
					_s.hider.stop();
				}
				
			}else{
				error = e;
				if(_s.main_do) _s.main_do.addChild(_s.info_do);
				if(_s.info_do) _s.info_do.showText(error);
			}
		
			if(_s.logo_do) _s.logo_do.hide(false);

			_s.preloader_do.hide(false);
			_s.preloader_do.stopPreloader();
			_s.showCursor();
			
			_s.dispatchEvent(FWDEVPlayer.ERROR, {error:error});
		};
		
		_s.videoScreenSafeToScrubbHandler = function(){
			
			if(_s.isCasting) return;
			
			if(_s.controller_do){
				if(_s.isAdd_bl){
					_s.controller_do.disableMainScrubber();
					if(_s._d.showSkipButton_bl){
						if(_s._d.timeToHoldAds != 0) _s.adsStart_do.show(true);
						if(_s._d.adsThumbnailPath_str && _s._d.adsThumbnailPath_str != "none") _s.adsStart_do.loadThumbnail(_s._d.adsThumbnailPath_str);
					}
					_s.positionAds();
				}else{
					_s.controller_do.enableMainScrubber();
				}
				
				if(_s.controller_do){
					if(!_s.isQualityChanging_bl) _s.controller_do.disableSubtitleButton();
					if(!_s.isAdd_bl) _s.controller_do.enableAtbButton();
					clearTimeout(_s.hideController_to);
					_s.controller_do.show(true);
				} 

				if(_s.isAdd_bl || (_s.IMA && _s.IMA.isPlaying)){
					// Ingnore controller tooltip and thumbnails preview
				}else{
					if(_s.customContextMenu_do) _s.customContextMenu_do.enable();
					_s.loadSubtitle(_s._d.subtitlePath_str);
					_s._d.tempShowMainScrubberToolTipLabel_bl = _s._d.showMainScrubberToolTipLabel_bl;
				
					if((_s._d.thumbnailsPreview || _s._d.thumbnailsPreview == 'auto')
					&& (_s.videoType_str == FWDEVPlayer.VIDEO || _s.videoType_str == FWDEVPlayer.HLS_JS)
					&& _s._d.thumbnailsPreview){
						_s._d.tempShowMainScrubberToolTipLabel_bl = false;
						_s.controller_do.setupThumbnailsPreview();
						_s.controller_do.thumbnailsPreview_do.load(_s._d.thumbnailsPreview, _s.videoType_str, _s.videoSource_str, _s.videoScreen_do.video_el);
					}else{
						if(_s._d.showMainScrubberToolTipLabel_bl) _s._d.tempShowMainScrubberToolTipLabel_bl = true;
					}
				}
				
				if(!_s.isAdd_bl && _s.controller_do.ytbQualityButton_do){
					_s.controller_do.ytbQualityButton_do.enable();
				}
				
				if(!_s.isAdd_bl && _s.controller_do.playbackRateButton_do){
					_s.controller_do.enablePlaybackRateButton();
				}

				if(!_s.isAdd_bl && _s.controller_do){
					 if(_s.controller_do.downloadButton_do) _s.controller_do.downloadButton_do.enable();
					 if(_s.controller_do.rewindButton_do) _s.controller_do.rewindButton_do.enable();
					 _s.controller_do.enableAtButton();
				}

				if(_s.isQualityChanging_bl && !_s.isAdd_bl){
					_s.scrubbAtTime(_s.curDurration);
					_s.curDurration = 0;
					_s.isQualityChanging_bl = false;
				}
				
				_s.hider.start();
			}
		
			_s.showClickScreen();
			setTimeout(function(){
				if(_s.totalDuration && _s.controller_do) _s.controller_do.positionAdsLines(_s.totalDuration);
			}, 500);

			var args = FWDEVPUtils.getHashUrlArgs(window.location.hash);
			
			if(_s.getStartTimeStamp("t") != "00:00:00"){
				if(args['evpi']){
					if(args['evpi'] == _s.instanceName_str) _s.scrubbAtTime(_s.getStartTimeStamp("t"));
				}else{
					_s.scrubbAtTime(_s.getStartTimeStamp("t"));
				}
			}

			if(document.cookie && _s._d.useResumeOnPlay_bl){
				if(FWDEVPUtils.getCookie("fwdevp_video_path") && FWDEVPUtils.getCookie("fwdevp_time") 
				   && FWDEVPUtils.getCookie("fwdevp_video_path") == _s.videoSourcePath_str && !_s.isAdd_bl){
					var curTime = FWDEVPUtils.getCookie("fwdevp_time");
					if(!_s.rmsPlayed_bl){
						_s.scrubbAtTime(FWDEVPUtils.getCookie("fwdevp_time"));
					}
				}
			}
			
			_s.setupAPT();
			_s.dispatchEvent(FWDEVPlayer.SAFE_TO_SCRUB);
		};
		
		_s.videoScreenStopHandler = function(e){
			
			if(_s.main_do) if(_s.main_do.contains(_s.info_do)) _s.main_do.removeChild(_s.info_do);
			
			_s.videoPoster_do.allowToShow_bl = true;
			_s.isPlaying_bl = false;
			
			clearTimeout(_s.hideController_to);
			if(_s.controller_do){
				_s.controller_do.disableMainScrubber();
				_s.controller_do.showPlayButton();
				_s.controller_do.updateMainScrubber(0);
				if(!_s._d.showControllerWhenVideoIsStopped_bl){
					_s.hideController_to = setTimeout(function(){
						_s.controller_do.hide(true, true);
					}, 200);
				}else{
					_s.controller_do.show(true);
				}
				_s.hider.stop();
			}
			
			if(_s.ytb_do && _s.videoType_str == FWDEVPlayer.YOUTUBE){
				_s.ytb_do.stopVideo();
			}
			
			if(_s.logo_do) _s.logo_do.hide(false);
			
			_s.hideClickScreen();
			_s.hider.reset();
			_s.showCursor();
			_s.dispatchEvent(FWDEVPlayer.STOP);
		};
		
		_s.videoScreenPlayHandler = function(){
			FWDEVPlayer.keyboardCurInstance = _s;
			
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE
			   && _s.ytb_do && _s.ytb_do.isStopped_bl) return;

			_s.videoPoster_do.hide();
			
			_s.callVastEvent("resume");
			_s.isPlaying_bl = true;
			_s.hasHlsPlayedOnce_bl = true;
			
			if(_s.isMobile_bl){
				if(FWDEVPlayer.videoStartBehaviour == FWDEVPlayer.STOP_ALL_VIDEOS){
					FWDEVPlayer.stopAllVideos(_s);
				}
			}else{
				if(FWDEVPlayer.videoStartBehaviour == FWDEVPlayer.PAUSE_ALL_VIDEOS){
					FWDEVPlayer.pauseAllVideos(_s);
				}
			}
			
			if(_s.logo_do && !_s.isIMA) _s.logo_do.show(true);
			  
			if(_s.controller_do){
				_s.controller_do.showPauseButton();
				clearTimeout(_s.hideController_to);
				_s.controller_do.show(true);
			}
			
			if(_s.popw_do) _s.popw_do.hide();
			
			if(_s.largePlayButton_do) _s.hideLargePlayButton();
			_s.hider.start();
			_s.showCursor();
			
			if(_s.isAdd_bl) _s.isQualityChangingError_bl = _s.isQualityChanging_bl = false;
			if(_s.playAtTime_bl && !_s.isAdd_bl) _s.scrubbAtTime(_s._d.scrubAtTimeAtFirstPlay);
			_s.playAtTime_bl = false;
			
			if(_s.isAdd_bl && !_s.hasStartedToPlay_bl) _s.scrubbAtTime(0);
		
			if(_s.wasAdd_bl && !_s.isAdd_bl){
				if(FWDEVPUtils.isSafari
				 || _s.videoType_str == FWDEVPlayer.VIMEO
				 || _s.videoType_str == FWDEVPlayer.DASH){
					clearTimeout(_s.playAfterAd_to);
					_s.playAfterAd_to = setTimeout(function(){
						_s.wasAdd_bl = false;
						_s.scrubbAtTime(_s.scrubAfterAddDuration);
					}, 1000);
				}else{
					_s.wasAdd_bl = false;
					_s.scrubbAtTime(_s.scrubAfterAddDuration);
				}
			}
			
			if(!_s.hasStartedToPlay_bl && _s._d.startAtTime && !_s.isAdd_bl){
				_s.scrubbAtTime(_s._d.startAtTime);
			}
			
			if(!_s.hasStartedToPlay_bl && _s.castStartAtTime && !_s.isAdd_bl){
				_s.scrubbAtTime(_s.castStartAtTime);
				_s.castStartAtTime = undefined;
			}
			
			if(_s.opener_do) _s.opener_do.showPauseButton();
			
			_s.hasStartedToPlay_bl = true;

			_s.dispatchEvent(FWDEVPlayer.PLAY);
		};
		
		_s.videoScreenPauseHandler = function(){
			if(_s.isCasting) return;
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE
			   && _s.ytb_do && _s.ytb_do.isStopped_bl) return;
			
			_s.isPlaying_bl = false;
			_s.callVastEvent("pause");
			
			if(_s.controller_do) _s.controller_do.showPlayButton(); 
			if(_s.largePlayButton_do && !_s._d.showAnnotationsPositionTool_bl && !_s.useWithoutVideoScreen_bl && !_s.notShowPlayBtnExternal) _s.showLargePlayButton();
			_s.notShowPlayBtnExternal = false;
			if(_s.controller_do) _s.controller_do.show(true);
			if(_s.logo_do && !_s.useWithoutVideoScreen_bl && !_s.useWithoutVideoScreen_bl) _s.logo_do.show(true);
			_s.hider.stop();
			_s.hider.reset();
			_s.showCursor();
			if(_s.videoType_str != FWDEVPlayer.VIMEO) _s.showClickScreen();
			
			if(_s.popw_do){
				var isShareWIndowShowed_bl = _s.shareWindow_do && _s.shareWindow_do.isShowed_bl;
				var isEmbedWIndowShowed_bl = _s.embedWindow_do && _s.embedWindow_do.isShowed_bl;

				if(!isShareWIndowShowed_bl && !isEmbedWIndowShowed_bl)
				_s.popw_do.show();
			}
			if(_s.opener_do) _s.opener_do.showPlayButton();
			
			_s.dispatchEvent(FWDEVPlayer.PAUSE);
		};
		
		_s.videoScreenUpdateHandler = function(e){
			var percent;	
			
			if(FWDEVPlayer.hasHTML5Video || _s.videoType_str == FWDEVPlayer.YOUTUBE && _s.videoType_str != FWDEVPlayer.IMAGE && _s.videoType_str != FWDEVPlayer.IFRAME){
				percent = e.percent;
				if(_s.controller_do) _s.controller_do.updateMainScrubber(percent);
			}else{
				percent = e;
				if(_s.controller_do) _s.controller_do.updateMainScrubber(percent);
			}
			_s.dispatchEvent(FWDEVPlayer.UPDATE, {percent:percent});
		};
		
		_s.videoScreenUpdateSubtitleHandler = function(e){
			_s.subtitle_do.updateSubtitle(e.curTime);
		}
		
		_s.videoScreenUpdateTimeHandler = function(e, e2, e3, stopHandler){
			if(_s.isCasting) return;
			if(_s.prevSeconds != Math.round(e.seconds)) _s.totalTimePlayed += 1;
			_s.totalTimeInSeconds = Math.round(e.totalTimeInSeconds);
			_s.totalTimeInMilliseconds = e.totalTimeInSeconds;
			_s.curTimeInSecond = Math.round(e.seconds);
			_s.curTimeInmilliseconds = e.seconds;
			_s.prevSeconds =  Math.round(e.seconds)
			if(_s.totalTimePlayed > 1){
				_s.totalPercentPlayed = _s.totalTimePlayed/e.totalTimeInSeconds;
				_s.stopped_at_time = _s.getCurrentTime();
			}

			if(!isFinite(_s.totalPercentPlayed)) _s.totalPercentPlayed = 0;			
		
			if(_s.controller_do 
			   && !_s.controller_do.isMainScrubberScrubbing_bl
			   && _s.controller_do.atb
			   && _s.controller_do.atb.isShowed_bl
			   && !_s.controller_do.atb.scrub){
				var a = _s.totalTimeInSeconds * _s.controller_do.atb.pa;
				var b = _s.totalTimeInSeconds * _s.controller_do.atb.pb;
				
				if(_s.prevCurTimeInSeconds != _s.curTimeInSecond){
					_s.prevCurTimeInSeconds = _s.curTimeInSecond;
					if(_s.curTimeInSecond < a){
						_s.scrub(_s.controller_do.atb.pa);
					}else if(_s.curTimeInSecond > b){
						_s.scrub(_s.controller_do.atb.pa);
					}
				}	
			}

			if(_s.isAdd_bl){
				if(_s.totalPercentPlayed >= .25 && _s.callFirstQuartile){
					_s.callVastEvent("firstQuartile");
					_s.callFirstQuartile = false;
				}else if(_s.totalPercentPlayed >= .5 && _s.callMidpoint){
					_s.callVastEvent("midpoint");
					_s.callMidpoint = false;
				}else if(_s.totalPercentPlayed >= .75 && _s.callThirdQuartile){
					_s.callVastEvent("thirdQuartile");
					_s.callThirdQuartile = false;
				}
			}
			
			var time;
			var seconds;
			if(FWDEVPlayer.hasHTML5Video || _s.videoType_str == FWDEVPlayer.YOUTUBE || _s.videoType_str == FWDEVPlayer.HLS_JS || _s.videoType_str == FWDEVPlayer.VIMEO){
				_s.curTime = e.curTime;
				_s.totalTime = e.totalTime;
				time = _s.curTime + "/" + _s.totalTime;
				seconds = Math.round(e.seconds);
				if(_s.controller_do) _s.controller_do.updateTime(time);
			}else{
				_s.curTime = e;
				_s.totalTime = e2;
				time = _s.curTime + "/" + _s.totalTime;
				seconds = Math.round(e3);
				if(e == undefined || e2 ==  undefined) time = "00:00/00:00";
				if(_s.controller_do) _s.controller_do.updateTime(time);
			}
		
			if(stopHandler) return;
			
			if(!_s.isAdd_bl){
				if(_s.totalTime.length>5){
					_s.totalDuration = FWDEVPUtils.getSecondsFromString(_s.totalTime);
				}else{
					_s.totalDuration = FWDEVPUtils.getSecondsFromString("00:" + _s.totalTime);
				}
			}
			
			if(_s.isAdd_bl && _s._d.showSkipButton_bl){
				if(_s._d.timeToHoldAds > seconds){
					_s.adsStart_do.updateText(_s._d.skipToVideoText_str + Math.abs(_s._d.timeToHoldAds - seconds));
					_s.adsSkip_do.hide(false);
					if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
						_s.adsStart_do.show(true);
					}
				}else{
					_s.adsStart_do.hide(true);
					if(_s._d.timeToHoldAds != 0) _s.adsSkip_do.show(true);
				}
			}
			
			_s.currentSecconds = seconds;
			
			if(!_s.isAdd_bl && _s.popupAds_do) _s.popupAds_do.update(seconds);
			if(!_s.isAdd_bl && _s.annotations_do) _s.annotations_do.update(seconds);
			if(seconds != 0 && !_s.isAdd_bl) _s.curDurration = seconds;
		
			if(_s._d.cuePointsSource_ar){
				for(var i=0; i<_s._d.cuePointsSource_ar.length; i++){
					var cuePoint = _s._d.cuePointsSource_ar[i];
					if(cuePoint.timeStart == seconds){
						if(_s._d.executeCuepointsOnlyOnce_bl){
							if(!cuePoint.isPlayed_bl) eval(cuePoint.javascriptCall);
						}else{
							eval(cuePoint.javascriptCall);
						}
						cuePoint.isPlayed_bl = true;
					}
				}
			}

			if(!_s.isAdd_bl && seconds != 0 && _s.isPlaying_bl){
				 _s.updateAds(seconds);
			}

			if(_s.isIMA && !_s.IMA.started){
				_s.IMA.updateCuepointLines(seconds);
			}
			
			if(_s.isPlaying_bl && FWDEVPUtils.getSecondsFromString(_s._d.stopAtTime) <= seconds) _s.stop();

			if(FWDEVPUtils.getSecondsFromString(_s.getStartTimeStamp("e"))){
				if(_s.curTimeInSecond >= parseInt(FWDEVPUtils.getSecondsFromString(_s.getStartTimeStamp("e")))) _s.stop();
			}
				
			_s.dispatchEvent(FWDEVPlayer.UPDATE_TIME, {currentTime:_s.curTime, totalTime:_s.totalTime});
		};
		
		_s.videoScreenLoadProgressHandler = function(e){
			if(FWDEVPlayer.hasHTML5Video || _s.videoType_str == FWDEVPlayer.YOUTUBE){
				if(_s.controller_do) _s.controller_do.updatePreloaderBar(e.percent);
			}else if(_s.videoType_str == FWDEVPlayer.VIDEO){
				if(_s.controller_do) _s.controller_do.updatePreloaderBar(e);
			}
		};
		
		_s.videoScreenStartToBuferHandler = function(){
			if(_s.showPreloader_bl){
				_s.preloader_do.show(false);
				_s.preloader_do.startPreloader();
			} 
		};
		
		_s.videoScreenStopToBuferHandler = function(){
			_s.preloader_do.hide(false);
			_s.preloader_do.stopPreloader();
		};
		
		_s.videoScreenPlayCompleteHandler = function(e, buttonUsedToSkipAds){
			_s.adDone_bl = true;
			
			_s.callVastEvent("complete");
			
			if(_s.isIMA && _s.IMA.hasPostRoll && _s.curTimeInSecond >= _s.totalTimeInSeconds - 1){
				_s.IMA.playPostRoll();
				return;
			}
			
			if(!_s.isAdd_bl && _s._d.redirectURL){
				if(_s._d.redirectTarget == "_self"){
					location.replace(_s._d.redirectURL);
				}else{
					window.open(_s._d.redirectURL, _s._d.redirectTarget);
				}
			}
		
			var tempIsAdd_bl = _s.isAdd_bl;
			_s.tempIsAdd_bl = _s.isAdd_bl;
			setTimeout(()=>{
				_s.tempIsAdd_bl = false;
			}, 1000)

			if(_s.isAdd_bl){
				if(_s._d.openNewPageAtTheEndOfTheAds_bl && _s._d.adsPageToOpenURL_str != "none" && !buttonUsedToSkipAds){
					if(_s._d.adsPageToOpenTarget_str == "_self"){
						location.href = _s._d.adsPageToOpenURL_str;
					}else{
						window.open(_s._d.adsPageToOpenURL_str, _s._d.adsPageToOpenTarget_str);
					}
				}
				
				_s.isAdd_bl = false;
			
				// Set new source and check for second source!
				_s.isQualityChangingError_bl = true;
				_s.updateAds(_s.curDurration);
				
				_s.wasAdd_bl = true;
				if(buttonUsedToSkipAds && _s.videoType_str == FWDEVPlayer.VIDEO){	
					_s.play();
				}else{
					if(!_s.isMobile_bl && _s.videoType_str != FWDEVPlayer.HLS_JS) _s.play();
				}	
				_s.wasAdHLS = true;
				_s.callVastEvent('complete');
				_s.sendGAPlayedAdEvent();
			}
			
			if(!tempIsAdd_bl){
				_s.wasAdd_bl = false;
				if((_s.lightBox_do && _s.lightBox_do.isShowed_bl && _s._d.clsLghtbPlayFinish)){
					_s.stop();
					_s.lightBox_do.closeButtonOnStartHandler();
				}

				if(_s._d.loop_bl){
					_s.scrub(0);
					_s.play();
				}else{
					_s.stop();
				}

				_s.dispatchEvent(FWDEVPlayer.PLAY_COMPLETE);
			}
			
			if(_s.hider) _s.hider.reset();
		};
		

		//##########################################//
		/* Setup annotations */
		//##########################################//
		_s.setupAnnotations = function(){
			FWDEVPAnnotations.setPrototype();
			_s.annotations_do = new FWDEVPAnnotations(_s, _s._d);
			_s.main_do.addChild(_s.annotations_do);
		};
		

		//##########################################//
		/* Setup skip adds buttons */
		//##########################################//
		_s.setupAdsStart = function(){
			FWDEVPAdsStart.setPrototype();
			_s.adsStart_do = new FWDEVPAdsStart(
					_s._d.adsButtonsPosition_str, 
					_s._d.adsBorderNormalColor_str, 
					"", 
					_s._d.adsBackgroundPath_str,
					_s._d.adsTextNormalColor);
			
			FWDEVPAdsButton.setPrototype();
			_s.adsSkip_do = new FWDEVPAdsButton(
					_s,
					_s._d.skipIconPath_img,
					_s._d.skipIconSPath_str,
					_s._d.skipToVideoButtonText_str,
					_s._d.adsButtonsPosition_str, 
					_s._d.adsBorderNormalColor_str, 
					_s._d.adsBorderSelectedColor_str, 
					_s._d.adsBackgroundPath_str,
					_s._d.adsTextNormalColor,
					_s._d.adsTextSelectedColor,
					_s._d.useHEX,
					_s._d.nBC,
					_s._d.sBC);
			_s.adsSkip_do.addListener(FWDEVPAdsButton.MOUSE_UP, _s.skipAdsMouseUpHandler);
			
			
			_s.main_do.addChild(_s.adsSkip_do);
			_s.main_do.addChild(_s.adsStart_do);
		};
		
		_s.skipAdsMouseUpHandler = function(e){
			if(e.preventDefault) e.preventDefault();
			
			_s.callVastEvent("skip");
			_s.videoScreenPlayCompleteHandler(e, true);
		};
		
		_s.positionAds = function(animate){
			if(!_s._d.showSkipButton_bl) return;
			var finalX;
			var finalY;
			var mbl = false;
			if(_s.sW < 600) mbl = true;
		
			_s.adsSkip_do.resize();

			if(_s._d.adsButtonsPosition_str == "left"){
				finalX = 0;
			}else{
				finalX = _s.sW;
			}
			
			if(_s.controller_do && _s.controller_do.isShowed_bl){
				finalY = _s.sH - _s.adsStart_do.h - _s._d.controllerHeight - 30;
			}else{
				finalY = _s.sH - _s.adsStart_do.h - _s._d.controllerHeight;
			}
			
			FWDAnimation.killTweensOf(_s.adsStart_do);
			if(animate){
				FWDAnimation.to(_s.adsStart_do, .8, {y:finalY, ease:Expo.easeInOut});
			}else{
				_s.adsStart_do.setY(finalY);
			}
			
			_s.adsStart_do.setX(finalX);
			
			if(_s._d.adsButtonsPosition_str == "left"){
				finalX = 0;
			}else{
				finalX = _s.sW;
			}
			
			if(_s.controller_do && _s.controller_do.isShowed_bl){
				finalY = _s.sH - _s.adsSkip_do.h - _s._d.controllerHeight - 30;
			}else{
				finalY = _s.sH - _s.adsSkip_do.h - _s._d.controllerHeight;
			}
			
			FWDAnimation.killTweensOf(_s.adsSkip_do);
			if(animate){
				FWDAnimation.to(_s.adsSkip_do, .8, {y:finalY, ease:Expo.easeInOut});
			}else{
				_s.adsSkip_do.setY(finalY);
			}
			
			_s.adsSkip_do.setX(finalX);
			
		};
		
		
		//##########################################//
		/* Setup embed window */
		//##########################################//
		_s.setupShareWindow = function(){
			FWDEVPShareWindow.setPrototype();
			_s.shareWindow_do = new FWDEVPShareWindow(_s._d, _s);
			_s.shareWindow_do.addListener(FWDEVPShareWindow.HIDE_COMPLETE, _s.shareWindowHideCompleteHandler);
		};
		
		_s.shareWindowHideCompleteHandler = function(){
			
			if(_s.isVideoPlayingWhenOpenWindows_bl){
				_s.resume();
			}
			
			if(_s.controller_do){
				_s.controller_do.shareButton_do.isDisabled_bl = false;
				_s.controller_do.shareButton_do.setNormalState(true);
			}
		};
		
		
		//##########################################//
		/* Setup login window */
		//##########################################//
		_s.setupLoginWindow =  function(){
			FWDEVPPassword.setPrototype();
			_s.lg_do = new FWDEVPPassword(_s._d, _s, true);
		}
		

		//##########################################//
		/* Setup embed window */
		//##########################################//
		_s.setupPasswordWindow = function(){
			FWDEVPPassword.setPrototype();
			_s.passWindow_do = new FWDEVPPassword(_s._d, _s);
			_s.passWindow_do.addListener(FWDEVPPassword.CORRECT, _s.passordCorrect);
			_s.passWindow_do.addListener(FWDEVPPassword.HIDE_COMPLETE, _s.passordHideComplete);
		};
		
		_s.passordCorrect = function(){
			_s.passWindow_do.hide();
			_s.hasPassedPassowrd_bl = true;
			_s.play();
		}

		_s.passordHideComplete = function(){
			if(_s.isStopped_bl){
				_s.showLargePlayButton()
			}
		}
		

		//##########################################//
		/* Setup embed window */
		//##########################################//
		_s.setupEmbedWindow = function(){
			FWDEVPEmbedWindow.setPrototype();
			_s.embedWindow_do = new FWDEVPEmbedWindow(_s._d, _s);
			_s.embedWindow_do.addListener(FWDEVPEmbedWindow.ERROR, _s.embedWindowErrorHandler);
			_s.embedWindow_do.addListener(FWDEVPEmbedWindow.HIDE_COMPLETE, _s.embedWindowHideCompleteHandler);
		};
		
		_s.embedWindowErrorHandler = function(e){
			_s.main_do.addChild(_s.info_do);
			_s.info_do.showText(e.error);
		};
		
		_s.embedWindowHideCompleteHandler = function(){
		
			if(_s.isVideoPlayingWhenOpenWindows_bl) _s.resume();
		
			if(_s.controller_do){
				_s.controller_do.embedButton_do.isDisabled_bl = false;
				_s.controller_do.embedButton_do.setNormalState(true);
			}
		};
		
		_s.copyLinkButtonOnMouseOver = function(){
			if(!_s.embedWindow_do.isShowed_bl) return
			_s.embedWindow_do.copyLinkButton_do.setSelectedState();
		};
		
		_s.copyLinkButtonOnMouseOut = function(){
			if(!_s.embedWindow_do.isShowed_bl) return
			_s.embedWindow_do.copyLinkButton_do.setNormalState();
		};
		
		_s.getLinkCopyPath = function(){
			if(!_s.embedWindow_do.isShowed_bl) return
			return _s.embedWindow_do.linkToVideo_str;
		};
		
		_s.embedkButtonOnMouseOver = function(){
			if(!_s.embedWindow_do.isShowed_bl) return
			_s.embedWindow_do.copyEmbedButton_do.setSelectedState();
		};
		
		_s.embedButtonOnMouseOut = function(){
			if(!_s.embedWindow_do.isShowed_bl) return
			_s.embedWindow_do.copyEmbedButton_do.setNormalState();
		};
		
		_s.getEmbedCopyPath = function(){
			return _s.embedWindow_do.finalEmbedCode_str;
		};
		
		
		//######################################//
		/* Add keyboard support */
		//######################################//
		_s.setInputs = function(){
			var numInputs = document.querySelectorAll('input');
			for (var i = 0; i < numInputs.length; i++) {
				numInputs[i].addEventListener("mousedown", _s.inputFocusInHandler);
				numInputs[i].addEventListener("touchstart", _s.inputFocusInHandler);
			}

			var numTextA = document.querySelectorAll('textarea');
			for (var i = 0; i < numTextA.length; i++) {
				numTextA[i].addEventListener("mousedown", _s.inputFocusInHandler);
				numTextA[i].addEventListener("touchstart", _s.inputFocusInHandler);
			}
		}
		
		_s.inputFocusInHandler = function(e){
			_s.curInput = e.target;
			setTimeout(function(){
				if(_s.hasPointerEvent_bl){
					window.addEventListener("pointerdown", _s.inputFocusOutHandler);
				}else if(window.addEventListener){
					window.addEventListener("mousedown", _s.inputFocusOutHandler);
					window.addEventListener("touchstart", _s.inputFocusOutHandler);
				}
				FWDEVPlayer.isSearchedFocused_bl = true;
			}, 50);
		}
		
		_s.inputFocusOutHandler = function(e){
			
			var vc = FWDEVPUtils.getViewportMouseCoordinates(e);	
			if(!FWDEVPUtils.hitTest(_s.curInput, vc.screenX, vc.screenY)){
				if(_s.hasPointerEvent_bl){
					window.removeEventListener("pointerdown", _s.inputFocusOutHandler);
				}else if(window.removeEventListener){
					window.removeEventListener("mousedown", _s.inputFocusOutHandler);
					window.removeEventListener("touchstart", _s.inputFocusOutHandler);
				}
				FWDEVPlayer.isSearchedFocused_bl = false;
				return;
			}
		};
		
		_s.addKeyboardSupport = function(){
			_s.setInputs();
			document.addEventListener("keydown",  _s.onKeyDownHandler);	
			document.addEventListener("keyup",  _s.onKeyUpHandler);	
		};

		_s.removeKeyboardSupport = function(){
			_s.setInputs();
			document.removeEventListener("keydown",  _s.onKeyDownHandler);	
			document.removeEventListener("keyup",  _s.onKeyUpHandler);	
		};
		
		_s.onKeyDownHandler = function(e){
			if(!_s._d.addKeyboardSupport_bl) return;
			if((_s.isSpaceDown_bl || !_s.hasStartedToPlay_bl || FWDEVPlayer.isSearchedFocused_bl) && !_s.isCasting) return;
			_s.isSpaceDown_bl = true;
			if(e.preventDefault) e.preventDefault();
			
			//pause
			if (e.keyCode == 32){
				
				if(_s != FWDEVPlayer.keyboardCurInstance 
				   && (FWDEVPlayer.videoStartBehaviour == "pause" || FWDEVPlayer.videoStartBehaviour == "none")) return
				_s.stickOnCurrentInstanceKey_bl = true;
			
				if(_s.isCasting){
					_s.cc.togglePlayPause();
				}else if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
					if(_s.isImageAdsPlaying_bl){
						_s.stopUpdateImageInterval();
					}else{
						_s.startUpdateImageInterval();
					}
				}else if(_s.isIMA && _s.IMA.started){
					_s.IMA.togglePlayPause();
				}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE){
					if(!_s.ytb_do.isSafeToBeControlled_bl) return;
					_s.ytb_do.togglePlayPause();
				}else if(_s.videoType_str == FWDEVPlayer.VIMEO){
					if(!_s.vimeo_do.isSafeToBeControlled_bl) return;
					_s.vimeo_do.togglePlayPause();
				}else if(_s.videoType_str == FWDEVPlayer.MP3){
					if(!_s.audioScreen_do.isSafeToBeControlled_bl) return;
					_s.audioScreen_do.togglePlayPause();
				}else if(FWDEVPlayer.hasHTML5Video){
					if(!_s.videoScreen_do.isSafeToBeControlled_bl) return;
					if(_s.videoScreen_do) _s.videoScreen_do.togglePlayPause();
				}
				if(e.preventDefault) e.preventDefault();
				return false;
			}else if (e.keyCode == 70 && !_s.useWithoutVideoScreen_bl){
				if(_s.isFullScreen_bl){
					_s.goNormalScreen();
				}else{
					_s.goFullScreen();
				}
			}else if (e.keyCode == 77){
				if(_s.volume != 0) _s.lastVolume = _s.volume;
				if(_s.volume != 0){
					_s.volume = 0;
				}else{
					_s.volume = _s.lastVolume;
				}
				_s.setVolume(_s.volume);
			}else if (e.keyCode == 38){
				_s.volume += .1;
				if(_s.volume > 1) _s.volume = 1;
				_s.setVolume(_s.volume);
			}else if (e.keyCode == 40){
				_s.volume -= .1;
				if(_s.volume < 0) _s.volume = 0;
				_s.setVolume(_s.volume);
			}else if (e.keyCode == 77){
				if(_s.volume < 0) _s.volume = 0;
				_s.setVolume(_s.volume);
			}else if (e.keyCode == 39 && !_s.isAdd_bl && !_s.isIMA){
				var curTime = _s.getCurrentTime();
				if(curTime.length == 5) curTime = "00:" + curTime;
				if(curTime.length == 7) curTime = "0" + curTime;
				curTime = FWDEVPUtils.getSecondsFromString(curTime);
				curTime += 5;
				curTime = FWDEVPUtils.formatTime(curTime);
				if(curTime.length == 5) curTime = "00:" + curTime;
				if(curTime.length == 7) curTime = "0" + curTime;
				_s.scrubbAtTime(curTime);
			}else if (e.keyCode == 37 && !_s.isAdd_bl && !_s.isIMA){
				var curTime = _s.getCurrentTime();
				if(curTime.length == 5) curTime = "00:" + curTime;
				if(curTime.length == 7) curTime = "0" + curTime;
				curTime = FWDEVPUtils.getSecondsFromString(curTime);
				curTime -= 5;
				curTime = FWDEVPUtils.formatTime(curTime);
				if(curTime.length == 5) curTime = "00:" + curTime;
				if(curTime.length == 7) curTime = "0" + curTime;
				_s.scrubbAtTime(curTime);
			}
		};
		
		_s.onKeyUpHandler = function(e){
			if(!_s._d.addKeyboardSupport_bl) return;
			_s.isSpaceDown_bl = false;
		};

		
		//####################################//
		/* Setup hider */
		//####################################//
		_s.setupHider = function(){
			FWDEVPHider.setPrototype();
			_s.hider = new FWDEVPHider(_s.main_do, _s.controller_do, _s._d.controllerHideDelay);
			_s.hider.addListener(FWDEVPHider.SHOW, _s.hiderShowHandler);
			_s.hider.addListener(FWDEVPHider.HIDE, _s.hiderHideHandler);
			_s.hider.addListener(FWDEVPHider.HIDE_COMPLETE, _s.hiderHideCompleteHandler);
		};
		
		_s.hiderShowHandler = function(){
			if(_s.isCasting) return;
			
			if(_s.controller_do && _s.isPlaying_bl){
				clearTimeout(_s.hideController_to);
				_s.controller_do.show(true);
			} 
			if(_s.logo_do && _s._d.hideLogoWithController_bl && _s.isPlaying_bl && !_s.useWithoutVideoScreen_bl) _s.logo_do.show(true);
			_s.showCursor();
			if(_s.isAdd_bl && _s._d.showSkipButton_bl){
				_s.positionAds(true);
				_s.adsStart_do.showWithOpacity();
				_s.adsSkip_do.showWithOpacity();	
			}
			_s.subtitle_do.position(true);
			if(_s.popupAds_do) _s.popupAds_do.position(true);
			_s.dispatchEvent(FWDEVPlayer.HIDER_SHOW);
		};
		
		_s.hiderHideHandler = function(){
			if(_s.isCasting) return;
			if(_s.videoType_str == FWDEVPlayer.VIMEO && !_s._d.showDefaultControllerForVimeo_bl) return;
			
			if(_s.controller_do && _s._d.showYoutubeQualityButton_bl && FWDEVPUtils.hitTest(_s.controller_do.ytbButtonsHolder_do.screen, _s.hider.globalX, _s.hider.globalY)){
				_s.hider.reset();
				return;
			}

			if(_s.controller_do && _s.controller_do.atb && _s.controller_do.atb.isShowed_bl){
				if(FWDEVPUtils.hitTest(_s.controller_do.atb.mainHolder_do.screen, _s.hider.globalX, _s.hider.globalY)){
					_s.hider.reset();
					return;
				}
			}
			
			if(_s.controller_do && _s._d.showSubtitleButton_bl && FWDEVPUtils.hitTest(_s.controller_do.subtitlesButtonsHolder_do.screen, _s.hider.globalX, _s.hider.globalY)){
				_s.hider.reset();
				return;
			}
			
			if(_s.controller_do && _s._d.showPlaybackRateButton_bl && FWDEVPUtils.hitTest(_s.controller_do.playbackRatesButtonsHolder_do.screen, _s.hider.globalX, _s.hider.globalY)){
				_s.hider.reset();
				return;
			}
			
			if(_s.controller_do && FWDEVPUtils.hitTest(_s.controller_do.screen, _s.hider.globalX, _s.hider.globalY)){
				_s.hider.reset();
				return;
			}
			
			if(_s.controller_do) _s.controller_do.hide(true);
			if(_s.isAdd_bl && _s._d.showSkipButton_bl){
				_s.positionAds(true);
				_s.adsStart_do.hideWithOpacity();
				_s.adsSkip_do.hideWithOpacity();	
			}
			
			if(_s.logo_do && _s._d.hideLogoWithController_bl) _s.logo_do.hide(true);
			if(_s.isFullScreen_bl) _s.hideCursor();
			_s.subtitle_do.position(true);
			if(_s.popupAds_do) _s.popupAds_do.position(true);
			_s.dispatchEvent(FWDEVPlayer.HIDER_HIDE);
		};
		
		_s.hiderHideCompleteHandler = function(){
			if(_s.isCasting) return;
			if(_s.controller_do) _s.controller_do.positionScrollBarOnTopOfTheController();
		};
		
		
		//####################################//
		// API
		//###################################//
		_s.showPlayer = function(){
			if(!_s.isAPIReady_bl) return;
			_s.isShowed_bl = true;
			_s.opener_do.showCloseButton();
			_s.setStageContainerFinalHeightAndPosition(_s.animate_bl);
			if(_s.isMin){
				_s.isMinShowed = true;
				_s.positionOnMin(true);
			}
		};
		
		_s.hidePlayer = function(){
			if(!_s.isAPIReady_bl) return;
			_s.isShowed_bl = false;
			_s.opener_do.showOpenButton();
			_s.setStageContainerFinalHeightAndPosition(_s.animate_bl);
			if(_s.isMin){
				_s.isMinShowed = false;
				_s.positionOnMin(true);
			}
		};
		
		_s.play = function(){
			
			if(!_s.isAPIReady_bl) return;

			if(_s.isCasting){
				_s.cc.play();
				return;
			}
	
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE && !_s.isYoutubeReady_bl){
				if(_s.showPreloader_bl){
				 	_s.preloader_do.show(false);
					_s.preloader_do.startPreloader();
				}
				if(_s.largePlayButton_do && !_s.useWithoutVideoScreen_bl) _s.showLargePlayButton();
				return;
			}
			
			if(_s.videoType_str == FWDEVPlayer.VIMEO && !_s.isVimeoReady_bl){
				if(_s.showPreloader_bl){
					_s.preloader_do.show(false);
					_s.preloader_do.startPreloader();
				}
				if(_s.largePlayButton_do && !_s.useWithoutVideoScreen_bl) _s.showLargePlayButton();
				return;
			}
			
			// if(_s.videoType_str == FWDEVPlayer.HLS_JS){
			// 	if(location.protocol.indexOf("file:") >= 0){
			// 		_s.main_do.addChild(_s.info_do);
			// 		_s.info_do.showText("HLS m3u8 videos can't be played local on this browser, please test it online!.");
			// 		return;
			// 	}
			// }

			
			if(_s._d.playVideoOnlyWhenLoggedIn_bl){
				if(!_s._d.isLoggedIn_bl){
					if(_s.largePlayButton_do && !_s.useWithoutVideoScreen_bl) _s.showLargePlayButton();
					_s.lg_do.show();
					return;
				}
			}
			
			if(!_s.isAdd_bl && _s._d.videosSource_ar[_s._d.startAtVideoSource]["isPrivate"] && !_s.hasPassedPassowrd_bl && _s.passWindow_do){
				if(_s.largePlayButton_do && !_s.useWithoutVideoScreen_bl) _s.hideLargePlayButton();
				_s.passWindow_do.show();
				_s.dispatchEvent(FWDEVPlayer.PLAY_START);
				return
			}
			_s.hasPassedPassowrd_bl = true;
			_s.isStopped_bl = false;

			if(_s.isMobile_bl){
				FWDEVPlayer.stopAllVideos(_s);
			}else{
				if(FWDEVPlayer.videoStartBehaviour == FWDEVPlayer.PAUSE_ALL_VIDEOS){
					FWDEVPlayer.pauseAllVideos(_s);
				}else if(FWDEVPlayer.videoStartBehaviour == FWDEVPlayer.STOP_ALL_VIDEOS){
					FWDEVPlayer.stopAllVideos(_s);
				}
			}
		
			if(_s.isIMA){
				if(!_s.IMA || _s.isIMA && _s.IMA && !_s.IMA.isReady) return;
				_s.IMA.play();
			}else if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
				_s.startUpdateImageInterval();
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do){
				_s.ytb_do.play();
				
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.play();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do){
					_s.audioScreen_do.play();
					if(!FWDEVPUtils.isLocal) _s.audioScreen_do.setupSpectrum();
				}
			}else if(FWDEVPlayer.hasHTML5Video){
			
				if(_s.videoType_str == FWDEVPlayer.HLS_JS && !_s.isHLSManifestReady_bl && window['Hls']){
					_s.videoScreen_do.initVideo();
					_s.setupHLS();
					var source = _s.videoSourcePath_str;
					if(source.indexOf("encrypt:") != -1) source = atob(source.substr(8));
					_s.hlsJS.loadSource(source);
					_s.hlsJS.attachMedia(_s.videoScreen_do.video_el);
					_s.hlsJS.on(Hls.Events.MANIFEST_PARSED,function(e){
						_s.isHLSManifestReady_bl = true;
						_s.parseLevels();
						_s.play();
					});

					// Get qulity levels.

				}else if(_s.dashJS && _s.videoType_str == FWDEVPlayer.DASH && !_s.isDASHManifestReady_bl){
					_s.videoScreen_do.initVideo();
					_s.setupDASH();
					
					_s.dashJS.initialize(_s.videoScreen_do.video_el, _s.videoSourcePath_str, false);
                 	_s.dashJS.attachSource(_s.videoSourcePath_str);

                 	_s.dashJS.on(dashjs.MediaPlayer.events.MANIFEST_LOADED, function(e){
                 		_s.isDASHManifestReady_bl = true;
						setTimeout(_s.play, 100);
                 	});
				}else{
					if(_s.videoScreen_do) _s.videoScreen_do.play();
				}
				
			}
			
			FWDEVPlayer.keyboardCurInstance = _s;
			_s.videoPoster_do.allowToShow_bl = false;
			_s.playStarted = true;
			if(_s.largePlayButton_do) _s.hideLargePlayButton();
			
			_s.videoPoster_do.hide();
			_s.dispatchEvent(FWDEVPlayer.PLAY_START);
		};
		
		_s.pause = function(){
			if(!_s.isAPIReady_bl) return;
			
			if(_s.isCasting){
				_s.cc.pause();
				return;
			}
			
			if(_s.isIMA && _s.IMA){
				_s.IMA.pause();
			}else if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
				_s.stopUpdateImageInterval();
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do){
				_s.ytb_do.pause();
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.pause();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.pause();
			}else{
				if(_s.videoScreen_do) _s.videoScreen_do.pause();
			}
		};
		
		_s.resume = function(){
			if(!_s.isAPIReady_bl) return;
			
			if(_s.isCasting){
				_s.cc.play();
			}else if(_s.isIMA && _s.IMA.started){
				_s.IMA.play();
			}else if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
				_s.startUpdateImageInterval();
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do){
				_s.ytb_do.resume();
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.resume();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.resume();
			}else if(FWDEVPlayer.hasHTML5Video){
				if(_s.videoScreen_do) _s.videoScreen_do.resume();
			}
		};	

		_s.sendGAPlayedEvent = function(){
			
			if(!isNaN(_s.totalPercentPlayed) && _s.gtag && !_s.isAdd_bl && !_s.tempIsAdd_bl){
				if(_s.totalPercentPlayed){
					var params = {
						'video_url': _s.videoSource_str,
						'percent_played' : _s.totalPercentPlayed.toFixed(3),
						'stopped_at_time': _s.getCurrentTime(),
						'duration' : _s.getTotalTime(),
						'full_screen': _s.isFullScreen_bl
					}

					_s.gtag('event', 'played', params);

					_s.totalTimePlayed = 0;
					_s.totalPercentPlayed = 0;
				}
				
			}
		}	

		_s.sendGAPlayedAdEvent = function(source, percentPlayed, duration){
			if(!source){
				source = _s.addSource_str;
			}

			if(!percentPlayed){
				percentPlayed = _s.totalPercentPlayed;
			}

			if(!duration){
				duration = _s.getTotalTime();
			}
			
			if(!isNaN(percentPlayed) && _s.gtag){
				if(_s.totalPercentPlayed){
					var params = {
						'video_url': source,
						'percent_played' : percentPlayed.toFixed(3),
						'stopped_at_time': _s.stopped_at_time,
						'duration': duration,
						'full_screen': _s.isFullScreen_bl
					}

					_s.gtag('event', 'ads', params);

					_s.totalTimePlayed = 0;
					_s.totalPercentPlayed = 0;
				}

			}
		}	

		
		_s.stop = function(source){
			if(!_s.isAPIReady_bl) return;

			_s.sendGAPlayedEvent();
			
			if(_s.IMA) _s.IMA.stop();
			_s.isQualityChangingStop_bl = false;
			
			if(_s.isCasting){
				_s.cc.stop();
			}
		
			_s.hlsLevels_ar = null;
			_s.isIMA = undefined;
			_s.isStopped_bl = true;
			_s.hasPassedPassowrd_bl = false;
			_s.isHLSManifestReady_bl = false;
			_s.isDASHManifestReady_bl = false;
			_s.playYoutubeIfLoadedLate_bl = false;
			_s.isPlaying_bl = false;
			_s.totalTimePlayed = 0;
			_s._d.closeVast();
			_s.hideAPT();
			_s.hider.reset();
			_s.destroyHLS();
			_s.destroyDASH();
			clearTimeout(_s.parseQualityLevelsId_to);
			clearTimeout(_s.playAfterAd_to);
			clearTimeout(_s.rewindId_to);
			clearTimeout(_s.load360ScriptsId_to);
			if(_s.popw_do) _s.popw_do.hide();
			
			if(_s.controller_do && _s.controller_do.ytbQualityButton_do){
				_s.controller_do.ytbQualityButton_do.disable();
				_s.controller_do.hideQualityButtons(false);
				_s.controller_do.updateMainScrubber(0);
				_s.controller_do.updatePreloaderBar(0);
			}
			
			if(_s.controller_do){
				if(_s.controller_do.atb) _s.controller_do.atb.hide(true);
				_s.controller_do.disableAtButton();
				if(_s.controller_do.thumbnailsPreview_do) _s.controller_do.thumbnailsPreview_do.remove();
				if(_s.controller_do.subtitleButton_do) _s.controller_do.subtitleButton_do.disable();
				if(_s.controller_do.rewindButton_do) _s.controller_do.rewindButton_do.disable();
				_s.controller_do.disablePlaybackRateButton();
				if(_s.controller_do.ttm) _s.controller_do.ttm.hide();
				if(_s._d.showPlaybackRateButton_bl){
					_s.controller_do.updatePlaybackRateButtons(_s._d.updatePlaybackRateButtons, _s._d.startAtPlaybackIndex);
				}
			}
		
			if(_s.isAdd_bl){
				_s.setPlaybackRate(1);
			}else{
				_s.setPlaybackRate(_s._d.defaultPlaybackRate_ar[_s._d.startAtPlaybackIndex]);
			}
			
			if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
				_s.stopUpdateImageInterval();
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do){
				_s.ytb_do.stop();
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO){
				if(_s.vimeo_do) _s.vimeo_do.stop();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.stop();
			}else{
				_s.videoScreen_do.stop();
			}
			
			clearTimeout(_s.hideController_to);
			if(_s.controller_do){
				if(_s._d.showControllerWhenVideoIsStopped_bl){
					 _s.controller_do.show(true);
				}else{
					_s.hideController_to = setTimeout(function(){
						_s.controller_do.hide(true);
					}, 200);
				}
			}
			_s.videoPoster_do.show();
			if(_s.largePlayButton_do && !_s.useWithoutVideoScreen_bl && !_s.notShowLargePlayButton_bl) _s.showLargePlayButton();
			
			clearInterval(_s.fillScreenId_int);
			_s.subtitle_do.stopToLoadSubtitle();
			_s.subtitle_do.hide();
			_s.hasHlsPlayedOnce_bl = false;
			_s.isSafeToScrub_bl = false;
			_s.hlsState = undefined;
			if(_s.popupAds_do) _s.popupAds_do.hideAllPopupButtons(false);
			if(_s.adsStart_do) _s.adsStart_do.hide(true);
			if(_s.adsSkip_do) _s.adsSkip_do.hide(true);
			if(_s.controller_do) _s.controller_do.hideAdsLines();
			if(_s.annotations_do) _s.annotations_do.update(100000);
			if(_s.customContextMenu_do) _s.customContextMenu_do.disable();
			_s.stopVisualization();
			
			_s.hasStartedToPlay_bl = false;
		};
		
		_s.startToScrub = function(){
			if(!_s.isAPIReady_bl) return;
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do && _s.ytb_do.isSafeToBeControlled_bl){
				_s.ytb_do.startToScrub();
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.startToScrub();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.startToScrub();
			}else if(FWDEVPlayer.hasHTML5Video){
				if(_s.videoScreen_do) _s.videoScreen_do.startToScrub();
			}
			_s.dispatchEvent(FWDEVPlayer.START_TO_SCRUB);
		};
		
		_s.stopToScrub = function(){
			if(!_s.isAPIReady_bl) return;
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do && _s.ytb_do.isSafeToBeControlled_bl){
				_s.ytb_do.stopToScrub();
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.stopToScrub();
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.stopToScrub();
			}else if(FWDEVPlayer.hasHTML5Video){
				if(_s.videoScreen_do) _s.videoScreen_do.stopToScrub();
			}
			_s.dispatchEvent(FWDEVPlayer.STOP_TO_SCRUB);
		};
		
		_s.scrub = function(percent, time){
			if(!_s.isAPIReady_bl) return;
			if(isNaN(percent)) return;
			if(percent < 0){
				percent = 0;
			}else if(percent > 1){
				percent = 1;
			}
			
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do && _s.ytb_do.isSafeToBeControlled_bl){
				_s.ytb_do.scrub(percent);
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.scrub(percent);
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.scrub(percent);
			}else{
				if(_s.videoScreen_do) _s.videoScreen_do.scrub(percent);	
			}
	
			_s.dispatchEvent(FWDEVPlayer.SCRUB, {percent:percent});
		};
		
		_s.scrubbAtTime = function(duration){
			if(!_s.isAPIReady_bl || !duration) return;
			if(String(duration).indexOf(":") != -1) duration = FWDEVPUtils.getSecondsFromString(duration);

			if(_s.isCasting){
				_s.cc.scrubbAtTime(duration);
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do){
				_s.ytb_do.scrubbAtTime(duration);
			}else if(_s.videoType_str == FWDEVPlayer.VIMEO && _s.vimeo_do){
				_s.vimeo_do.scrubbAtTime(duration);
			}else if(_s.videoType_str == FWDEVPlayer.MP3){
				if(_s.audioScreen_do) _s.audioScreen_do.scrubbAtTime(duration);
			}else{
				if(_s.videoScreen_do) _s.videoScreen_do.scrubbAtTime(duration);
			}
		};
		
		_s.share = function(){
			if(!_s.isAPIReady_bl) return;
			_s.shareWindow_do.show();
		}
		
		
		_s.setVolume = function(volume, removeAutoPlay){
			if(!_s.isAPIReady_bl) return;
		
			if(_s.controller_do) _s.controller_do.updateVolume(volume, true);
			if(volume && removeAutoPlay){
				_s._d.autoPlay_bl = false;
				_s.removeAPT();
			} 
			_s.volume = volume;
			
			if(_s.isIMA && _s.IMA) _s.IMA.setVolume(volume);
			
			if(_s.ytb_do){
				_s.ytb_do.setVolume(_s.volume);
			}
			
			if(_s.vimeo_do){
				_s.vimeo_do.setVolume(_s.volume);
			}
			
			if(_s.audioScreen_do){
				_s.audioScreen_do.setVolume(_s.volume);
			}
			
			if(FWDEVPlayer.hasHTML5Video){
				_s.videoScreen_do.setVolume(_s.volume);
			}
			
			if(_s.isCasting){
				_s.cc.setVolume();
			}
				
			_s.dispatchEvent(FWDEVPlayer.VOLUME_SET, {volume:_s.volume});
		};
		
		_s.setPosterSource = function(path, o){
			_s.posterPath_str = path;
			if(!path){
				_s.videoPoster_do.curPath = '';
				_s.videoPoster_do.hide();
				return;
			}
			
			if(!_s.isAPIReady_bl) return;
			var path_ar = path.split(",");
				
			if(_s.isMobile_bl && path_ar[1] != undefined){
				path = path_ar[1];
			}else{
				path = path_ar[0];
			}
			
			if(path.indexOf("encrypt:") != -1) path = atob(path.substr(8));
			
			_s.videoPoster_do.setPoster(_s.posterPath_str, o);
			if(_s.prevPosterSource_str != path && !o) _s.dispatchEvent(FWDEVPlayer.UPDATE_POSTER_SOURCE);
			_s.prevPosterSource_str = path;
		};
		

		//#####################################################//
		/* Update ads */
		//#####################################################//
		_s.updateAds = function(duration, forceSource){
			
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE && !_s.ytb_do) return;
				
			if(_s._d.vastXML && !_s._d.isVastXMLParsed_bl){
				if(_s.controller_do){
					_s.controller_do.createdAdsOnce_bl = false;
					_s.controller_do.resetsAdsLines(true);
				}

				_s.lastCurTime = '00:00'
				_s._d.setVastSource(_s._d.vastXML);
				return;
			} 
			
			if(_s.isAdd_bl) return;

			if(!_s.isAdd_bl && _s._d.adsSource_ar){
				if(_s.controller_do){
					_s._d.fixVmapTimes(_s.totalDuration);
					if(_s.totalDuration){
						_s.controller_do.setupAdsLines(_s._d.adsSource_ar);
						_s.controller_do.positionAdsLines(_s.totalDuration);

						if(_s.popupAds_do){
							if(_s._d.popupAds_ar){
								_s.popupAds_do.resetPopups(_s._d.popupAds_ar);
							}
						}
					}
				}

				if(isNaN(duration)) duration = 0;
		
				for(var i=0; i<_s._d.adsSource_ar.length; i++){
					if(duration >= _s._d.adsSource_ar[i].timeStart && duration <= (_s._d.adsSource_ar[i].timeStart + 1) 
						&& !_s._d.adsSource_ar[i].played_bl/* && duration != _s.prevDuration*/){
						
						_s.isAdd_bl = true;
						if(_s._d.adsSource_ar[i].timeStart != 0) _s.wasAdd_bl = true;
						_s.addSource_str = _s._d.adsSource_ar[i].source;
						_s._d.adsSource_ar[i].played_bl = true;
						_s._d.adsThumbnailPath_str = _s._d.adsSource_ar[i].thumbnailSource;
						_s._d.timeToHoldAds = _s._d.adsSource_ar[i].timeToHoldAds;
						if(_s._d.timeToHoldAds){
							_s._d.showSkipButton_bl = true;
						}else{
							_s._d.showSkipButton_bl = false;
						}						
						_s._d.adsPageToOpenURL_str = _s._d.adsSource_ar[i].link;
						_s._d.adsPageToOpenTarget_str = _s._d.adsSource_ar[i].target;
						_s.scrubAfterAddDuration = _s._d.adsSource_ar[i].timeStart;
						_s.TrackingEvents = _s._d.adsSource_ar[i].TrackingEvents;

						_s.Impression = _s._d.adsSource_ar[i].Impression;
						_s.ClickTracking = _s._d.adsSource_ar[i].ClickTracking;
						
						if(_s.TrackingEvents){
							_s.Impression = _s.ClickTracking = _s.ClickThrough = true;
							_s.callFirstQuartile = _s.callMidpoint = _s.callThirdQuartile =  true;
						}

						_s.curImageTotalTime = _s._d.adsSource_ar[i].addDuration;
						if(!_s.isStopped_bl) _s.lastCurTime = _s.curTime
						if(!_s.lastCurTime) _s.lastCurTime = _s.getCurrentTime();
						_s.setSource(_s.addSource_str, true);
						 _s.prevVidSrc2 = '';
						
						if(_s.videoType_str != FWDEVPlayer.IMAGE && _s.videoType_str != FWDEVPlayer.IFRAME && !_s.isMobile_bl){
							_s.allowToPlay = false;
							if(_s.lastCurTime.substr(_s.lastCurTime.length -2) == "00"){
								
								if(_s.autoPlay_bl || _s.adDone_bl){
									if(_s.addSource_str.indexOf("youtube.") != -1 && _s.ytb_do && _s.ytb_do.hasBeenCreatedOnce_bl) _s.allowToPlay = true;
									if(_s.addSource_str.indexOf("youtube.") == -1 ) _s.allowToPlay = true;
								}
							}else{
								if(_s.addSource_str.indexOf("youtube.") != -1 && _s.ytb_do) _s.allowToPlay = true;
								_s.allowToPlay = true;
							}
							if(_s.allowToPlay) _s.play();
						}
						_s.adDone_bl = false;
						if(_s.controller_do && _s.controller_do.line_ar){
							if(_s.controller_do.line_ar[i]){
								_s.controller_do.line_ar[i].setVisible(false);
								_s.controller_do.line_ar[i].isUsed_bl = true;
							}
						}
						break;
					}
				}
			}
			_s.isLive = _s._d.videosSource_ar[_s._d.startAtVideoSource]["isLive"];
			

			if(!_s.isAdd_bl){
				var dSrc = _s._d.videosSource_ar[_s._d.startAtVideoSource];
				var curVidSrc = dSrc["source"];
				if(curVidSrc != _s.prevVidSrc || forceSource){
					_s.TrackingEvents = _s.Impression = _s.ClickTracking = _s.ClickThrough = undefined;
					_s.callFirstQuartile = _s.callMidpoint = _s.callThirdQuartile = undefined;
					_s.playSecondSource = false;
					_s.videoSource2_str = undefined;
					_s.setSource(dSrc["source"], false, _s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"]);
					_s.prevVidSrc = curVidSrc;
				}else{
					var source = dSrc["source"];
					if(_s.videoSource2_str) source = _s.videoSource2_str;
					if(source != _s.prevVidSrc2){
						_s.setSource(source, false, _s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"]);
						_s.prevVidSrc2 = source;
					}
				}
			}

			if(_s.controller_do) _s.controller_do.positionAdsLines(_s.curDuration);
			_s.prevDuration = duration;
		};
		

		//#####################################################//
		/* Setup image screen */
		//#####################################################//
		_s.updateImageScreen = function(source){
			
			if(_s.videoType_str == FWDEVPlayer.IFRAME){
				if(!_s.iFrame_do){		
					_s.iFrame_do = new FWDEVPDO("iframe");
					_s.iFrame_do.hasT3D = false;
					_s.iFrame_do.hasT2D = false;
					_s.iFrame_do.setBackfaceVisibility();
				}
				
				_s.main_do.addChildAt(_s.iFrame_do, _s.main_do.getChildIndex(_s.dClk_do) + 1);
				_s.showClickScreen();
				
				_s.iFrame_do.screen.src = source;
				_s.positionAdsImage();
				_s.startToUpdateAdsButton();
				return;
			}
			
			if(!_s.imageSceeenHolder_do){
				_s.imageSceeenHolder_do = new FWDEVPDO("div");
				_s.imageSceeenHolder_do.setX(0);
				_s.imageSceeenHolder_do.setY(0);
				_s.imageSceeenHolder_do.setBkColor("#000000");
			}
			
			_s.main_do.addChildAt(_s.imageSceeenHolder_do,  _s.main_do.getChildIndex(_s.dClk_do) - 1);
			_s.showClickScreen();
			if(_s.imageSceeenHolder_do.contains(_s.imageScreen_do)) _s.imageSceeenHolder_do.removeChild(_s.imageScreen_do);
			_s.imageScreen_do = null;
			
			_s.imageScreen_do = new FWDEVPDO("img");
			
			_s.imageAdd_img = new Image()
			_s.imageAdd_img.src = source;
		
			if(_s.showPreloader_bl){
				_s.preloader_do.show(false);
				_s.preloader_do.startPreloader();
			}
			if(_s.largePlayButton_do) _s.hideLargePlayButton();
			
			_s.imageAdd_img.onload = function(){
				_s.imageScreen_do.setScreen(_s.imageAdd_img);
				_s.imageScreen_do.setAlpha(0);
				FWDAnimation.to(_s.imageScreen_do, 1, {alpha:1});
				_s.imageAddOriginalWidth = _s.imageAdd_img.width;
				_s.imageAddOriginalHeight = _s.imageAdd_img.height;
				_s.preloader_do.hide(false);
				_s.preloader_do.stopPreloader();
				_s.imageSceeenHolder_do.addChild(_s.imageScreen_do);
				_s.positionAdsImage();
				_s.startToUpdateAdsButton();
			}
			
			_s.imageAdd_img.onerror = function(){
				_s.main_do.addChild(_s.info_do);
				_s.info_do.showText("Advertisment image with path " +  source + " can't be found");
				_s.preloader_do.hide(false);
				_s.preloader_do.stopPreloader();
				return;
			}
		}
		
		_s.positionAdsImage = function(){
			
			if(_s.videoType_str == FWDEVPlayer.IFRAME && _s.iFrame_do){
				_s.iFrame_do.setWidth(_s.sW);
				_s.iFrame_do.setHeight(_s.sH);
		
			}
			
			if(!_s.imageScreen_do || _s.videoType_str != FWDEVPlayer.IMAGE) return;
			var scaleX = _s.sW/_s.imageAddOriginalWidth;
			var scaleY = _s.sH/_s.imageAddOriginalHeight;
			
			var totalScale = 0;
			if(scaleX >= scaleY){
				totalScale = scaleX;
			}else if(scaleX <= scaleY){
				totalScale = scaleY;
			}
			
			var finalW = parseInt(_s.imageAddOriginalWidth * totalScale);
			var finalH = parseInt(_s.imageAddOriginalHeight * totalScale);
			var finalX = parseInt((_s.sW - finalW)/2);
			var finalY = parseInt((_s.sH - finalH)/2);
			
			_s.imageScreen_do.setWidth(finalW); 
			_s.imageScreen_do.setHeight(finalH); 
			_s.imageScreen_do.setX(finalX); 
			_s.imageScreen_do.setY(finalY); 
			_s.imageSceeenHolder_do.setWidth(_s.sW);
			_s.imageSceeenHolder_do.setHeight(_s.sH);
		}
		
		_s.startToUpdateAdsButton = function(){
			_s.curImageTime = 0;
			_s.updateAdsButton();
			_s.stopUpdateImageInterval();
			_s.startUpdateImageInterval();
			_s.setPlayAndPauseButtonState();	
		}
		
		_s.stopUpdateImageInterval = function(){
			_s.isImageAdsPlaying_bl = false;
			
			clearInterval(_s.startUpdateAdsId_int);
			_s.setPlayAndPauseButtonState();
			if(_s.largePlayButton_do && !_s.useWithoutVideoScreen_bl) _s.showLargePlayButton();
			_s.isPlaying_bl = false;
			
			_s.hider.stop();	
		}
		
		_s.startUpdateImageInterval = function(){
			_s.isImageAdsPlaying_bl = true;
			_s.startUpdateAdsId_int = setInterval(_s.updateAdsButton, 1000);
			_s.setPlayAndPauseButtonState();
			if(_s.largePlayButton_do) _s.hideLargePlayButton();
			_s.isPlaying_bl = true;
			_s.hider.start();
		}
		
		_s.updateAdsButton = function(){
			
			_s.videoScreenUpdateTimeHandler({curTime:FWDEVPUtils.formatTime(_s.curImageTime), totalTime:FWDEVPUtils.formatTime(_s.curImageTotalTime), seconds:_s.curImageTime});
			_s.videoScreenUpdateHandler({percent:_s.curImageTime/_s.curImageTotalTime});
			if(_s.curImageTime == _s.curImageTotalTime) _s.videoScreenPlayCompleteHandler();
			_s.curImageTime += 1;
		}
		
		_s.setPlayAndPauseButtonState = function(){
			if(_s.isImageAdsPlaying_bl){
				if(_s.controller_do) _s.controller_do.showPauseButton();
			}else{
				if(_s.controller_do) _s.controller_do.showPlayButton();
			}
		}
		

		// DASH
		_s.setupDASH = function(){
			if(_s.dashJS || !window['dashjs']) return;
		
			_s.isDASHLoaded_bl = true;
			_s.dashJS = dashjs.MediaPlayer().create();

			_s.dashJS.on(dashjs.MediaPlayer.events.ERROR, function(e){

				if(_s.checkSecondSource()){
					return;
				}
				_s.main_do.addChild(_s.info_do);
				_s.info_do.showText(e.error.message);
			});
			_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_DONE);
		}

		_s.destroyDASH = function(){
			if(_s.dashJS){
				try{
					_s.dashJS.reset();
				}catch(e){}
				_s.dashJS = null;
			}
		}


		// Setup HLS.
		_s.isThreeJsLoaded_bl = false;
		_s.isThreeJsOrbitLoaded_bl = false;
		_s.load360ScriptsId_to;
		_s.isHLSJsLoaded_bl = false;
		
		_s.destroyHLS = function(){
			if(_s.hlsJS){
				_s.hlsJS.destroy();
				_s.hlsJS = null;
			}
		}
		
		_s.setupHLS = function(){
			if(_s.hlsJS || !window['Hls']) return;
			_s.isHLSJsLoaded_bl = true;
			_s.hlsJS = new Hls({autoLevelEnabled:true});
			FWDEVPRegisterHLSError(_s);
			_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_DONE);

			_s.hlsJS.on(Hls.Events.MANIFEST_LOADED,function(event, data){
				_s.setAudioTracks(_s.hlsJS.audioTracks);
			});

			_s.hlsJS.on(Hls.Events.LEVEL_UPDATED,function(event, data){
				_s.curHlsLevel = data.level;
				_s.updateHlsControllerLevels();
			});

			_s.hlsJS.on(Hls.Events.LEVEL_SWITCHING,function(event, data){
				_s.curHlsLevel = data.level;
				_s.updateHlsControllerLevels();
			});
		}

		_s.setAudioTracks = function(audioTracks_ar){
			if(audioTracks_ar && audioTracks_ar.length > 1){
				_s.controller_do.addAtButton();

				_s.audioTracks_ar = [];
				audioTracks_ar.forEach((o,i) =>{
					_s.audioTracks_ar[i] = {'label':o['name'] || o['label']};
				})
				_s.audioTracks_ar.reverse();
				_s.controller_do.updateATBButtons(_s.audioTracks_ar, 0);
			}
		}

		_s.updateHlsControllerLevels = function(){

			if(_s.controller_do && _s.hlsLevels_ar){
				_s.curHlsLevel = Math.abs(_s.curHlsLevel - _s.hlsLevels_ar.length + 1)
				_s.controller_do.disableQualityButtons('', _s.curHlsLevel);
			}
		}

		_s.parseLevels = function(){
			var hlsLevels = _s.hlsJS.levels;
			
			_s.parseQualityLevelsId_to = setTimeout(function(){

				_s.hlsLevels_ar = [];
				
				hlsLevels.forEach(function(el){
					var level = '';
					var levelW = el.width;
					var levelH = el.height;
					if(levelW == 320){
						level = 'tiny';
					}else if(levelW == 480){
						level = 'small';
					}else if(levelW == 640){
						level = 'medium';
					}else if(levelW == 960){
						level = 'large';
					}else if(levelW == 1280){
						level = 'hd720';
					}else if(levelW == 1920){
						level = 'hd1080';
					}else if(levelW == 2560){
						level = 'hd1440p';
					}else if(levelW == 3840){
						level = 'hd2160';
					}else if(levelW == 3840){
						level = 'hd2160';
					}else if(levelW == 5120){
						level = 'hd2880';
					}else if(levelW == 7680){
						level = 'hd4320';
					}else{
						level = levelW + 'x' + levelH;
					}

					_s.hlsLevels_ar.push(level);					
				});
				
				_s.hlsLevels_ar.reverse();
			
				if(_s.controller_do){
					_s.curHlsLevel = Math.abs(_s.hlsLevels_ar.length - _s.hlsLevels_ar.length);
					_s.curHlsLevel = _s.curHlsLevel;
					_s.controller_do.updateQuality(_s.hlsLevels_ar, _s.curHlsLevel, _s.curHlsLevel);
				}
			}, 500);
		
		}
	

		//#####################################################//
		/* set source */
		//#####################################################//
		_s.setSource = function(source, overwrite, videoType, source2){
			
			if(!_s.isAPIReady_bl || !source) return;

			source = source.replace(/&amp;/g, "&");

			_s.currentSecconds = 0;
			clearInterval(_s.tryHLS_int);
			clearTimeout(_s.load360ScriptsId_to);
			if(source.indexOf("encrypt:") != -1) source = atob(source.substr(8));
			if(source == _s.prevVideoSource_str && !overwrite) return;
			_s.prevVideoSource_str = source;
			
			_s.videoSource_str = source;
			_s.videoSourcePath_str = source;
			_s.finalVideoPath_str = source;
			_s.videoType = videoType;
			
			if(_s.main_do.contains(_s.info_do)){
				_s.main_do.removeChild(_s.info_do);
			}
			
			_s.stop();
	
			if(_s.controller_do) _s.controller_do.setIsLive(_s.isLive);
		
			if(_s.videoSourcePath_str.indexOf("vimeo.com") != -1 &&
			   source.indexOf(".mp4") == -1 && source.indexOf(".m3u8") == -1){
				_s.videoType_str = FWDEVPlayer.VIMEO;
			}else if(_s.videoSourcePath_str.indexOf("youtube.") != -1){
				_s.videoType_str = FWDEVPlayer.YOUTUBE;
				if(_s.controller_do) _s.controller_do.setX(0);
			}else if(_s.videoSourcePath_str.indexOf(".jpg") != -1 
					|| _s.videoSourcePath_str.indexOf(".jpeg") != -1 
					|| _s.videoSourcePath_str.indexOf(".png") != -1
			){
				_s.videoType_str = FWDEVPlayer.IMAGE;
				if(_s.controller_do) _s.controller_do.setX(0);
			}else if(source.match(/\.mp3|\.m4a|\.acc/ig)){
				_s.videoType_str = FWDEVPlayer.MP3;
				if(_s.controller_do) _s.controller_do.setX(0);
			}else if(!source.match(/\.mpd|\.m3u8|\.mp4|\.mov|google.com|lh3.|myqnapcloud/ig)){
				_s.videoType_str = FWDEVPlayer.IFRAME;
				if(_s.controller_do) _s.controller_do.setX(0);
			}else{
				if(_s.controller_do) _s.controller_do.setX(0);
				if(!FWDEVPUtils.isIOS && _s.videoSourcePath_str.indexOf(".m3u8") != -1){
					_s.videoType_str = FWDEVPlayer.HLS_JS;
				}else if(source.indexOf(".mpd") != -1){
					_s.videoType_str = FWDEVPlayer.DASH;
				}else{
					_s.videoType_str = FWDEVPlayer.VIDEO;
				}
			}


			// IMA
			var isIMA = _s._d.imaURL;
			if(_s.videoType_str != FWDEVPlayer.VIDEO || _s.errorImaSDK) isIMA = false;
			
			if(isIMA){
				_s.isIMA = isIMA;
				if(!_s._d.imaReady){
					_s._d.startToLoadIMA();
					return;
				}
				
				if(!_s.IMA){
					FWDEVPIMA.setPrototype();
					_s.IMA = new FWDEVPIMA(_s);
				}
			}
			if(!_s.IMA) _s.isIMA = false;

			if(!isIMA){
				if(_s.IMA) _s.IMA.stop();
				_s.prevIsIMA = '';
			}


			_s.isGR = false;
			_s.is360 = false;
			
			if(videoType){
				if(videoType.toLowerCase() == "360degreevideo"){
					_s.isGR = false;
					_s.is360 = true;
				}else if(videoType.toLowerCase() == "greenscreenvideo"){
					_s.isGR = true;
					_s.is360 = false;
				}
			}
			
			if(_s.isGR){
				_s.main_do.setBkColor("transparent");
				_s.videoScreen_do.setBkColor("transparent");
			}else{
				_s.main_do.setBkColor(_s.backgroundColor_str);
				_s.videoScreen_do.setBkColor(_s.backgroundColor_str);
			}

			
			// Casting
			if(_s.cc) _s.cc.checkButtonState();
			if(_s.vimeo_do) _s.vimeo_do.setX(-5000);
			if(_s.ytb_do) _s.ytb_do.setX(-5000);
			if(_s.videoScreen_do) _s.videoScreen_do.setX(-5000);
			if(_s.audioScreen_do) _s.audioScreen_do.setX(-5000);
			_s.audioScreen_do.setVisible(false);
			
			// Youtube.
			if(_s.videoSource_str.indexOf("youtube.") != -1 && !_s.isYoutubeReady_bl){
				setTimeout(function(){					
					if(_s.showPreloader_bl){
						_s.main_do.addChild(_s.preloader_do);	
						_s.preloader_do.show(false);
						_s.preloader_do.startPreloader();
						if(_s.largePlayButton_do) _s.hideLargePlayButton();
						
						if(location.protocol.indexOf("file:") != -1 && FWDEVPUtils.isIE) _s.main_do.addChild(_s.info_do);
					}
				}, 50);
				
				if(location.protocol.indexOf("file:") != -1 && FWDEVPUtils.isIE){
					_s.info_do.allowToRemove_bl = false;
					var error = "This browser dosen't allow the Youtube API to run local, please test it online or in another browser like Firefox or Chrome."
					_s.displayError(error);
					_s.resizeHandler();
					return;
				}	
				
				_s.setupYoutubeAPI();
				return;
			}
			
			// Vimeo.
			if(source.indexOf("vimeo.") != -1 && !_s.vimeo_do && _s.videoType_str == FWDEVPlayer.VIMEO){
					
				if(location.protocol.indexOf("file:") != -1){
					var error = "This browser dosen't allow playing Vimeo videos local, please test online.";
					_s.displayError(error);
					return;
				}

				if(_s.showPreloader_bl){
					_s.main_do.addChild(_s.preloader_do);	
					_s.preloader_do.show(false);
					_s.preloader_do.startPreloader();
				}
				if(_s.largePlayButton_do) _s.hideLargePlayButton();
			
				_s.setupVimeoAPI();
				return;
			}
			
			
			//DASH
			if(source.indexOf(".mpd") != -1 && !_s.isDASHLoaded_bl && !FWDEVPlayer.isDASHLoaded_bl){
				
				if(location.protocol.indexOf("file:") != -1){
					var error = "This browser doesn't allow playing MPEG DASH videos local, please test online.";
					_s.displayError(error);
					return;
				}
				_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_LOAD);
				
				var script = document.createElement('script');
				script.src = _s._d.dashPath_str;
				document.head.appendChild(script);
				script.onerror = function(){
					var error = "Error loading MPEG DASH library <font color='#FF0000'>" + _s._d.dashPath_str + "</font>.";
					_s.displayError(error);
					return;
				}

				script.onload = function () {
					_s.isDASHLoaded_bl = true;
					FWDEVPlayer.isDASHLoaded_bl = true;
					_s.setupDASH();
					_s.setSource(source, true, _s.is360);
				}

				if(!_s.autoPlay_bl){
					_s.setPosterSource(_s.posterPath_str);
					if(_s.videoPoster_do) _s.videoPoster_do.show();
					if(_s.lrgPlayBtn) _s.lrgPlayBtn.show();
				}
				return;
			}

			// HLS.
			if(!FWDEVPUtils.isIOS && _s.videoSourcePath_str.indexOf(".m3u8") != -1 
				&& !_s.isHLSJsLoaded_bl && !FWDEVPlayer.isHLSJsLoaded_bl
			){
				
				// if(location.protocol.indexOf("file:") != -1){
				// 	_s.info_do.allowToRemove_bl = false;
				// 	var error = "This browser dosen't allow playing HLS / live streaming videos local, please test online.";
				// 	_s.displayError(error);
				// 	return;
				// }

				_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_LOAD);
				
				var script = document.createElement('script');
				script.src = _s._d.hlsPath_str;
				script.onerror = function(){
					_s.main_do.addChild(_s.info_do);
					var error = "Error loading HLS library <font color='#FF0000'>" + _s._d.hlsPath_str + "</font>.";
					_s.displayError(error);
					return;
				}
				
				script.onload = function () {
					_s.isHLSJsLoaded_bl = true;
					FWDEVPlayer.isHLSJsLoaded_bl = true;
					_s.setupHLS();
					_s.setSource(_s.videoSourcePath_str, true,_s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"]);

					
				}
				document.head.appendChild(script);
				return;
			}
			
			// 360
			if(_s.is360 && !_s.isThreeJsOrbigLoaded_bl){
				
				if(FWDEVPUtils.isLocal){
					_s.main_do.addChild(_s.info_do);
					var error = "This browser dosen't allow playing 360 videos local, please test online."
					_s.displayError(error);
					return;
				}

			
				if(!FWDEVPUtils.hasWEBGL){
					_s.main_do.addChild(_s.info_do);
					var error = "Playing 360 videos in this browser is not possible because it dosen't support WEBGL."
					_s.displayError(error);
					return;
				}
			
				if(!_s.isThreeJsLoaded_bl && !FWDEVPlayer.hasThreeJsLoaded_bl){
					_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_LOAD);
					var script = document.createElement('script');
					script.src = _s._d.threeJsPath_str;
					script.onerror = function(){
						var error = "Error loading 360 degree library <font color='#FF0000'>" + _s._d.threeJsPath_str + "</font>.";
						_s.displayError(error);
						return;
					}

					script.onload = function () {
						_s.isThreeJsOrbigLoaded_bl = true;
						
						var script2 = document.createElement('script');
						script2.src = _s._d.threeJsControlsPath_str;
						script2.onerror = function(){
							var error = "Error loading three.js from <font color='#FF0000'>" + _s._d.threeJsControlsPath_str + "</font>."
							_s.displayError(error);
							return;
						}

						script2.onload = function () {
							FWDEVPlayer.hasThreeJsLoaded_bl = true;
							_s.isThreeJsOrbitLoaded_bl = true;
							var dSrc = _s._d.videosSource_ar[_s._d.startAtVideoSource];
							if(_s.isThreeJsOrbigLoaded_bl && _s.isThreeJsOrbitLoaded_bl) _s.setSource(dSrc["source"],true, dSrc["videoType"], dSrc['source2']);
							clearTimeout(_s.load360ScriptsId_to);
							_s.preloader_do.hide(false);
							_s.preloader_do.stopPreloader();
							_s.dispatchEvent(FWDEVPlayer.FRAMEWORK_DONE);
						};
						document.head.appendChild(script2); 
							
						};

						document.head.appendChild(script);
						
						_s.load360ScriptsId_to = setTimeout(function(){
							if(_s.showPreloader_bl){
								_s.preloader_do.show(false);
								_s.preloader_do.startPreloader();
							}
						},1000);
					return;
				}
			}
			
			if(_s.is360){
				_s.dClk_do.style().cursor = 'url(' + _s._d.handPath_str + '), default';
			}else{
				_s.dClk_do.style().cursor = "auto";
				_s.dispatchEvent(FWDEVPlayer.SHOW_CURSOR)
			}
			
			if(!source){
				_s.main_do.addChild(_s.info_do);
				_s.info_do.showText("Video source is not defined!");
				return;
			}
			
			if(source.indexOf("youtube.") != -1){
				var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
				source = source.match(regExp)[2];
			}
			
			if(_s.controller_do && _s._d.subtitles_ar && _s._d.subtitles_ar.length > 1){
				_s.controller_do.updateSubtitleButtons(_s._d.subtitles_ar, _s._d.startAtSubtitle);
				_s._d.subtitlePath_str = _s._d.subtitles_ar[_s._d.subtitles_ar.length - 1 - _s._d.startAtSubtitle]["source"];
				_s.ccSS = _s._d.startAtSubtitle;
			}
			_s.subtitle_do.stopToLoadSubtitle();
			
			if(_s.controller_do && !_s.isQualityChanging_bl) _s.controller_do.disableSubtitleButton();
			if(_s.controller_do && _s.controller_do.rewindButton_do) _s.controller_do.rewindButton_do.disable();
			
			if(_s._d.scrubAtTimeAtFirstPlay != "00:00:00") _s.playAtTime_bl = true;
			
			if(_s.controller_do){
				_s.controller_do.updateHexColorForScrubber(_s.isAdd_bl);
				_s.controller_do.removeAtButton();
			} 
				
			_s.resizeHandler();
			if(_s.getVideoSource()) _s.dispatchEvent(FWDEVPlayer.UPDATE_VIDEO_SOURCE);
	
			//Image
			if(_s.videoType_str == FWDEVPlayer.IMAGE || _s.videoType_str == FWDEVPlayer.IFRAME){
				_s.updateImageScreen(_s.videoSourcePath_str);
				if(_s.videoPoster_do) _s.videoPoster_do.setX(-5000);
				_s.wasAdd_bl = false;
				return;
			}else{
				if(_s.main_do.contains(_s.imageSceeenHolder_do)) _s.main_do.removeChild(_s.imageSceeenHolder_do);
				if(_s.main_do.contains(_s.iFrame_do)) _s.main_do.removeChild(_s.iFrame_do);
		   		if(_s.videoPoster_do) _s.videoPoster_do.setX(0);
		   		_s.hideClickScreen();
			}
			
			if(_s.isAdd_bl){
				_s.setPlaybackRate(1);
			}else{
				_s.setPlaybackRate(_s._d.defaultPlaybackRate_ar[_s._d.startAtPlaybackIndex]);
			}
			
			if(_s.controller_do){
				if(_s.videoType_str == FWDEVPlayer.VIMEO
					|| _s.videoType_str == FWDEVPlayer.IMAGE
					|| _s.videoType_str == FWDEVPlayer.IFRAME
				){
					_s.controller_do.removePlaybackRateButton();
				}else{
					_s.controller_do.addPlaybackRateButton();
				}

				if(_s.is360 && _s._d.show360DegreeVideoVrButton_bl){
					_s.controller_do.addVrButton();
				}else{
					_s.controller_do.removeVrButton();
				}
			}
			
			if(_s.controller_do && _s._d.showPlaybackRateButton_bl){
				_s.controller_do.updatePlaybackRateButtons(_s._d.updatePlaybackRateButtons, _s._d.startAtPlaybackIndex);
			}


			//Vimeo
			if(_s.videoType_str == FWDEVPlayer.VIMEO){
				
				if(_s.ytb_do && _s.ytb_do.ytb ){
					_s.ytb_do.showDisable();
				}
				
				if(_s.controller_do){
					_s.controller_do.removeYtbQualityButton();
				}

				_s.vimeo_do.showDisable();
				_s.vimeo_do.setSource(source);
				_s.setPosterSource(_s.posterPath_str);
			
				if(_s.largePlayButton_do && !_s._d.showAnnotationsPositionTool_bl && !_s.useWithoutVideoScreen_bl){
					 _s.hideLargePlayButton();
					 _s.showLargePlayButton();
				}

				if(_s._d.autoPlay_bl){
					if(_s.controller_do) _s.controller_do.updateVolume(0);
				}
			
				
				if(_s.getVideoSource()) _s.dispatchEvent(FWDEVPlayer.UPDATE_VIDEO_SOURCE);
				_s.resizeHandler();
				if(_s.vimeo_do.iFrame_do) _s.vimeo_do.iFrame_do.screen.style.left = "0px";
				_s.vimeo_do.setX(0);
				_s.wasAdd_bl = false;
				return;
			}
				
			//Youtube
			if(_s.videoType_str == FWDEVPlayer.YOUTUBE && _s.ytb_do && _s.ytb_do.ytb &&  _s.ytb_do.ytb.cueVideoById){
			
				if(_s.ytb_do && _s.ytb_do.ytb && !_s.is360){
					_s.ytb_do.showDisable();
				}else{
					_s.ytb_do.hideDisable();
				}

				_s.ytb_do.setX(0);
				if(_s._d.aom_bl && _s.controller_do) _s.controller_do.updateVolume(0);
				_s.ytb_do.setSource(source);
				
				_s.setPosterSource(_s.posterPath_str);
				if(_s.largePlayButton_do  && !_s._d.showAnnotationsPositionTool_bl && !_s.useWithoutVideoScreen_bl){
					 _s.hideLargePlayButton();
					 _s.showLargePlayButton();
				}
				
				_s.playSecondSource = false;
				
				if(_s.controller_do){
					_s.controller_do.updatePreloaderBar(0);
					_s.controller_do.addYtbQualityButton();
				}
				_s.wasAdd_bl = false;
				if(_s.getVideoSource()) _s.dispatchEvent(FWDEVPlayer.UPDATE_VIDEO_SOURCE);
				return;
			}
			
			_s.finalVideoPath_str = source;
			
			// Mp3.
			if(_s.videoType_str == FWDEVPlayer.MP3){
				_s._d.autoPlay_bl = false;
				_s.setPosterSource(_s.posterPath_str);
				if(_s.ytb_do && _s.ytb_do.ytb){
					_s.ytb_do.showDisable();
				}
				
				if(_s.largePlayButton_do  && !_s._d.showAnnotationsPositionTool_bl && !_s.useWithoutVideoScreen_bl){
					 _s.hideLargePlayButton();
					 _s.showLargePlayButton();
				}

				_s.audioScreen_do.setX(0);
				_s.audioScreen_do.setVisible(true);
				
				if(_s.showPreloader_bl){
					_s.preloader_do.hide(false);
					_s.preloader_do.stopPreloader();
				}
			
				_s.audioScreen_do.setSource(source);
				if(_s.playSecondSource){
					_s.play();
				} 
			
				if(_s.controller_do && _s._d.videosSource_ar && _s._d.videosSource_ar.length > 1){
					_s.controller_do.updatePreloaderBar(0);					
					_s.controller_do.addYtbQualityButton();	
					_s.controller_do.updateQuality(_s._d.videoLabels_ar, _s._d.videoLabels_ar[_s._d.videoLabels_ar.length - 1 - _s._d.startAtVideoSource]);
				}else if(_s.controller_do){	
					_s.controller_do.removeYtbQualityButton();
				}
				_s.wasAdd_bl = false;
				return;
			}
			
			// Video.		
			if(FWDEVPlayer.hasHTML5Video && _s.videoType_str == FWDEVPlayer.VIDEO
			  || _s.videoType_str == FWDEVPlayer.HLS_JS
			  || _s.videoType_str == FWDEVPlayer.DASH
			){
				
				_s.setPosterSource(_s.posterPath_str);
				
				if(_s.ytb_do && _s.ytb_do.ytb){
					_s.ytb_do.showDisable();
				}

				if(_s.largePlayButton_do  && !_s._d.showAnnotationsPositionTool_bl && !_s.useWithoutVideoScreen_bl){
					 _s.hideLargePlayButton();
					 _s.showLargePlayButton();
				}
				
				_s.videoScreen_do.setX(0)
				_s.videoScreen_do.setVisible(true);
				if(_s.showPreloader_bl){
					_s.preloader_do.hide(false);
					_s.preloader_do.stopPreloader();
				}
			
				if(_s.videoType_str == FWDEVPlayer.DASH){
					_s.videoScreen_do.setSource(source);
					_s.videoScreen_do.initVideo();
					_s.setupDASH();

					_s.dashJS.initialize(_s.videoScreen_do.video_el, _s.videoSourcePath_str, false);
                 	_s.dashJS.attachSource(_s.videoSourcePath_str);

                 	_s.dashJS.on(dashjs.MediaPlayer.events.MANIFEST_LOADED, function(e){
                 		_s.isDASHManifestReady_bl = true;
                 
                 		if(_s._d.autoPlay_bl
                 			|| (_s.playSecondSource && _s.playStarted)
                 			|| (!_s.isMobile_bl && _s.wasAdd_bl)
                 		 ){
                 		 	if(_s._d.autoPlay_bl) _s.controller_do.updateVolume(0);
							if(_s.videoType_str == FWDEVPlayer.DASH){
								if( _s.displayType != FWDEVPlayer.LIGHTBOX  || _s.lightBox_do.showComplete_bl){
									setTimeout(_s.play, 100);
								} 
								setTimeout(_s.play, 100);
							}
							_s.playStarted = _s.playSecondSource = false;
						}

						if(_s.isAdd_bl){
							_s.setPlaybackRate(1);
						}else{
							_s.setPlaybackRate(_s._d.defaultPlaybackRate_ar[_s.startAtPlaybackIndex]);
						}
						if(_s.controller_do && _s._d.showPlaybackRateButton_bl){
							_s.controller_do.updatePlaybackRateButtons(_s.startAtPlaybackIndex);
						}
                 	});
					
				}else if(_s.videoType_str == FWDEVPlayer.HLS_JS){
					_s.videoScreen_do.setSource(source);
					_s.videoScreen_do.initVideo();
					_s.setupHLS();
					_s.hlsJS.loadSource(_s.videoSourcePath_str);
					_s.hlsJS.attachMedia(_s.videoScreen_do.video_el);


					
					_s.hlsJS.on(Hls.Events.MANIFEST_PARSED,function(e){
						_s.isHLSManifestReady_bl = true;
						if(_s.controller_do){
							_s.controller_do.addYtbQualityButton();
						}

						if(_s._d.autoPlay_bl || _s.wasAdHLS){
							if(_s._d.autoPlay_bl) _s.controller_do.updateVolume(0);
							if(_s.displayType != FWDEVPlayer.LIGHTBOX  || _s.lightBox_do.showComplete_bl){
								_s.play();
							} 
						}

						_s.dispatchEvent(FWDEVPlayer.MANIFEST_PARSED_PLAY);

						_s.parseLevels();
						_s.wasAdHLS = false;
					});
				}else{
					_s.videoScreen_do.setSource(source);
					if(_s._d.autoPlay_bl){
						if(_s.controller_do) _s.controller_do.updateVolume(0);
						if(_s.displayType != FWDEVPlayer.LIGHTBOX  || _s.lightBox_do.showComplete_bl){
							_s.play();
						} 
					} 

					if(_s.isIMA){
						if(_s.prevIsIMA != _s.isIMA){
							_s.IMA.setSource(_s.isIMA);
						}
						_s.prevIsIMA = _s.isIMA;
					} 
					_s.wasAdd_bl = false;
				}
				
				if(_s.controller_do && _s._d.videosSource_ar && _s._d.videosSource_ar.length > 1){
					_s.controller_do.updatePreloaderBar(0);
					_s.controller_do.addYtbQualityButton();
					_s.controller_do.updateQuality(_s._d.videoLabels_ar, _s._d.videoLabels_ar[_s._d.videoLabels_ar.length - 1 - _s._d.startAtVideoSource]);
				}else if(_s.controller_do){
					_s.controller_do.removeYtbQualityButton();
				}
				
			}
			_s.prevVideoSourcePath_str = _s.videoSourcePath_str;
		};
		
	
		//#############################################//
		/* go fullscreen / normal screen */
		//#############################################//
		_s.goFullScreen = function(){
			if(!_s.isAPIReady_bl || _s.displayType ==  FWDEVPlayer.BACKGROUND_VIDEO) return;
			
			if(document.addEventListener){
				document.addEventListener("fullscreenchange", _s.onFullScreenChange);
				document.addEventListener("mozfullscreenchange", _s.onFullScreenChange);
				document.addEventListener("webkitfullscreenchange", _s.onFullScreenChange);
				document.addEventListener("MSFullscreenChange", _s.onFullScreenChange);
			}
			
			if(document.documentElement.requestFullScreen) {
				_s.main_do.screen.documentElement.requestFullScreen();
			}else if(document.documentElement.mozRequestFullScreen){ 
				_s.main_do.screen.mozRequestFullScreen();
			}else if(document.documentElement.webkitRequestFullScreen){
				_s.main_do.screen.webkitRequestFullScreen();
			}else if(document.documentElement.msRequestFullscreen){
				_s.main_do.screen.msRequestFullscreen();
			}
			
			_s.stopVisualization();
			_s.callVastEvent("playerExpand");
			_s.callVastEvent("fullscreen");
			_s.disableClick();
			if(_s.customContextMenu_do) _s.customContextMenu_do.updateFullScreenButton(1);
			_s.main_do.style().position = "fixed";
			document.documentElement.style.overflow = "hidden";
			_s.main_do.style().zIndex = 9999999999998;
		
			_s.isFullScreen_bl = true;
			if(_s.controller_do){
				_s.controller_do.showNormalScreenButton();
				_s.controller_do.setNormalStateToFullScreenButton();
			}
			
			var scrollOffsets = FWDEVPUtils.getScrollOffsets();
			_s.lastX = scrollOffsets.x;
			_s.lastY = scrollOffsets.y;
			
			window.scrollTo(0,0);
		
			if(_s.isMobile_bl) window.addEventListener("touchmove", _s.disableFullScreenOnMobileHandler, {passive:false});
			_s.dispatchEvent(FWDEVPlayer.GO_FULLSCREEN);
			_s.resizeHandler();
			setTimeout(function(){
				_s.resizeHandler();
			}, 300);
			setTimeout(function(){
				_s.resizeHandler();
			}, 600);
		};
		
		_s.disableFullScreenOnMobileHandler = function(e){
			if(e.preventDefault) e.preventDefault();
		};
		
		_s.goNormalScreen = function(){		
			if(!_s.isAPIReady_bl || _s.displayType == FWDEVPlayer.BACKGROUND_VIDEO || !_s.isFullScreen_bl) return;
			
			if (document.cancelFullScreen) {  
				document.cancelFullScreen();  
			}else if (document.mozCancelFullScreen) {  
				document.mozCancelFullScreen();  
			}else if (document.webkitCancelFullScreen) {  
				document.webkitCancelFullScreen();  
			}else if (document.msExitFullscreen) {  
				document.msExitFullscreen();  
			}
		
			
			_s.addMainDoToTheOriginalParent();
			_s.isFullScreen_bl = false;
		};
		
		_s.addMainDoToTheOriginalParent = function(o){
			if(!_s.isFullScreen_bl && !o) return;
			
			_s.isFullScreen_bl = false;

			if(document.removeEventListener){
				document.removeEventListener("fullscreenchange", _s.onFullScreenChange);
				document.removeEventListener("mozfullscreenchange", _s.onFullScreenChange);
				document.removeEventListener("webkitfullscreenchange", _s.onFullScreenChange);
				document.removeEventListener("MSFullscreenChange", _s.onFullScreenChange);
			}
			
			_s.callVastEvent("playerCollapse");
				
			if(_s.controller_do) _s.controller_do.setNormalStateToFullScreenButton();
			
			if(_s.displayType == FWDEVPlayer.RESPONSIVE
			   || _s.displayType == FWDEVPlayer.AFTER_PARENT
			   || _s.displayType == FWDEVPlayer.LIGHTBOX
			   || _s.displayType == FWDEVPlayer.STICKY
			 ){
			
				document.documentElement.style.overflow = "visible";
				_s.main_do.style().position = "relative";
				_s.main_do.style().zIndex = 0;

				if(_s.isMin){
					_s.main_do.style().position = 'fixed';
					_s.main_do.style().zIndex = 9999999999999;
				}else{
					_s.main_do.style().position = "relative";
					_s.main_do.style().zIndex = 0;
				}
			}else{
				_s.main_do.style().position = "absolute";
				_s.main_do.style().zIndex = 9999999999998;
			}
			
			
			_s.showCursor();
			if(_s.controller_do) _s.controller_do.showFullScreenButton();

			var alScr = true;
			if(window['FWDSISC'] && FWDSISC.isFS){
				alScr = false;
			}
			
			if(alScr){
				window.scrollTo(_s.lastX, _s.lastY);
				
				if(!FWDEVPUtils.isIE){
					setTimeout(function(){
						window.scrollTo(_s.lastX, _s.lastY);
					}, 150);
				}
			}

			_s.resizeHandler();
			if(_s.customContextMenu_do) _s.customContextMenu_do.updateFullScreenButton(0);
			if(_s.isMobile_bl) window.removeEventListener("touchmove", _s.disableFullScreenOnMobileHandler);
			_s.dispatchEvent(FWDEVPlayer.GO_NORMALSCREEN);
		};
		
		_s.onFullScreenChange = function(e){
			if(!(document.fullScreen || document.msFullscreenElement  || document.mozFullScreen || document.webkitIsFullScreen || document.msieFullScreen)){
				if(_s.controller_do) _s.controller_do.showNormalScreenButton();
				_s.isFullScreen_bl = false;
				_s.addMainDoToTheOriginalParent(true);
				
			}
		};

		_s.displayError = function(error){
			_s.main_do.addChild(_s.info_do);
			_s.info_do.showText(error);
			_s.preloader_do.hide(false);
			_s.preloader_do.stopPreloader();
			_s.dispatchEvent(FWDEVPlayer.ERROR, {error:error});
		}
		
		_s.downloadVideo = function(){
			if(!_s.isAPIReady_bl) return;
			var sourceName;
			
			var source = _s._d.videosSource_ar[_s._d.startAtVideoSource]["source"];
			if(source.indexOf("/") != -1){
				sourceName = source.substr(source.lastIndexOf("/") + 1);
			}else{
				sourceName = source;
			}
		
			_s._d.downloadVideo(source, sourceName);
			
			if(_s.gtag){	
				var params = {
					'video_url': source,
				}
				_s.gtag('event', 'download', params);
			}
		};


		_s.stopVideo = function(){
			if(!_s.isAPIReady_bl) return;
			if(_s.IMA) _s.IMA.stop();
			if(_s.controller_do){
				_s.controller_do.createdAdsOnce_bl = false;
				_s.controller_do.resetsAdsLines(true);
			}

			_s._d.imaURL = _s.videoSource2_str = _s.prevVideoSource_str = undefined;
			_s.adDone_bl = _s.isAdd_bl = _s._d.isVastXMLParsed_bl = _s.playSecondSource = false;
			_s._d.adsSource_ar = _s._d.popupAds_ar = [];
			_s.prevVidSrc = _s.prevVidSrc2 = _s.prevIsIMA = '';
			_s.curDurration = 0;
			_s.prevDuration = -1;
			_s.stop();
		}
		
		_s.setVideoSource =  function(source, source2, videoType, isLive, vast, password, rotationY360DegreeVideo, startWhenPlayButtonClick360DegreeVideo){
			if(!_s.isAPIReady_bl) return;
			_s.stopVideo();
			_s.isAdd_bl = false;
			_s.adDone_bl = false;
			_s.prevDuration = -1;
			_s._d.vastXML = undefined;
			_s._d.imaURL = undefined;

			if(vast && FWDEVPUtils.isIMA(vast)){
				_s._d.imaURL = vast;
			}else if(vast){
				_s._d.vastXML = vast;
			}
			
			if(isLive ==  undefined) isLive = false;
		
			_s._d.videosSource_ar[_s._d.startAtVideoSource]["isLive"] = isLive;
			_s._d.videosSource_ar[_s._d.startAtVideoSource]["source"] = source;
			_s._d.videosSource_ar[_s._d.startAtVideoSource]["videoType"] = videoType;
			_s._d.videosSource_ar[_s._d.startAtVideoSource]["isPrivate"] = password;
			_s._d.videosSource_ar[_s._d.startAtVideoSource]["rotationY360DegreeVideo"] = rotationY360DegreeVideo || 0;
			_s._d.videosSource_ar[_s._d.startAtVideoSource]["startWhenPlayButtonClick360DegreeVideo"] = startWhenPlayButtonClick360DegreeVideo || false;
			
			_s.updateAds(0);
		};
		
		_s.setVastSource = function(source){
			if(!_s.isAPIReady_bl) return;
			_s.stopVideo();
			_s._d.vastXML = source;
			_s.updateAds(0);
		}
		
		_s.getVideoSource = function(){
			if(!_s.isAPIReady_bl) return;
			return _s.finalVideoPath_str;
		};
		
		_s.updateVolume = function(){
			if(!_s.isAPIReady_bl) return;
			_s.setVolume();
		}
		
		_s.getPosterSource = function(){
			if(!_s.isAPIReady_bl) return;
			return _s.posterPath_str;
		};

		_s.getCurrentTime = function(format){
			if(!format) format = 'text';
			var tm;
			if(format == 'milliseconds'){
				if(!_s.curTimeInmilliseconds){
					tm = 0;
				}else{
					tm = _s.curTimeInmilliseconds;
				}
				if(_s.isCasting) tm = _s.cc.getCurrentTime();
			}else if(format == 'seconds'){
				if(!_s.curTimeInSecond){
					tm = 0;
				}else{
					tm = _s.curTimeInSecond;
				}
				if(_s.isCasting) tm = _s.cc.getCurrentTime();
			}else{
				if(!_s.curTime){
					tm = "00:00";
				}else{
					tm = _s.curTime;
				}
				if(_s.isCasting) tm = FWDEVPUtils.formatTime(_s.cc.getCurrentTime());
			}
			return tm;
		};
		
		_s.getTotalTime = function(format){
			if(!format) format = 'text';
			var tm;
			if(format == 'milliseconds'){
				if(!_s.totalTimeInMilliseconds){
					tm = 0;
				}else{
					tm = _s.totalTimeInMilliseconds;
				}
				if(_s.isCasting) tm = _s.cc.getCurrentTime();
			}else if(format == 'seconds'){
				tm = Math.round(_s.totalTimeInSeconds);
				if(_s.isCasting) tm = _s.cc.getDuration();
			}else{
				if(!_s.totalTime){
					tm = "00:00";
				}else{
					tm = _s.totalTime;
				}
				if(_s.isCasting) tm = FWDEVPUtils.formatTime(_s.cc.getDuration());
			}
			return tm;
		};
		
		_s.setPlaybackRate = function(rate){

			if(!_s.isAPIReady_bl || rate === undefined) return;
			if( _s.videoScreen_do && _s.videoType_str == FWDEVPlayer.VIDEO
			 || _s.videoType_str == FWDEVPlayer.HLS_JS
			 || _s.videoType_str == FWDEVPlayer.DASH){
				_s.videoScreen_do.setPlaybackRate(rate);
			}else if(_s.videoType_str == FWDEVPlayer.MP3 && _s.audioScreen_do){
				_s.audioScreen_do.setPlaybackRate(rate);
			}else if(_s.videoType_str == FWDEVPlayer.YOUTUBE){
				
				if(_s.ytb_do && _s.ytb_do.ytb) _s.ytb_do.setPlaybackRate(rate);
			}
		}
		
		_s.fillEntireVideoScreen = function(param){
			if(!_s.isAPIReady_bl) return;
			_s.fillEntireVideoScreen_bl = param;
			_s.resizeHandler();
		};
		
		_s.showLightbox = function(){
			if(_s.lightBox_do) _s.lightBox_do.show();
		}
		
		_s.updateHEXColors = function(normalColor, selectedColor){
			if(!_s.isAPIReady_bl) return;
			_s.controller_do.updateHEXColors(normalColor, selectedColor);
			if(_s.largePlayButton_do) _s.largePlayButton_do.updateHEXColors(normalColor, selectedColor);
			if(_s.shareWindow_do) _s.shareWindow_do.updateHEXColors(normalColor, selectedColor);
			if(_s.embedWindow_do) _s.embedWindow_do.updateHEXColors(normalColor, selectedColor);
			if(_s.adsSkip_do) _s.adsSkip_do.updateHEXColors(normalColor, selectedColor);
			if(_s.opener_do) _s.opener_do.updateHEXColors(normalColor, selectedColor);
		};
		

		//###########################################//
		/* Hide / show cursor */
		//###########################################//
		_s.hideCursor = function(){
			document.documentElement.style.cursor = "none";
			document.getElementsByTagName("body")[0].style.cursor = "none";
			if(!_s.isAdd_bl) _s.dClk_do.style().cursor = "none";
		};
		
		_s.showCursor = function(){
			document.documentElement.style.cursor = "auto";
			document.getElementsByTagName("body")[0].style.cursor = "auto";
			if(_s.isAdd_bl){
				_s.dClk_do.setButtonMode(true);
			}else{
				if(_s.is360){
					_s.dClk_do.style().cursor = 'url(' + _s._d.handPath_str + '), default';
				}else{
					_s.dClk_do.style().cursor = "auto";
				}
			}
			_s.dispatchEvent(FWDEVPlayer.SHOW_CURSOR);
		};
		

		//#############################################//
		/* Tracking vast events */
		//#############################################//
		_s.callVastEvent = function(eventName){
			
			if(!_s.TrackingEvents) return;
			var URI;
		
			for(var i=0; i<_s.TrackingEvents.length; i++){
				if(eventName == _s.TrackingEvents[i]["event"]){
					URI = _s.TrackingEvents[i]["URI"];
				}
			}
		
			if(!URI) return;
			_s.executeVastEvent(URI);
		}
		
		_s.executeVastEvent = function(URI){
			if(!URI) return;
			if(URI === true) return;
			
			var trackingXHR = new XMLHttpRequest();
			trackingXHR.onreadystatechange = function(e){};
			
			trackingXHR.onerror = function(e){};
			
			trackingXHR.open("get", URI, true);
			trackingXHR.send();
		}
		
		_s.getStartTimeStamp = function(str){
			
			var ts  = window.location.href;
			ts = ts.substr(ts.indexOf(str + "=") + 2);
			if(ts.indexOf("&") != -1){
				ts = ts.substr(0, ts.indexOf("&"));
			}

			if(ts.indexOf("s&") != -1){
				ts = ts.substr(0, ts.indexOf("s&") + 1);
			}

			if(ts.match(/:/)) return '00:00:00';
			
			var pattern = /\d+h/g;
			var hours = ts.match(pattern);
			try{ hours = ts.match(pattern)[0] }catch(e){}
			if(hours){
				hours = hours.substr(0, hours.length -1);
				if(hours.length == 1 && parseInt(hours) < 10){
					hours = "0" + hours;
				}
				if(parseInt(hours) > 59) hours = 59;
			}
			hours = hours ? hours : "00";
			
			var pattern = /\d+m/g;
			var minutes = ts.match(pattern);
			try{ minutes = ts.match(pattern)[0] }catch(e){}
			if(minutes){
				minutes = minutes.substr(0, minutes.length -1);
				if(minutes.length == 1 && parseInt(minutes) < 10){
					minutes = "0" + minutes;
				}
				if(parseInt(minutes) > 59) minutes = 59;
			}
			minutes = minutes ? minutes : "00";
			
			var pattern = /\d+s/g;
			var seconds = ts.match(pattern);
			try{ seconds = ts.match(pattern)[0] }catch(e){}
			if(seconds){
				seconds = seconds.substr(0, seconds.length -1);
				if(seconds.length == 1 && parseInt(seconds) < 10){
					seconds = "0" + seconds;
				}
				if(parseInt(seconds) > 59) seconds = 59;
			}
			seconds = seconds ? seconds : "00";
		
			return hours + ":" + minutes + ":" + seconds;
		}
	

		//###########################################//
		/* event dispatcher */
		//###########################################//
		_s.addListener = function (type, listener){
	    	if(type == undefined) throw Error("type is required.");
	    	if(typeof type === "object") throw Error("type must be of type String.");
	    	if(typeof listener != "function") throw Error("listener must be of type Function.");
	    	
	        var event = {};
	        event.type = type;
	        event.listener = listener;
	        event.target = this;
	        _s.listeners.events_ar.push(event);
	    };
	    
	    _s.dispatchEvent = function(type, props){
	    	if(_s.listeners == null) return;
	    	if(type == undefined) throw Error("type is required.");
	    	if(typeof type === "object") throw Error("type must be of type String.");
	    	
	        for (var i=0, len=_s.listeners.events_ar.length; i < len; i++){
	        	if(_s.listeners.events_ar[i].target === this && _s.listeners.events_ar[i].type === type){		
	    	        if(props){
	    	        	for(var prop in props){
	    	        		_s.listeners.events_ar[i][prop] = props[prop];
	    	        	}
	    	        }
	        		_s.listeners.events_ar[i].listener.call(this, _s.listeners.events_ar[i]);
	        	}
	        }
	    };
	    
	   _s.removeListener = function(type, listener){
	    	
	    	if(type == undefined) throw Error("type is required.");
	    	if(typeof type === "object") throw Error("type must be of type String.");
	    	if(typeof listener != "function") throw Error("listener must be of type Function." + type);
	    	
	        for (var i=0, len=_s.listeners.events_ar.length; i < len; i++){
	        	if(_s.listeners.events_ar[i].target === this 
	        			&& _s.listeners.events_ar[i].type === type
	        			&& _s.listeners.events_ar[i].listener ===  listener
	        	){
	        		_s.listeners.events_ar.splice(i,1);
	        		break;
	        	}
	        }  
	    };
	    

	   //#############################################//
		/* clean main events */
		//#############################################//
		_s.cleanMainEvents = function(){
			if(window.removeEventListener){
				window.removeEventListener("resize", _s.onResizeHandler);
			}else if(window.detachEvent){
				window.detachEvent("onresize", _s.onResizeHandler);
			}
		
			clearTimeout(_s.resizeHandlerId_to);
			clearTimeout(_s.resizeHandler2Id_to);
			clearTimeout(_s.hidePreloaderId_to);
			clearTimeout(_s.orientationChangeId_to);
		};
		
		
		//#####################################################//
		/* Add background if embedded */
		//#####################################################//
		var args = FWDEVPUtils.getUrlArgs(window.location.search);
		var embedTest = args.EVPInstanceName;
	
		var tt = FWDEVPlayer.instaces_ar.length;
		var video;
		
		if(embedTest){
			for(var i=0; i<tt; i++){
				video = FWDEVPlayer.instaces_ar[i];
				if(video.props.instanceName == embedTest){
					var ws = FWDEVPUtils.getViewportSize();
					
					var dumy_do = new FWDEVPDO("div");
					dumy_do.setBkColor(video.props.backgroundColor);
					dumy_do.setWidth(ws.w);
					dumy_do.setHeight(ws.h);
				
					document.documentElement.style.overflow = "hidden";
					document.getElementsByTagName("body")[0].style.overflow = "hidden";
					
					if(FWDEVPUtils.isIEAndLessThen9){
						document.getElementsByTagName("body")[0].appendChild(dumy_do.screen);
					}else{
						document.documentElement.appendChild(dumy_do.screen);
					}
					break;
				}
			}
		}
	
		_s.init();
	};
	
	
	/* set prototype */
	FWDEVPlayer.setPrototype =  function(){
		FWDEVPlayer.prototype = new FWDEVPEventDispatcher();
	};
	
	FWDEVPlayer.stopAllVideos = function(pVideo){
		var tt = FWDEVPlayer.instaces_ar.length;
		var video;
		for(var i=0; i<tt; i++){
			video = FWDEVPlayer.instaces_ar[i];
			if(video != pVideo){
				video.stop();
			}
		};
	};
	
	FWDEVPlayer.pauseAllVideos = function(pVideo){
		var tt = FWDEVPlayer.instaces_ar.length;
		var video;
		for(var i=0; i<tt; i++){
			video = FWDEVPlayer.instaces_ar[i];
			if(video != pVideo){
				video.pause();
			}
		};
	};
	
	
	FWDEVPlayer.hasHTML5Video = true;
	
	FWDEVPlayer.hasHTMLHLS = (function(){
		var videoTest_el = document.createElement("video");
		var flag = false;
		if(videoTest_el.canPlayType){
			flag = Boolean(videoTest_el.canPlayType('application/vnd.apple.mpegurl') === "probably" || videoTest_el.canPlayType('application/vnd.apple.mpegurl') === "maybe");
		}
		return flag;
	}());
	
	FWDEVPlayer.instaces_ar = [];
	
	FWDEVPlayer.curInstance = null;
	FWDEVPlayer.keyboardCurInstance = null;
	FWDEVPlayer.areInstancesCreated_bl = null;
	FWDEVPlayer.isYoutubeAPICreated_bl = false;
	FWDEVPlayer.isEmbedded_bl = false;
	
	
	FWDEVPlayer.START_TO_SCRUB = "startToScrub";
	FWDEVPlayer.STOP_TO_SCRUB = "stopToScrub";
	FWDEVPlayer.CENTER = "center";
	FWDEVPlayer.LEFT = "left";
	FWDEVPlayer.RIGHT = "right";
	FWDEVPlayer.PAUSE_ALL_VIDEOS = "pause";
	FWDEVPlayer.STOP_ALL_VIDEOS = "stop";
	FWDEVPlayer.DO_NOTHING = "none";
	FWDEVPlayer.VIMEO = "vimeo";
	FWDEVPlayer.YOUTUBE = "youtube";
	FWDEVPlayer.VIDEO = "video";
	FWDEVPlayer.MP3 = "mp3";
	FWDEVPlayer.STICKY = "sticky";
	FWDEVPlayer.POSITION_TOP = "top";
	FWDEVPlayer.POSITION_BOTTOM = "bottom";
	FWDEVPlayer.SHOW_PLAY_BUTTON = 'showPlayButton';
	FWDEVPlayer.HIDE_PLAY_BUTTON = 'hidePlayButton';
	FWDEVPlayer.SAFE_TO_SCRUB = "safeToScrub";
	FWDEVPlayer.IFRAME = "iframe";
	FWDEVPlayer.SCRUB = "scrub";
	FWDEVPlayer.BACKGROUND_VIDEO = "backgroundvideo";
	FWDEVPlayer.READY = "ready";
	FWDEVPlayer.STOP = "stop";
	FWDEVPlayer.PLAY_START = "playStart";
	FWDEVPlayer.PLAY = "play";
	FWDEVPlayer.PAUSE = "pause";
	FWDEVPlayer.UPDATE = "update";
	FWDEVPlayer.UPDATE_TIME = "updateTime";
	FWDEVPlayer.UPDATE_VIDEO_SOURCE = "updateVideoSource";
	FWDEVPlayer.UPDATE_POSTER_SOURCE = "udpatePosterSource";
	FWDEVPlayer.PLAYBACK_RATE_CHANGE = "playbackRateChange";
	FWDEVPlayer.ERROR = "error";
	FWDEVPlayer.PLAY_COMPLETE = "playComplete";
	FWDEVPlayer.VOLUME_SET = "volumeSet";
	FWDEVPlayer.GO_FULLSCREEN = "goFullScreen";
	FWDEVPlayer.GO_NORMALSCREEN = "goNormalScreen";
	FWDEVPlayer.IMAGE = "image";
	FWDEVPlayer.VAST_LOADED_DONE = 'vastLoadingDone';
	FWDEVPlayer.HIDER_HIDE = 'hide';
	FWDEVPlayer.HIDER_SHOW = 'show';
	FWDEVPlayer.SHOW_CURSOR = 'showCursor';
	FWDEVPlayer.VR_START = 'vrStart';
	FWDEVPlayer.VR_STOP = 'vrStop';
	FWDEVPlayer.MANIFEST_PARSED_PLAY = 'manifesetParsedPlay';
	FWDEVPlayer.HIDE_LIGHTBOX_COMPLETE = "lightboxHideComplete";
	FWDEVPlayer.DASH = 'dash';
	FWDEVPlayer.HLS_JS = "HLS_JS";
	FWDEVPlayer.LIGHTBOX = "lightbox";
	FWDEVPlayer.RESPONSIVE = "responsive";
	FWDEVPlayer.FULL_SCREEN = "fullscreen";
	FWDEVPlayer.AFTER_PARENT = "afterparent";
	FWDEVPlayer.FRAMEWORK_LOAD = 'frload;';
	FWDEVPlayer.FRAMEWORK_DONE = 'frdone';
	
	
	window.FWDEVPlayer = FWDEVPlayer;
	
}(window));