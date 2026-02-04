/**
 * Easy Video Player PACKAGED v9.1
 * Embed window.
 *
 * @author Tibi - FWDesign [https://webdesign-flash.ro/]
 * Copyright © 2006 All Rights Reserved.
 */
(function (window){
	
	var FWDEVPEmbedWindow = function(_d, prt){

		'use strict';
		
		var _s = this;
		var prototype = FWDEVPEmbedWindow.prototype;
		
		_s.embedColoseN_img = _d.embedColoseN_img;
		_s.embedWindowBackground_str = _d.embedWindowBackground_str;
		_s.embedWindowInputBackgroundPath_str = _d.embedWindowInputBackgroundPath_str;
		_s.secondaryLabelsColor_str = _d.secondaryLabelsColor_str;
		_s.inputColor_str = _d.inputColor_str;
		_s.mainLabelsColor_str = _d.mainLabelsColor_str;
		_s.sendButtonNPath_str = _d.sendButtonNPath_str;
		_s.sendButtonSPath_str = _d.sendButtonSPath_str;
		_s.inputBackgroundColor_str = _d.inputBackgroundColor_str;
		_s.borderColor_str = _d.borderColor_str;
		_s.sendToAFriendPath_str = _d.sendToAFriendPath_str;
		
		_s.maxTextWidth = 0;
		_s.totalWidth = 0;
		_s.sW = 0;
		_s.sH = 0;
		_s.buttonWidth = 44;
		_s.buttonHeight = 19;
		_s.embedWindowCloseButtonMargins = _d.embedWindowCloseButtonMargins;
		_s.shareAndEmbedTextColor_str = _d.shareAndEmbedTextColor_str;
		_s.isMobile_bl = FWDEVPUtils.isMobile;
		_s.useVectorIcons_bl = _d.useVectorIcons_bl
	
		//#################################//
		/* init */
		//#################################//
		this.init = function(){
			prt.main_do.addChild(_s);
			if(_s.closeButton_do) return;
		
			_s.setBackfaceVisibility();
			_s.mainHolder_do = new FWDEVPDO("div");
			_s.mainHolder_do.hasT3D = false;
			_s.mainHolder_do.hasT2D = false;
			_s.mainHolder_do.setBackfaceVisibility();
			
			_s.bk_do = new FWDEVPDO("div");
			_s.bk_do.style().width = "100%";
			_s.bk_do.style().height = "100%";
			_s.bk_do.setAlpha(.9);
			_s.bk_do.style().background = "url('" + _s.embedWindowBackground_str + "')";

			//setup link and embed text
			_s.linkAndEmbedHolder_do =  new FWDEVPDO("div");
			_s.linkAndEmbedHolderBk_do = new FWDEVPDO("div");
			_s.linkAndEmbedHolderBk_do.style().background = "url('" + _s.embedWindowBackground_str + "')";
			_s.linkAndEmbedHolderBk_do.style().borderStyle = "solid";
			_s.linkAndEmbedHolderBk_do.style().borderWidth = "1px";
			_s.linkAndEmbedHolderBk_do.style().borderColor =  _s.borderColor_str;
			
			_s.mainLbl = new FWDEVPDO("div");
			_s.mainLbl.setBackfaceVisibility();
			_s.mainLbl.style().fontFamily = "Arial";
			_s.mainLbl.style().fontSize= "12px";
			_s.mainLbl.style().color = _s.mainLabelsColor_str;
			_s.mainLbl.style().whiteSpace= "nowrap";
			_s.mainLbl.style().fontSmoothing = "antialiased";
			_s.mainLbl.style().webkitFontSmoothing = "antialiased";
			_s.mainLbl.style().textRendering = "optimizeLegibility";
			_s.mainLbl.style().padding = "0px";
			_s.mainLbl.screen.className = 'EVP-main-label';
			_s.mainLbl.setInnerHTML("SHARE & EMBED");	
			
			_s.linkLbl = new FWDEVPDO("div");
			_s.linkLbl.screen.className = 'EVP-secnd-label';
			_s.linkLbl.setBackfaceVisibility();
			_s.linkLbl.style().fontFamily = "Arial";
			_s.linkLbl.style().fontSize= "12px";
			_s.linkLbl.style().color = _s.secondaryLabelsColor_str;
			_s.linkLbl.style().whiteSpace= "nowrap";
			_s.linkLbl.style().fontSmoothing = "antialiased";
			_s.linkLbl.style().webkitFontSmoothing = "antialiased";
			_s.linkLbl.style().textRendering = "optimizeLegibility";
			_s.linkLbl.style().padding = "0px";
			_s.linkLbl.setInnerHTML("Link to this video:");	
			
			_s.linkTxt = new FWDEVPDO("div");
			_s.linkTxt.screen.className = 'EVP-embed-inpt';
			_s.linkTxt.setBackfaceVisibility();
			_s.linkTxt.style().fontFamily = "Arial";
			_s.linkTxt.style().fontSize= "12px";
			_s.linkTxt.style().color = _s.shareAndEmbedTextColor_str;
			if(!FWDEVPUtils.isIEAndLessThen9) _s.linkTxt.style().wordBreak = "break-all";
			_s.linkTxt.style().fontSmoothing = "antialiased";
			_s.linkTxt.style().webkitFontSmoothing = "antialiased";
			_s.linkTxt.style().textRendering = "optimizeLegibility";
			_s.linkTxt.style().padding = "6px";
			_s.linkTxt.style().paddingTop = "4px";
			_s.linkTxt.style().paddingBottom = "4px";
			_s.linkTxt.style().backgroundColor = _s.inputBackgroundColor_str;
			_s.linkTxt.screen.onclick = selectText;
			
			_s.embedLbl = new FWDEVPDO("div");
			_s.embedLbl.screen.className = 'EVP-secnd-label';
			_s.embedLbl.setBackfaceVisibility();
			_s.embedLbl.style().fontFamily = "Arial";
			_s.embedLbl.style().fontSize= "12px";
			_s.embedLbl.style().color = _s.secondaryLabelsColor_str;
			_s.embedLbl.style().whiteSpace= "nowrap";
			_s.embedLbl.style().fontSmoothing = "antialiased";
			_s.embedLbl.style().webkitFontSmoothing = "antialiased";
			_s.embedLbl.style().textRendering = "optimizeLegibility";
			_s.embedLbl.style().padding = "0px";
			_s.embedLbl.setInnerHTML("Embed this video:");
			
			_s.embdTxt = new FWDEVPDO("div");
			_s.embdTxt.screen.className = 'EVP-embed-inpt';
			_s.embdTxt.setBackfaceVisibility();
			if(!FWDEVPUtils.isIEAndLessThen9) _s.embdTxt.style().wordBreak = "break-all";
			_s.embdTxt.style().fontFamily = "Arial";
			_s.embdTxt.style().fontSize= "12px";
			_s.embdTxt.style().lineHeight = "16px";
			_s.embdTxt.style().color = _s.shareAndEmbedTextColor_str;
			_s.embdTxt.style().fontSmoothing = "antialiased";
			_s.embdTxt.style().webkitFontSmoothing = "antialiased";
			_s.embdTxt.style().textRendering = "optimizeLegibility";
			_s.embdTxt.style().backgroundColor = _s.inputBackgroundColor_str;
			_s.embdTxt.style().padding = "6px";
			_s.embdTxt.style().paddingTop = "4px";
			_s.embdTxt.style().paddingBottom = "4px";
			_s.embdTxt.screen.onclick = selectText;
		
			//setup flash buttons
			FWDEVPSimpleSizeButton.setPrototype();
			_s.copyLinkButton_do = new FWDEVPSimpleSizeButton(
					_d.embedCopyButtonNPath_str,
					_d.embedCopyButtonSPath_str,
					_s.buttonWidth,
					_s.buttonHeight,
					_d.useHEX,
					_d.nBC,
					_d.sBC,
					true
			);
			_s.copyLinkButton_do.screen.style.position = 'absolute';
			_s.copyLinkButton_do.addListener(FWDEVPSimpleSizeButton.CLICK, function(){
				_s.copyToClipboard(_s.linkTxt.screen);
			});

			FWDEVPSimpleSizeButton.setPrototype();
			_s.copyEmbedBtn = new FWDEVPSimpleSizeButton(
					_d.embedCopyButtonNPath_str,
					_d.embedCopyButtonSPath_str,
					_s.buttonWidth,
					_s.buttonHeight,
					_d.useHEX,
					_d.nBC,
					_d.sBC,
					true
			);
			_s.copyEmbedBtn.screen.style.position = 'absolute';
			_s.copyEmbedBtn.addListener(FWDEVPSimpleSizeButton.CLICK,function(){
				_s.copyToClipboard(_s.embdTxt.screen);
			});

			//setup send to a friend
			_s.sendMainHolder_do =  new FWDEVPDO("div");
			
			_s.sendMainHldBk = new FWDEVPDO("div");
			_s.sendMainHldBk.style().background = "url('" + _s.embedWindowBackground_str + "')";
			_s.sendMainHldBk.style().borderStyle = "solid";
			_s.sendMainHldBk.style().borderWidth = "1px";
			_s.sendMainHldBk.style().borderColor =  _s.borderColor_str;
			
			_s.sendMainLbl = new FWDEVPDO("div");
			_s.sendMainLbl.setBackfaceVisibility();
			_s.sendMainLbl.style().fontFamily = "Arial";
			_s.sendMainLbl.style().fontSize= "12px";
			_s.sendMainLbl.style().color = _s.mainLabelsColor_str;
			_s.sendMainLbl.style().whiteSpace= "nowrap";
			_s.sendMainLbl.style().padding = "0px";
			_s.sendMainLbl.screen.className = 'EVP-main-label';
			_s.sendMainLbl.setInnerHTML("SEND TO A FRIEND");	
			
			_s.yourEmailLabel_do = new FWDEVPDO("div");
			_s.yourEmailLabel_do.screen.className = 'EVP-secnd-label';
			_s.yourEmailLabel_do.setBackfaceVisibility();
			_s.yourEmailLabel_do.style().fontFamily = "Arial";
			_s.yourEmailLabel_do.style().fontSize= "12px";
			_s.yourEmailLabel_do.style().color = _s.secondaryLabelsColor_str;
			_s.yourEmailLabel_do.style().whiteSpace= "nowrap";
			_s.yourEmailLabel_do.style().padding = "0px";
			_s.yourEmailLabel_do.setInnerHTML("Your email:");
			
			_s.yourEmailInpt = new FWDEVPDO("input");
			_s.yourEmailInpt.screen.className = 'EVP-embed-inpt';
			_s.yourEmailInpt.setBackfaceVisibility();
			_s.yourEmailInpt.style().fontFamily = "Arial";
			_s.yourEmailInpt.style().fontSize= "12px";
			_s.yourEmailInpt.style().backgroundColor = _s.inputBackgroundColor_str;
			_s.yourEmailInpt.style().color = _s.inputColor_str;
			_s.yourEmailInpt.style().outline = 0;
			_s.yourEmailInpt.style().whiteSpace= "nowrap";
			_s.yourEmailInpt.style().padding = "6px";
			_s.yourEmailInpt.style().paddingTop = "4px";
			_s.yourEmailInpt.style().paddingBottom = "4px";
			
			_s.friendEmailLbl = new FWDEVPDO("div");
			_s.friendEmailLbl.screen.className = 'EVP-secnd-label';
			_s.friendEmailLbl.setBackfaceVisibility();
			_s.friendEmailLbl.style().fontFamily = "Arial";
			_s.friendEmailLbl.style().fontSize= "12px";
			_s.friendEmailLbl.style().color = _s.secondaryLabelsColor_str;
			_s.friendEmailLbl.style().whiteSpace= "nowrap";
			_s.friendEmailLbl.style().padding = "0px";
			_s.friendEmailLbl.setInnerHTML("Your friend's email:");
			
			_s.friendEmailInpt = new FWDEVPDO("input");
			_s.friendEmailInpt.screen.className = 'EVP-embed-inpt';
			_s.friendEmailInpt.setBackfaceVisibility();
			_s.friendEmailInpt.style().fontFamily = "Arial";
			_s.friendEmailInpt.style().fontSize= "12px";
			_s.friendEmailInpt.style().backgroundColor = _s.inputBackgroundColor_str;
			_s.friendEmailInpt.style().color = _s.inputColor_str;
			_s.friendEmailInpt.style().outline= 0;
			_s.friendEmailInpt.style().whiteSpace= "nowrap";
			_s.friendEmailInpt.style().padding = "6px";
			_s.friendEmailInpt.style().paddingTop = "4px";
			_s.friendEmailInpt.style().paddingBottom = "4px";	
			
			FWDEVPSimpleSizeButton.setPrototype();
			_s.sndBtn = new FWDEVPSimpleSizeButton(
					_s.sendButtonNPath_str, 
					_s.sendButtonSPath_str,
					_s.buttonWidth,
					_s.buttonHeight,
					_d.useHEX,
					_d.nBC,
					_d.sBC,
					true
					);
			_s.sndBtn.addListener(FWDEVPSimpleSizeButton.CLICK, _s.sendClickHandler);
					
			_s.infoText_do = new FWDEVPDO("div");
			_s.infoText_do.setBackfaceVisibility();
			_s.infoText_do.style().fontFamily = "Arial";
			_s.infoText_do.style().fontSize= "12px";
			_s.infoText_do.style().color = _s.secondaryLabelsColor_str;
			_s.infoText_do.style().whiteSpace= "nowrap";
			_s.infoText_do.style().fontSmoothing = "antialiased";
			_s.infoText_do.style().webkitFontSmoothing = "antialiased";
			_s.infoText_do.style().textRendering = "optimizeLegibility";
			_s.infoText_do.style().padding = "0px";
			_s.infoText_do.style().paddingTop = "4px";
			_s.infoText_do.style().textAlign = "center";
			_s.infoText_do.style().color = _s.mainLabelsColor_str;
		
			//setup close button
			if(_s.useVectorIcons_bl){
				FWDEVPSimpleButton.setPrototype();
				FWDEVPUtils.smpBtnNPos();
				var ic = prt.fontIcon +' ' + prt.fontIcon + '-close';
				_s.closeButton_do = new FWDEVPSimpleButton(
						undefined, undefined, undefined, true, undefined, undefined, undefined,
						"<div class='table-fwdevp-button'><span class='table-cell-fwdevp-button " + ic + "'></span></div>",
						undefined,
						"EVPCloseButtonNormalState",
						"EVPCloseButtonSelectedState"
				);
				
			}else{
				FWDEVPSimpleButton.setPrototype();
				_s.closeButton_do = new FWDEVPSimpleButton(_d.embedClooseN_img, _d.embedWindowClosePathS_str, undefined,
						true,
						_d.useHEX,
						_d.nBC,
						_d.sBC,
						false, false, false, false, true);
			}
			
			_s.closeButton_do.addListener(FWDEVPSimpleButton.MOUSE_UP, _s.closeButtonOnMouseUpHandler);
			
			_s.addChild(_s.mainHolder_do);
			_s.mainHolder_do.addChild(_s.bk_do);
			
			_s.linkAndEmbedHolder_do.addChild(_s.linkAndEmbedHolderBk_do);
			_s.linkAndEmbedHolder_do.addChild(_s.mainLbl);
			_s.linkAndEmbedHolder_do.addChild(_s.linkLbl);
			_s.linkAndEmbedHolder_do.addChild(_s.linkTxt);
			_s.linkAndEmbedHolder_do.addChild(_s.embedLbl);
			_s.linkAndEmbedHolder_do.addChild(_s.embdTxt);
			_s.linkAndEmbedHolder_do.addChild(_s.copyLinkButton_do);
			_s.linkAndEmbedHolder_do.addChild(_s.copyEmbedBtn);
			
			_s.sendMainHolder_do.addChild(_s.sendMainHldBk);
			_s.sendMainHolder_do.addChild(_s.sendMainLbl);
			_s.sendMainHolder_do.addChild(_s.yourEmailLabel_do);
			_s.sendMainHolder_do.addChild(_s.yourEmailInpt);
			_s.sendMainHolder_do.addChild(_s.friendEmailLbl);
			_s.sendMainHolder_do.addChild(_s.friendEmailInpt);
			_s.sendMainHolder_do.addChild(_s.sndBtn);
			
			_s.mainHolder_do.addChild(_s.linkAndEmbedHolder_do);
			_s.mainHolder_do.addChild(_s.sendMainHolder_do);
			
			_s.mainHolder_do.addChild(_s.closeButton_do); 
		};
	
		this.closeButtonOnMouseUpHandler = function(){
			if(!_s.isShowed_bl) return;
			_s.hide();
		};

		this.copyToClipboard = function(element){
			  selectText(element);
			  document.execCommand("copy");
		}
	
		function selectText(element){
			if(window.top != window && FWDEVPUtils.isIE) return;
			if(!element) element = this;

			var range, selection;
			if (document.body.createTextRange) {
				range = document.body.createTextRange();
			    range.moveToElementText(element);
			    range.select();
			}else if(window.getSelection && document.createRange) {
			    selection = window.getSelection();
			    range = document.createRange();
			    range.selectNodeContents(element);
			    selection.removeAllRanges();
			    selection.addRange(range);
			}
		};
		
		this.positionAndResize = function(){
			_s.sW = prt.sW;
			_s.sH = prt.sH;
			
			_s.maxTextWidth = Math.min(_s.sW - 150, 500);
			_s.totalWidth = _s.maxTextWidth + _s.buttonWidth + 40;
			
			if(_s.isMobile_bl){
				_s.linkTxt.setWidth(_s.maxTextWidth + 52);
				_s.embdTxt.setWidth(_s.maxTextWidth + 52);
			}else{
				_s.linkTxt.setWidth(_s.maxTextWidth);
				_s.embdTxt.setWidth(_s.maxTextWidth);
			}
			
			_s.positionFinal();
			
			_s.closeButton_do.setX(_s.sW - _s.closeButton_do.w - _s.embedWindowCloseButtonMargins);
			_s.closeButton_do.setY(_s.embedWindowCloseButtonMargins);
			
			_s.setWidth(_s.sW);
			_s.setHeight(_s.sH);
			_s.mainHolder_do.setWidth(_s.sW);
			_s.mainHolder_do.setHeight(_s.sH);
		};
		
		this.positionFinal = function(){
			
			var totalHeight;
			var isEmbeddedAndFScreenOnIE11Bug_bl = false;
			
			if(_s.sH < 360 || _s.sW < 350){
				_s.linkTxt.style().whiteSpace= "nowrap";
				_s.embdTxt.style().whiteSpace= "nowrap";
			}else{
				_s.linkTxt.style().whiteSpace = "normal";
				_s.embdTxt.style().whiteSpace= "normal";
			}
			
			if(_s.linkLbl.screen.offsetHeight < 6) isEmbeddedAndFScreenOnIE11Bug_bl = true;
			
			var embedAndLinkMainLabelHeight;
			if(isEmbeddedAndFScreenOnIE11Bug_bl){
				embedAndLinkMainLabelHeight = Math.round(_s.mainLbl.screen.getBoundingClientRect().height * 100);
			}else{
				embedAndLinkMainLabelHeight = _s.mainLbl.getHeight();
			}
			_s.mainLbl.setX(16);
			_s.linkLbl.setX(16);
			_s.linkLbl.setY(embedAndLinkMainLabelHeight + 14);

			var linkTextLabelHeight;
			var linkTextHeight;
			if(isEmbeddedAndFScreenOnIE11Bug_bl){
				linkTextLabelHeight = Math.round(_s.linkLbl.screen.getBoundingClientRect().height * 100);
				linkTextHeight = Math.round(_s.linkTxt.screen.getBoundingClientRect().height * 100);
			}else{
				linkTextLabelHeight = _s.linkLbl.getHeight();
				linkTextHeight = _s.linkTxt.getHeight();
			}
			
			_s.linkTxt.setX(10);
			_s.linkTxt.setY(_s.linkLbl.y + linkTextLabelHeight + 5);
			if(_s.isMobile_bl){
				_s.copyLinkButton_do.setX(-100);
			}else{
				_s.copyLinkButton_do.setX(_s.maxTextWidth + 30);
			}
			
			_s.copyLinkButton_do.setY(_s.linkTxt.y + linkTextHeight - _s.buttonHeight);
			_s.embedLbl.setX(16);
			_s.embedLbl.setY(_s.copyLinkButton_do.y + _s.copyLinkButton_do.h + 14);
			
			var embedTextHeight;
			if(isEmbeddedAndFScreenOnIE11Bug_bl){
				embedTextHeight = Math.round(_s.embdTxt.screen.getBoundingClientRect().height * 100);
			}else{
				embedTextHeight = _s.embdTxt.getHeight();
			}
			_s.embdTxt.setX(10);
			_s.embdTxt.setY(_s.embedLbl.y + linkTextLabelHeight + 5);
			if(_s.isMobile_bl){
				_s.copyEmbedBtn.setX(-100);
			}else{
				_s.copyEmbedBtn.setX(_s.maxTextWidth + 30);
			}
			_s.copyEmbedBtn.setY(_s.embdTxt.y + embedTextHeight - _s.buttonHeight);
			_s.linkAndEmbedHolderBk_do.setY(_s.linkLbl.y - 9);
			_s.linkAndEmbedHolderBk_do.setWidth(_s.totalWidth - 2);
			_s.linkAndEmbedHolderBk_do.setHeight(_s.embdTxt.y + embedTextHeight - 9);
			_s.linkAndEmbedHolder_do.setWidth(_s.totalWidth);
			_s.linkAndEmbedHolder_do.setHeight(_s.embdTxt.y + embedTextHeight + 14);
			
			var sendMainLabelHeight;
			var inputHeight;
			if(isEmbeddedAndFScreenOnIE11Bug_bl){
				sendMainLabelHeight = Math.round(_s.sendMainLbl.screen.getBoundingClientRect().height * 100);
				inputHeight = Math.round(_s.yourEmailInpt.screen.getBoundingClientRect().height * 100);
			}else{
				sendMainLabelHeight = _s.sendMainLbl.getHeight();
				inputHeight = _s.yourEmailInpt.getHeight();
			}
			_s.sendMainLbl.setX(16);
			_s.yourEmailLabel_do.setX(16);
			_s.yourEmailLabel_do.setY(sendMainLabelHeight + 14);
			
			if(_s.sW > 400){
				_s.yourEmailInpt.setX(10);
				_s.yourEmailInpt.setWidth(parseInt(_s.totalWidth - 52 - _s.buttonWidth)/2);
				_s.yourEmailInpt.setY(_s.yourEmailLabel_do.y + linkTextLabelHeight + 5);
				
				_s.friendEmailLbl.setX(_s.yourEmailInpt.x + _s.yourEmailInpt.w + 26);
				_s.friendEmailLbl.setY(_s.yourEmailLabel_do.y);
				_s.friendEmailInpt.setX(_s.yourEmailInpt.x + _s.yourEmailInpt.w + 20);
				_s.friendEmailInpt.setWidth(parseInt((_s.maxTextWidth - 30)/2));
				_s.friendEmailInpt.setY(_s.yourEmailLabel_do.y + linkTextLabelHeight + 5);
				_s.sndBtn.setX(_s.friendEmailInpt.x + _s.yourEmailInpt.w + 10);
				_s.sndBtn.setY(_s.friendEmailInpt.y +inputHeight - _s.buttonHeight);
			}else{
				_s.yourEmailInpt.setX(10);
				_s.yourEmailInpt.setWidth(_s.totalWidth -32);
				_s.yourEmailInpt.setY(_s.yourEmailLabel_do.y + linkTextLabelHeight + 5);
				
				_s.friendEmailLbl.setX(16);
				_s.friendEmailLbl.setY(_s.yourEmailInpt.y + inputHeight + 14);
				_s.friendEmailInpt.setX(10);
				_s.friendEmailInpt.setY(_s.friendEmailLbl.y + linkTextLabelHeight + 5);
				_s.friendEmailInpt.setWidth(_s.totalWidth - 32);
				
				_s.sndBtn.setX(_s.totalWidth - _s.buttonWidth - 10);
				_s.sndBtn.setY(_s.friendEmailInpt.y + inputHeight + 10);
			}
			
			_s.sendMainHldBk.setY(_s.yourEmailLabel_do.y - 9);
			_s.sendMainHldBk.setWidth(_s.totalWidth - 2);
			_s.sendMainHldBk.setHeight(_s.sndBtn.y + _s.sndBtn.h - 9);
			_s.sendMainHolder_do.setWidth(_s.totalWidth);
			_s.sendMainHolder_do.setHeight(_s.sndBtn.y + _s.sndBtn.h + 14);
			
			if(isEmbeddedAndFScreenOnIE11Bug_bl){
				totalHeight = Math.round(_s.linkAndEmbedHolder_do.screen.getBoundingClientRect().height * 100 + _s.sendMainHolder_do.screen.getBoundingClientRect().height * 100);
			}else{
				totalHeight = _s.linkAndEmbedHolder_do.getHeight() + _s.sendMainHolder_do.getHeight();
			}
			
			
			_s.linkAndEmbedHolder_do.setX(parseInt((_s.sW - _s.totalWidth)/2));
			_s.linkAndEmbedHolder_do.setY(parseInt((_s.sH - totalHeight)/2) - 8);
			_s.sendMainHolder_do.setX(parseInt((_s.sW - _s.totalWidth)/2));
			if(isEmbeddedAndFScreenOnIE11Bug_bl){
				_s.sendMainHolder_do.setY(Math.round(_s.linkAndEmbedHolder_do.y + _s.linkAndEmbedHolder_do.screen.getBoundingClientRect().height * 100 + 20));
			}else{
				_s.sendMainHolder_do.setY(_s.linkAndEmbedHolder_do.y + _s.linkAndEmbedHolder_do.getHeight() + 20);
			}
		};
		

		//##############################################//
		/* Send email */
		//##############################################//
		this.sendClickHandler = function(){
			var hasError_bl = false;
			if(!_s.getValidEmail(_s.yourEmailInpt.screen.value)){
				if(FWDAnimation.isTweening(_s.yourEmailInpt.screen)) return;
				FWDAnimation.to(_s.yourEmailInpt.screen, .1, {css:{backgroundColor:'#FF0000'}, yoyo:true, repeat:3});
				hasError_bl = true;
			}
			if(!_s.getValidEmail(_s.friendEmailInpt.screen.value)){
				if(FWDAnimation.isTweening(_s.friendEmailInpt.screen)) return;
				FWDAnimation.to(_s.friendEmailInpt.screen, .1, {css:{backgroundColor:'#FF0000'}, yoyo:true, repeat:3});
				hasError_bl = true;
			}
			if(hasError_bl) return;
			_s.sendEmail();
		};
		

		//############ send email ####################//
		this.sendEmail = function(){
			if(_s.isSending_bl) return;
			_s.isSending_bl = true;
			_s.xhr = new XMLHttpRequest();
			_s.xhr.onreadystatechange = _s.onChange;
			_s.xhr.onerror = _s.ajaxOnErrorHandler;
			
			try{
				_s.xhr.open("get", _s.sendToAFriendPath_str + "?friendMail=" + _s.friendEmailInpt.screen.value + "&yourMail=" + _s.yourEmailInpt.screen.value + "&link=" + encodeURIComponent(_s.linkToVideo_str) , true);
				_s.xhr.send();
			}catch(e){
				_s.showInfo("ERROR", true);
				if(console) console.log(e);
				if(e.message) console.log(e.message);
			}
			_s.resetInputs();
		};
		
		this.ajaxOnErrorHandler = function(e){
			_s.showInfo("ERROR", true);
			try{
				if(window.console) console.log(e);
				if(window.console) console.log(e.message);
			}catch(e){};
			_s.isSending_bl = false;
		};
		
		this.onChange = function(response){
			if(_s.xhr.readyState == 4 && _s.xhr.status == 200){
				if(_s.xhr.responseText == "sent"){
					_s.showInfo("SENT");
				}else{
					_s.showInfo("ERROR", true);
					if(window.console) console.log("Error The server can't send the email!");
				}
				_s.isSending_bl = false;
			}
		};
		
		this.resetInputs = function(){
			_s.yourEmailInpt.screen.value = "";
			_s.friendEmailInpt.screen.value = "";
		};
	
		this.getValidEmail = function(email){
			var emailRegExp = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(!emailRegExp.test(email) || email == "") return false;
			return true;
		};
		

		//#############################################//
		/* Set embed _d */
		//#############################################//
		this.setEmbedData = function(){
		
			var allUrl = location.href;
			var host = location.protocol + "//" + location.host;
			var pathName = location.pathname;
			var hash = location.hash;
			var search = location.search;
			var pathWithoutHashOrSearch = host + pathName;
		
			search = search.replace(/&?EVPInstanceName=.+/g, "");
			hash = hash.replace(/&?EVPInstanceName=.+/g, "");
			allUrl = allUrl.replace(/&?EVPInstanceName=.+/g, "");
			if(search == "?") search = null;
			
			if(search){
				if(hash){
					_s.finalEmbedPath_str = pathWithoutHashOrSearch + search + hash + "&EVPInstanceName=" + prt.instanceName_str;
					_s.linkToVideo_str = pathWithoutHashOrSearch + search + hash;
				}else{
					_s.finalEmbedPath_str = pathWithoutHashOrSearch + search + "&EVPInstanceName=" + prt.instanceName_str;
					_s.linkToVideo_str = pathWithoutHashOrSearch + search;
				}
			}else{
				if(hash){
					_s.finalEmbedPath_str = pathWithoutHashOrSearch + hash + "?EVPInstanceName=" + prt.instanceName_str;
					_s.linkToVideo_str = pathWithoutHashOrSearch + hash;
				}else{
					_s.finalEmbedPath_str = pathWithoutHashOrSearch + "?EVPInstanceName=" + prt.instanceName_str;
					_s.linkToVideo_str = pathWithoutHashOrSearch;
				}
			}
		
			_s.finalEmbedPath_str = encodeURI(_s.finalEmbedPath_str);
			_s.linkToVideo_str = encodeURI(_s.linkToVideo_str);	
			_s.finalEmbedCode_str = "<iframe src='" + _s.finalEmbedPath_str + "' width='" + prt.sW + "' height='" + prt.sH + "' frameborder='0' scrolling='no' allowfullscreen></iframe>";
		
			if(FWDEVPUtils.isIE){
				_s.linkTxt.screen.innerText = _s.linkToVideo_str;
				_s.embdTxt.screen.innerText = _s.finalEmbedCode_str;
			}else{
				_s.linkTxt.screen.textContent = _s.linkToVideo_str;
				_s.embdTxt.screen.textContent = _s.finalEmbedCode_str;
			}
		};	
		

		//#########################################//
		/* show hide info */
		//#########################################//
		this.showInfo = function(text, hasError){
				
			_s.infoText_do.setInnerHTML(text);
			_s.sendMainHolder_do.addChild(_s.infoText_do);
			_s.infoText_do.setWidth(_s.buttonWidth);
			_s.infoText_do.setHeight(_s.buttonHeight - 4);
			_s.infoText_do.setX(_s.sndBtn.x);
			_s.infoText_do.setY(_s.sndBtn.y - 23);

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
		this.show = function(id){
			if(_s.isShowed_bl) return;
			_s.isShowed_bl = true;
			prt.main_do.addChild(_s);
			_s.init();
			
			_s.resetInputs();
			_s.setEmbedData();
			if(!FWDEVPUtils.isMobile || (FWDEVPUtils.isMobile && FWDEVPUtils.hasPointerEvent)) prt.main_do.setSelectable(true);

			if(_s.useVectorIcons_bl){
				_s.checkButtonsId_to = setInterval(function(){
					
					if(_s.closeButton_do.w != 0){	
						_s.positionAndResize();
						
						clearInterval(_s.checkButtonsId_to);
						clearTimeout(_s.hideCompleteId_to);
						clearTimeout(_s.showCompleteId_to);
						_s.mainHolder_do.setY(- _s.sH);
						
						_s.showCompleteId_to = setTimeout(_s.showCompleteHandler, 900);
						setTimeout(function(){
							FWDAnimation.to(_s.mainHolder_do, .8, {y:0, delay:.1, ease:Expo.easeInOut});
						}, 100);
					
					}
				
				}, 50);
			}else{
				_s.positionAndResize();
			
				clearTimeout(_s.hideCompleteId_to);
				clearTimeout(_s.showCompleteId_to);
				_s.mainHolder_do.setY(- _s.sH);
				
				_s.showCompleteId_to = setTimeout(_s.showCompleteHandler, 900);
				setTimeout(function(){
					FWDAnimation.to(_s.mainHolder_do, .8, {y:0, delay:.1, ease:Expo.easeInOut});
				}, 100);
			}
		};
		
		this.showCompleteHandler = function(){};
		
		this.hide = function(){
			if(!_s.isShowed_bl) return;
			_s.isShowed_bl = false;
			
			if(prt.customContextMenu_do) prt.customContextMenu_do.enable();
			_s.positionAndResize();
			
			clearTimeout(_s.hideCompleteId_to);
			clearTimeout(_s.showCompleteId_to);
			
			if(!FWDEVPUtils.isMobile || (FWDEVPUtils.isMobile && FWDEVPUtils.hasPointerEvent)) prt.main_do.setSelectable(false);
			_s.hideCompleteId_to = setTimeout(_s.hideCompleteHandler, 800);
			FWDAnimation.killTweensOf(_s.mainHolder_do);
			FWDAnimation.to(_s.mainHolder_do, .8, {y:-_s.sH, ease:Expo.easeInOut});
		};
		
		this.hideCompleteHandler = function(){
			prt.main_do.removeChild(_s);
			_s.dispatchEvent(FWDEVPEmbedWindow.HIDE_COMPLETE);
		};
	
		if(_d.useHEX){
			_s.init();
		}
	};
		
		
	/* set prototype */
	FWDEVPEmbedWindow.setPrototype = function(){
		FWDEVPEmbedWindow.prototype = new FWDEVPDO("div");
	};
	
	FWDEVPEmbedWindow.ERROR = "error";
	FWDEVPEmbedWindow.HIDE_COMPLETE = "hideComplete";
	
	FWDEVPEmbedWindow.prototype = null;
	window.FWDEVPEmbedWindow = FWDEVPEmbedWindow;
}(window));