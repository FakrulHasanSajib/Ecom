(function (window){

	var FWDEVPSimpleButton = function(nImg, 
								  sPath, 
								  dPath, 
								  alwaysShowSelectedPath, 
								  useHEX,
								  nBC,
								  sBC,
								  iconCSSString, 
								  showHDIcon, 
								  normalCalssName,
								  selectedCalssName,
								  showOver
								 ){
		'use strict';
		
		var _s = this;
		
		_s.iconCSSString = iconCSSString;
		_s.showHDIcon = showHDIcon;
	
		_s.nImg = nImg;
		_s.sPath_str = sPath;
		_s.dPath_str = dPath;
		
		_s.testButton = Boolean(String(_s.iconCSSString).indexOf("download") != -1);
	
		_s.n_do;
		_s.s_sdo;
		_s.d_sdo;

		_s.showOver = showOver;
		if(!useHEX){
			_s.showOver = false;
		}

		_s.toolTipLabel_str;
		
		if(_s.nImg){
			_s.totalWidth = _s.nImg.width;
			_s.totalHeight = _s.nImg.height;
			_s.buttonWidth = _s.totalWidth;
			_s.buttonHeight = _s.totalHeight;
		}
	
		_s.normalCalssName = normalCalssName;
		_s.selectedCalssName = selectedCalssName;
		
		_s.useHEX = useHEX;
		_s.nBC = nBC;
		_s.sBC = sBC;
		
		_s.isShowed_bl = true;
		_s.isMbl = FWDEVPUtils.isMobile;
		_s.hasPointerEvent_bl = FWDEVPUtils.hasPointerEvent;
		_s.allowToCreateSecondButton_bl = !_s.isMbl || _s.hasPointerEvent_bl || alwaysShowSelectedPath;
		_s.useFontAwesome_bl = Boolean(_s.iconCSSString);
		

		//##########################################//
		/* initialize _s */
		//##########################################//
		_s.init = function(){
			if(_s.iconCSSString) _s.setOverflow('visible');
			_s.setupMainContainers();
			_s.setNormalState();
		};

		
		//##########################################//
		/* setup main containers */
		//##########################################//
		_s.setupMainContainers = function(){
			if(_s.useFontAwesome_bl){
				_s.main_do = new FWDEVPDO("div", 0,0, true);
				_s.main_do.setOverflow('visible');
				_s.main_do.hasT2D = true;
				_s.setOverflow('visible');
				_s.n_do = new FWDEVPDO("div");	
				_s.n_do.hasT3D = false;
				_s.n_do.hasT2D = false;
				_s.n_do.setInnerHTML(_s.iconCSSString);
				_s.addChild(_s.main_do);
				_s.main_do.addChild(_s.n_do);

				if(_s.showHDIcon){
					var hdImage = new Image();
					hdImage.src = showHDIcon;
					_s.hd_do = new FWDEVPDO("img");
					_s.hd_do.setScreen(hdImage);
					_s.hd_do.setWidth(7);
					_s.hd_do.setHeight(5);

					_s.setOverflow("visible");
					_s.addChild(_s.hd_do);
				};
			
				_s.setFinalSize();
			}else{
				if(_s.useHEX && !_s.showOver){
					_s.n_do = new FWDEVPDO("div");
					_s.n_do.setWidth(_s.totalWidth);
					_s.n_do.setHeight(_s.totalHeight);

					_s.n_do_canvas = FWDEVPUtils.getCanvasWithModifiedColor(_s.nImg, _s.nBC).canvas;
					_s.n_do.screen.appendChild(_s.n_do_canvas);
					_s.addChild(_s.n_do);
				}else{
					_s.n_do = new FWDEVPDO("img");	
					_s.n_do.setScreen(_s.nImg);
					_s.addChild(_s.n_do);
				}
				_s.n_do.setAlpha(1);
				
				if(_s.allowToCreateSecondButton_bl){
					
					_s.img1 = new Image();
					_s.img1.src = _s.sPath_str;
					var img2 = new Image();
					_s.sImg = img2;
					
					if(_s.useHEX){
						_s.s_sdo = new FWDEVPDO("div");
						_s.s_sdo.setWidth(_s.totalWidth);
						_s.s_sdo.setHeight(_s.totalHeight);
						var clr = _s.sBC;
						if(_s.showOver){
							clr = _s.nBC
						}

						_s.img1.onload = function(){
							_s.s_sdo_canvas = FWDEVPUtils.getCanvasWithModifiedColor(_s.img1, clr).canvas;
							_s.s_sdo.screen.appendChild(_s.s_sdo_canvas);
						}
						if(!_s.showOver){
							_s.s_sdo.setAlpha(0);
						}
						_s.addChild(_s.s_sdo);
					}else{
						_s.s_sdo = new FWDEVPDO("img");
						_s.s_sdo.setScreen(_s.img1);
						_s.s_sdo.setWidth(_s.totalWidth);
						_s.s_sdo.setHeight(_s.totalHeight);
						if(!_s.useHEX){
							_s.s_sdo.setAlpha(0);
						}
						_s.addChild(_s.s_sdo);
					}
					
					if(_s.dPath_str){
						img2.src = _s.dPath_str;
						_s.d_sdo = new FWDEVPDO("img");
						_s.d_sdo.setScreen(img2);
						_s.d_sdo.setWidth(_s.totalWidth);
						_s.d_sdo.setHeight(_s.totalHeight);
						_s.d_sdo.setX(-100);
						_s.addChild(_s.d_sdo);
					};
					_s.setWidth(_s.totalWidth);
					_s.setHeight(_s.totalHeight);
				}
			}

			_s.setButtonMode(true);
			_s.screen.style.yellowOverlayPointerEvents = "none";
			
			if(_s.hasPointerEvent_bl){
				_s.screen.addEventListener("pointerup", _s.onMouseUp);
				_s.screen.addEventListener("pointerover", _s.onMouseOver);
				_s.screen.addEventListener("pointerout", _s.onMouseOut);
			}else if(_s.screen.addEventListener){	
				if(!_s.isMbl){
					_s.screen.addEventListener("mouseover", _s.onMouseOver);
					_s.screen.addEventListener("mouseout", _s.onMouseOut);
					_s.screen.addEventListener("mouseup", _s.onMouseUp);
				}
				_s.screen.addEventListener("touchend", _s.onMouseUp);
			}
		};
		
		_s.onMouseOver = function(e){
			_s.dispatchEvent(FWDEVPSimpleButton.SHOW_TOOLTIP, {e:e});
			if(_s.isDisabledForGood_bl) return;
			if(!e.pointerType || e.pointerType == e.MSPOINTER_TYPE_MOUSE || e.pointerType == "mouse"){
				if(_s.isDisabled_bl || _s.isSelectedFinal_bl) return;
				_s.dispatchEvent(FWDEVPSimpleButton.MOUSE_OVER, {e:e});
				_s.setSelectedState(true);
			}
		};
			
		_s.onMouseOut = function(e){
			if(_s.isDisabledForGood_bl) return;
			if(!e.pointerType || e.pointerType == e.MSPOINTER_TYPE_MOUSE || e.pointerType == "mouse"){
				if(_s.isDisabled_bl || _s.isSelectedFinal_bl) return;
				_s.dispatchEvent(FWDEVPSimpleButton.MOUSE_OUT, {e:e});
				_s.setNormalState(true);
			}
		};
		
		_s.onMouseUp = function(e){
			
			if(_s.isDisabledForGood_bl) return;
			if(e.preventDefault) e.preventDefault();
			if(_s.isDisabled_bl || e.button == 2) return;
		
			_s.dispatchEvent(FWDEVPSimpleButton.MOUSE_UP, {e:e});
		};
		
		_s.checkCount = 0;
		_s.setFinalSize = function(reset){
			if(reset){
				_s.checkCount = 0;
			}
			
			clearInterval(_s.checkId_int);
			if(_s.checkCount > 6) return;
			_s.lastWidth = _s.n_do.screen.firstChild.offsetWidth;
			_s.lastHeight = _s.n_do.screen.firstChild.offsetHeight;
			_s.checkCount +=1;
		
			_s.checkId_int = setInterval(function(){
				_s.setFinalSize();
			},100);
			
			if(_s.prevWidth == _s.lastWidth || _s.lastWidth == 0) return;
			_s.setWidth(_s.n_do.screen.firstChild.offsetWidth);
			_s.setHeight(_s.n_do.screen.firstChild.offsetHeight);

			if(_s.main_do){
				_s.main_do.setWidth(_s.w);
				_s.main_do.setHeight(_s.h);
			}
			_s.n_do.setWidth(_s.w);
			_s.n_do.setHeight(_s.h);
			_s.n_do.setWidth(_s.w);
			_s.n_do.setHeight(_s.h);
			_s.buttonWidth = _s.w;
			_s.buttonHeight = _s.h;
			_s.totalWidth = _s.w;
			_s.totalHeight = _s.h;
		
			if(_s.hd_do){
				_s.hd_do.setX(_s.w - _s.hd_do.w + 2);
				_s.hd_do.setY( -2);	
			}
			
			_s.prevWidth = _s.lastWidth;
			_s.prevHeight = _s.lastHeight;
			_s.lastHeight = _s
		}
		

		//##############################//
		// set select / deselect final.
		//##############################//
		_s.setSelected = function(){
			_s.isSelectedFinal_bl = true;
			if(!_s.s_sdo) return;
			FWDAnimation.killTweensOf(_s.s_sdo);
			FWDAnimation.to(_s.s_sdo, .8, {alpha:1, ease:Expo.easeOut});
		};
		
		_s.setUnselected = function(){
			_s.isSelectedFinal_bl = false;
			if(!_s.s_sdo) return;
			FWDAnimation.to(_s.s_sdo, .8, {alpha:0, delay:.1, ease:Expo.easeOut});
		};
		

		//####################################//
		/* Set normal / selected state */
		//####################################//
		_s.setNormalState = function(animate){
			if(_s.doNotallowToSetNormal) return;
			if(_s.useFontAwesome_bl){
				FWDAnimation.killTweensOf(_s.n_do.screen);
				if(animate){
					FWDAnimation.to(_s.n_do.screen, .6, {className:_s.normalCalssName, ease:Quart.easeOut});	
				}else{
					_s.n_do.screen.className = _s.normalCalssName;
				}
			}else{

				if(_s.showOver){
					FWDAnimation.killTweensOf(_s.s_sdo);
					FWDAnimation.to(_s.s_sdo, .6, {alpha:1, ease:Quart.easeOut});	
				}else{
					FWDAnimation.killTweensOf(_s.s_sdo);
					FWDAnimation.to(_s.s_sdo, .6, {alpha:0, ease:Quart.easeOut});	
				}
			}
		};
		
		_s.setSelectedState = function(animate){
			if(_s.useFontAwesome_bl){
				FWDAnimation.killTweensOf(_s.n_do.screen);
				if(animate){
					FWDAnimation.to(_s.n_do.screen, .6, {className:_s.selectedCalssName, ease:Quart.easeOut});	
				}else{
					_s.n_do.screen.className = _s.selectedCalssName;
				}
			}else{
				if(_s.showOver){
					FWDAnimation.killTweensOf(_s.s_sdo);
					FWDAnimation.to(_s.s_sdo, .6, {alpha:0, ease:Quart.easeOut});	
				}else{
					FWDAnimation.killTweensOf(_s.s_sdo);
					FWDAnimation.to(_s.s_sdo, .6, {alpha:1, delay:.1, ease:Quart.easeOut});
				}
				
			}
		};
		

		//####################################//
		/* Disable / enable */
		//####################################//
		_s.setDisabledState = function(){
			if(_s.isSetToDisabledState_bl) return;
			_s.isSetToDisabledState_bl = true;
			if(_s.d_sdo) _s.d_sdo.setX(0);
			if(_s.hd_do) _s.hd_do.setX(_s.w - _s.hd_do.w);
		};
		
		_s.setEnabledState = function(){
			if(!_s.isSetToDisabledState_bl) return;
			_s.isSetToDisabledState_bl = false;
			if(_s.d_sdo) _s.d_sdo.setX(-100);
			if(_s.hd_do) _s.hd_do.setX(-100000);
		};
		
		_s.disable = function(){
			if(_s.isDisabledForGood_bl || _s.isDisabled_bl) return;

			_s.isDisabled_bl = true;
			_s.setButtonMode(false);
			FWDAnimation.killTweensOf(_s);
			FWDAnimation.to(_s, .6, {alpha:.4});
			_s.setNormalState(true);
		};
		
		_s.enable = function(){
			if(_s.isDisabledForGood_bl || !_s.isDisabled_bl) return;
			_s.isDisabled_bl = false;
			_s.setButtonMode(true);
			FWDAnimation.killTweensOf(_s);
			FWDAnimation.to(_s, .6, {alpha:1});
		};
		
		_s.disableForGood = function(){
			_s.isDisabledForGood_bl = true;
			_s.setButtonMode(false);
		};
		
		_s.showDisabledState = function(){
			if(_s.d_sdo) if(_s.d_sdo.x != 0) _s.d_sdo.setX(0);
			if(_s.hd_do) _s.hd_do.setX(_s.w - _s.hd_do.w + 2);
		};
		
		_s.hideDisabledState = function(){
			if(_s.d_sdo) if(_s.d_sdo.x != -100) _s.d_sdo.setX(-100);
			if(_s.hd_do) _s.hd_do.setX(-10000);
		};
	
		
		//#####################################//
		/* show / hide */
		//#####################################//
		_s.show = function(dl){
			if(_s.isShowed_bl) return;
			_s.isShowed_bl = true;
			
			if(dl === 0){
				if(_s.main_do){
					_s.setVisible(true);
					_s.main_do.setScale2(1);
				}else{
					_s.setVisible(true);
					_s.setScale2(1);
				}
				
			}else{
				if(_s.main_do){
					FWDAnimation.killTweensOf(_s.main_do);
					FWDAnimation.to(_s.main_do, .8, {scale:1, delay:.4, onStart:function(){_s.setVisible(true);}, ease:Elastic.easeOut});
				}else{
					FWDAnimation.killTweensOf(_s);
					FWDAnimation.to(_s, .8, {scale:1, delay:.4, onStart:function(){_s.setVisible(true);}, ease:Elastic.easeOut});
				}
			}
			_s.screen.style.pointerEvents = "auto";
		};	

		_s.show2 = function(){
			if(_s.isShowed_bl) return;
			_s.isShowed_bl = true;
			
			if(dl === 0){
				if(_s.main_do){
					_s.setVisible(true);
					_s.main_do.setScale2(1);
				}else{
					_s.setVisible(true);
					_s.setScale2(1);
				}
				
			}else{
				if(_s.main_do){
					FWDAnimation.killTweensOf(_s.main_do);
					FWDAnimation.to(_s.main_do, .8, {scale:1,  onStart:function(){_s.setVisible(true);}, ease:Elastic.easeOut});
				}else{
					FWDAnimation.killTweensOf(_s);
					FWDAnimation.to(_s, .8, {scale:1, onStart:function(){_s.setVisible(true);}, ease:Elastic.easeOut});
				}
			}
			_s.screen.style.pointerEvents = "auto";
		};	
			
		_s.hide = function(anim){
			if(!_s.isShowed_bl) return;
			_s.isShowed_bl = false;

			if(_s.main_do){	
				if(anim){
					FWDAnimation.killTweensOf(_s.main_do);
					FWDAnimation.to(_s.main_do, .8, {scale:0, delay:.4, ease:Elastic.easeOut});
				}else{
					_s.setVisible(false);
					FWDAnimation.killTweensOf(_s.main_do);
					_s.main_do.setScale2(0);
				}
			}else{
				_s.setVisible(false);
				FWDAnimation.killTweensOf(_s);
				_s.setScale2(0);
			}
			_s.screen.style.pointerEvents = "none";
		};
		

		//##########################################//
		/* Update HEX color of a canvaas */
		//##########################################//
		_s.updateHEXColors = function(nBC, sBC){
			if(_s.n_do_canvas){
				FWDEVPUtils.changeCanvasHEXColor(_s.nImg, _s.n_do_canvas, nBC);
			}
			
			if(_s.s_sdo_canvas){
				FWDEVPUtils.changeCanvasHEXColor(_s.img1, _s.s_sdo_canvas, sBC);
			}
		}
		
		_s.init();
	};
	
	
	/* set prototype */
	FWDEVPSimpleButton.setPrototype = function(){
		FWDEVPSimpleButton.prototype = null;
		FWDEVPSimpleButton.prototype = new FWDEVPDO("div");
	};

	FWDEVPSimpleButton.setTransformPrototype = function(){
		FWDEVPSimpleButton.prototype = null;
		FWDEVPSimpleButton.prototype = new FWDEVPDO("div", 0,0, true);
	};
	
	FWDEVPSimpleButton.CLICK = "onClick";
	FWDEVPSimpleButton.MOUSE_OVER = "onMouseOver";
	FWDEVPSimpleButton.SHOW_TOOLTIP = "showTooltip";
	FWDEVPSimpleButton.MOUSE_OUT = "onMouseOut";
	FWDEVPSimpleButton.MOUSE_UP = "onMouseDown";
	
	FWDEVPSimpleButton.prototype = null;
	window.FWDEVPSimpleButton = FWDEVPSimpleButton;
}(window));