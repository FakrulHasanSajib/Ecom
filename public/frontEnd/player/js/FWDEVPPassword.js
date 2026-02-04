/**
 * Easy Video Player PACKAGED v9.1
 * Password window.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (window){

	var FWDEVPPassword = function(_d, prt, lg){

		'use strict';
		
		var _s = this;
		var prototype = FWDEVPPassword.prototype;
	
		_s.passColoseN_img = _d.passColoseN_img;
		
	
		_s.embedWindowBackground_str = _d.embedWindowBackground_str;
		
		_s.secondaryLabelsColor_str = _d.secondaryLabelsColor_str;
		_s.inputColor_str = _d.inputColor_str;
		_s.mainLabelsColor_str = _d.mainLabelsColor_str;
		_s.passButtonNPath_str = _d.passButtonNPath_str;
		_s.passButtonSPath_str = _d.passButtonSPath_str;
		_s.inputBackgroundColor_str = _d.inputBackgroundColor_str;
		_s.borderColor_str = _d.borderColor_str;
		
		_s.maxTextWidth = 0;
		_s.totalWidth = 0;
		_s.sW = 0;
		_s.sH = 0;
		_s.buttonWidth = 28;
		_s.buttonHeight = 19;
		_s.embedWindowCloseButtonMargins = _d.embedWindowCloseButtonMargins;		
		_s.useVectorIcons_bl = _d.useVectorIcons_bl;
		_s.isShowed_bl = false;
		_s.isMobile_bl = FWDEVPUtils.isMobile;
	
		//#################################//
		/* init */
		//#################################//
		_s.init = function(){
			if(_s.clsBtn) return;
			_s.setBackfaceVisibility();
			_s.mainHld = new FWDEVPDO("div");
			_s.mainHld.hasT3D = false;
			_s.mainHld.hasT2D = false;
			_s.mainHld.setBackfaceVisibility();
			
			_s.bk_do = new FWDEVPDO("div");
			_s.bk_do.style().width = "100%";
			_s.bk_do.style().height = "100%";
			_s.bk_do.setAlpha(.9);
			_s.bk_do.style().background = "url('" + _s.embedWindowBackground_str + "')";
		
			_s.passMainHolder_do =  new FWDEVPDO("div");
			
			_s.passMainHolderBk_do = new FWDEVPDO("div");
			_s.passMainHolderBk_do.style().background = "url('" + _s.embedWindowBackground_str + "')";
			_s.passMainHolderBk_do.style().borderStyle = "solid";
			_s.passMainHolderBk_do.style().borderWidth = "1px";
			_s.passMainHolderBk_do.style().borderColor =  _s.borderColor_str;
			
			_s.passMainLabel_do = new FWDEVPDO("div");
			_s.passMainLabel_do.setBackfaceVisibility();
			_s.passMainLabel_do.screen.className = 'fwdevp-password-title'
			_s.passMainLabel_do.style().fontFamily = "Arial";
			_s.passMainLabel_do.style().fontSize= "12px";
			_s.passMainLabel_do.style().color = _s.mainLabelsColor_str;
			_s.passMainLabel_do.style().whiteSpace= "nowrap";
			_s.passMainLabel_do.style().padding = "0px";
			_s.passMainLabel_do.setInnerHTML("PRIVATE CONTENT");	
			
			_s.passLabel_do = new FWDEVPDO("div");
			_s.passLabel_do.setBackfaceVisibility();
			_s.passLabel_do.screen.className = 'fwdevp-password-label';
			_s.passLabel_do.style().fontFamily = "Arial";
			_s.passLabel_do.style().fontSize= "12px";
			_s.passLabel_do.style().color = _s.secondaryLabelsColor_str;
			_s.passLabel_do.style().whiteSpace= "nowrap";
	
			_s.passLabel_do.style().padding = "0px";
			_s.passLabel_do.setInnerHTML("Please enter password:");
			
			_s.passInput_do = new FWDEVPDO("input");
			_s.passInput_do.screen.className = 'fwdevp-password-input';
			_s.passInput_do.setBackfaceVisibility();
			_s.passInput_do.style().fontFamily = "Arial";
			_s.passInput_do.style().fontSize= "12px";
			_s.passInput_do.style().backgroundColor = _s.inputBackgroundColor_str;
			_s.passInput_do.style().color = _s.inputColor_str;
			_s.passInput_do.style().outline = 0;
			_s.passInput_do.style().whiteSpace= "nowrap";
			_s.passInput_do.style().padding = "6px";
			_s.passInput_do.style().paddingTop = "4px";
			_s.passInput_do.style().paddingBottom = "4px";
			_s.passInput_do.screen.setAttribute("type", "password");
			
			if(!lg){
				FWDEVPSimpleSizeButton.setPrototype();
				_s.passButton_do = new FWDEVPSimpleSizeButton(
						_s.passButtonNPath_str, 
						_s.passButtonSPath_str,
						_s.buttonWidth,
						_s.buttonHeight,
						_d.useHEX,
						_d.nBC,
						_d.sBC
						);
				_s.passButton_do.addListener(FWDEVPSimpleSizeButton.CLICK, _s.passClickHandler);
			
				//setup close button
				FWDEVPSimpleButton.setPrototype();
				if(_s.useVectorIcons_bl){
					FWDEVPUtils.smpBtnNPos();
					var ic = prt.fontIcon +' ' + prt.fontIcon + '-close';
					_s.clsBtn = new FWDEVPSimpleButton(
							0, 0, 0, true, 0, 0, 0,
							"<div class='table-fwdevp-button'><span class='table-cell-fwdevp-button " + ic + "'></span></div>",
							undefined,
							"EVPCloseButtonNormalState",
							"EVPCloseButtonSelectedState"
					);
					_s.clsBtn.addListener(FWDEVPSimpleButton.MOUSE_UP, _s.closeButtonOnMouseUpHandler);
					_s.mainHld.addChild(_s.clsBtn); 
				
				}else{
					_s.clsBtn = new FWDEVPSimpleButton(_s.passColoseN_img, _d.embedWindowClosePathS_str, undefined,
							true,
							_d.useHEX,
							_d.nBC,
							_d.sBC);
				}
				_s.clsBtn.screen.className = 'fwdevp-close-button';
				_s.clsBtn.addListener(FWDEVPSimpleButton.MOUSE_UP, _s.closeButtonOnMouseUpHandler);
				
				_s.addChild(_s.mainHld);
				_s.mainHld.addChild(_s.bk_do);
				
				_s.passMainHolder_do.addChild(_s.passMainHolderBk_do);
				_s.passMainHolder_do.addChild(_s.passMainLabel_do);
				_s.passMainHolder_do.addChild(_s.passLabel_do);
				_s.passMainHolder_do.addChild(_s.passInput_do);
				_s.passMainHolder_do.addChild(_s.passButton_do);
				_s.mainHld.addChild(_s.passMainHolder_do);
				_s.mainHld.addChild(_s.clsBtn); 
			}else{
				_s.addChild(_s.mainHld);
				_s.mainHld.addChild(_s.bk_do);
				_s.mainHld.addChild(_s.passLabel_do);
				_s.passLabel_do.style().whiteSpace = "normal";
				_s.passLabel_do.style().width = "calc(100% - 40px)";
				_s.passLabel_do.style().textAlign = 'center';
				_s.passLabel_do.setInnerHTML(_d.loggedInMessage_str);

				var clsn = 'fwdevp-loggedin-message-white';
				if(_d.isDark){
					clsn = 'fwdevp-loggedin-message-dark';
				}
				_s.passLabel_do.screen.className = clsn;

				FWDEVPSimpleButton.setPrototype();
				if(_s.useVectorIcons_bl){
					FWDEVPUtils.smpBtnNPos();
					var ic = 'fwdicon-close';
					_s.clsBtn = new FWDEVPSimpleButton(
							0, 0, 0, true, 0, 0, 0,
							"<div class='table-fwdevp-button'><span class='table-cell-fwdevp-button " + ic + "'></span></div>",
							undefined,
							"EVPCloseButtonNormalState",
							"EVPCloseButtonSelectedState"
					);
					_s.clsBtn.screen.className = 'fwdevp-close-button';
					_s.clsBtn.addListener(FWDEVPSimpleButton.MOUSE_UP, _s.closeButtonOnMouseUpHandler);
					_s.mainHld.addChild(_s.clsBtn); 
				}else{

					var clsNImg = new Image();
					clsNImg.src = _s.passColoseN_img.src;
					clsNImg.onload = function(){
						//setup close button.
						FWDEVPSimpleButton.setPrototype();
						_s.clsBtn = new FWDEVPSimpleButton(
								clsNImg, 
								_d.embedWindowClosePathS_str, 
								undefined,
								true,
								_d.useHEX,
								_d.nBC,
								_d.sBC, 
								false, false, false, true);
						_s.clsBtn.screen.className = 'fwdevp-close-button';
						_s.clsBtn.addListener(FWDEVPSimpleButton.MOUSE_UP, _s.closeButtonOnMouseUpHandler);
						_s.mainHld.addChild(_s.clsBtn); 
						clsNImg.onload = null;
						_s.posClsBtn();
					}
				}
			}		
			_s.posClsBtn();
		};
	
		_s.closeButtonOnMouseUpHandler = function(){
			if(!_s.isShowed_bl) return;
			_s.hide();
		};
		
	
		function selectText(){
			if(window.top != window && FWDEVPUtils.isIE) return;
			var range, selection;
			if (document.body.createTextRange) {
				range = document.body.createTextRange();
			    range.moveToElementText(this);
			    range.select();
			}else if(window.getSelection && document.createRange) {
			    selection = window.getSelection();
			    range = document.createRange();
			    range.selectNodeContents(this);
			    selection.removeAllRanges();
			    selection.addRange(range);
			}
		};
		
		_s.positionAndResize = function(){
			_s.sW = prt.sW;
			_s.sH = prt.sH;
			
			_s.maxTextWidth = Math.min(_s.sW - 150, 300);
			_s.totalWidth = _s.maxTextWidth + _s.buttonWidth;
			
			_s.positionFinal();
			
			_s.setWidth(_s.sW);
			_s.setHeight(_s.sH);
			_s.mainHld.setWidth(_s.sW);
			_s.mainHld.setHeight(_s.sH);
		};

		_s.posClsBtn = function(){
			if(_s.clsBtn){
				_s.clsBtn.style().left = 'auto';
				_s.clsBtn.style().right = _s.embedWindowCloseButtonMargins + 'px';
				_s.clsBtn.style().top = _s.embedWindowCloseButtonMargins + 'px';
			}
		}

		
		_s.positionFinal = function(){
			
			var totalHeight;
			var textLableHeight = _s.passLabel_do.getHeight();
			var passMainLabelHeight;
			
			passMainLabelHeight = _s.passMainLabel_do.getHeight();

			if(!lg){
				_s.passMainLabel_do.setX(14);
				_s.passLabel_do.setX(14);
				_s.passLabel_do.setY(passMainLabelHeight + 14);
				
				_s.passInput_do.setX(10);
				_s.passInput_do.setWidth(parseInt(_s.totalWidth - 40 - _s.buttonWidth));
				_s.passInput_do.setY(_s.passLabel_do.y + textLableHeight + 5);
				_s.passButton_do.setX(10 + _s.passInput_do.w + 20);
				_s.passButton_do.setY(_s.passLabel_do.y + textLableHeight + 7);
				
				_s.passMainHolderBk_do.setY(_s.passLabel_do.y - 9);
				_s.passMainHolderBk_do.setWidth(_s.totalWidth - 2);
				_s.passMainHolderBk_do.setHeight(_s.passButton_do.y + _s.passButton_do.h - 9);
				_s.passMainHolder_do.setWidth(_s.totalWidth);
				_s.passMainHolder_do.setHeight(_s.passButton_do.y + _s.passButton_do.h + 14);

				_s.passMainHolder_do.setX(Math.round((_s.sW - _s.totalWidth)/2));
				totalHeight = _s.passMainHolderBk_do.getHeight();
				_s.passMainHolder_do.setY(Math.round((_s.sH - totalHeight)/2) - 10);
			}else{
				_s.passLabel_do.setX(Math.round((_s.sW - _s.passLabel_do.getWidth())/2));
				_s.passLabel_do.setY(Math.round((_s.sH - _s.passLabel_do.getHeight())/2));
			}
		};
		
		//##############################################//
		/* Send email */
		//##############################################//
		_s.passClickHandler = function(){
			if(_d.privateVideoPassword_str != FWDEVPUtils.MD5(_s.passInput_do.screen.value)){
				if(!FWDAnimation.isTweening(_s.passInput_do.screen)) FWDAnimation.to(_s.passInput_do.screen, .1, {css:{backgroundColor:'#FF0000'}, yoyo:true, repeat:3});
				return;
			}
			_s.dispatchEvent(FWDEVPPassword.CORRECT);
		};
		
		//##########################################//
		/* Update HEX color of a canvaas */
		//##########################################//
		_s.updateHEXColors = function(nBC, sBC){
			_s.passButton_do.updateHEXColors(nBC, sBC);
			_s.clsBtn.updateHEXColors(nBC, sBC);
		}
		
		/* show hide info */
		//#########################################//
		_s.showInfo = function(text, hasError){
				
			_s.infoText_do.setInnerHTML(text);
			_s.passMainHolder_do.addChild(_s.infoText_do);
			_s.infoText_do.setWidth(_s.buttonWidth);
			_s.infoText_do.setHeight(_s.buttonHeight - 4);
			_s.infoText_do.setX(_s.passButton_do.x);
			_s.infoText_do.setY(_s.passButton_do.y - 23);

			_s.infoText_do.setAlpha(0);
			if(hasError){
				_s.infoText_do.style().color = "#FF0000";
			}else{
				_s.infoText_do.style().color = _s.mainLabelsColor_str;
			}
			FWDAnimation.killTweensOf(_s.infoText_do);
			FWDAnimation.to(_s.infoText_do, .16, {alpha:1, yoyo:true, repeat:7});
		};
		
		//###########################################//
		/* show / hide */
		//###########################################//
		_s.show = function(id){
			if(_s.isShowed_bl) return;
			_s.isShowed_bl = true;
			prt.main_do.addChild(_s);
			_s.init();
			
			if(_s.passButton_do){
				_s.passButton_do.setSelectedState();
				_s.passInput_do.setInnerHTML("");
			}
			if(!FWDEVPUtils.isMobile || (FWDEVPUtils.isMobile && FWDEVPUtils.hasPointerEvent)) prt.main_do.setSelectable(true);
			_s.positionAndResize();
			
			clearTimeout(_s.hideCompleteId_to);
			clearTimeout(_s.showCompleteId_to);
			_s.mainHld.setY(- _s.sH);
			if(_s.passButton_do){
				_s.passButton_do.setNormalState();
			}
			_s.showCompleteId_to = setTimeout(_s.showCompleteHandler, 900);
			setTimeout(function(){
				_s.positionAndResize();
				FWDAnimation.to(_s.mainHld, .8, {y:0, delay:.1, ease:Expo.easeInOut});
			}, 100);
		};
		
		_s.showCompleteHandler = function(){};
		
		_s.hide = function(normal){
			if(!_s.isShowed_bl) return;
			_s.isShowed_bl = false;
			
			if(prt.customContextMenu_do) prt.customContextMenu_do.enable();
			_s.positionAndResize();
			
			clearTimeout(_s.hideCompleteId_to);
			clearTimeout(_s.showCompleteId_to);
			
			if(!FWDEVPUtils.isMobile || (FWDEVPUtils.isMobile && FWDEVPUtils.hasPointerEvent)) prt.main_do.setSelectable(false);
			if(normal){
				_s.mainHld.setY(-_s.sH);
				_s.hideCompleteHandler();
			}else{
				_s.hideCompleteId_to = setTimeout(_s.hideCompleteHandler, 800);
				FWDAnimation.killTweensOf(_s.mainHld);
				FWDAnimation.to(_s.mainHld, .8, {y:-_s.sH, ease:Expo.easeInOut});
			}

			
		
		};
		
		_s.hideCompleteHandler = function(){
			prt.main_do.removeChild(_s);
			_s.dispatchEvent(FWDEVPPassword.HIDE_COMPLETE);
		};
	
		if(_d.useHEX){
			_s.init();
		}
	};
		
	/* set prototype */
	FWDEVPPassword.setPrototype = function(){
		FWDEVPPassword.prototype = new FWDEVPDO("div");
	};
	
	FWDEVPPassword.ERROR = "error";
	FWDEVPPassword.CORRECT = "correct";
	FWDEVPPassword.HIDE_COMPLETE = "hideComplete";
	
	FWDEVPPassword.prototype = null;
	window.FWDEVPPassword = FWDEVPPassword;
}(window));