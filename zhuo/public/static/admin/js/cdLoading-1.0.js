//function($)$符号不要忘记写了
//结尾的(jQuery)必须严格区分大小写
(function(win){
	//插件入口
	tmLoading = function(options){
		var opts = $.extend({},tmLoading.methods,tmLoading.defaults,options);
		opts.init($(this),opts);
		$("#tm_loading").find("#tm_loading_content").html(opts.content);
		opts.$mask.show();
	};

	/*提供一系列的封装方法*/
	tmLoading.methods = {
		init : function($this,opts){//初始化
			var emptyFlag = $("#tm_loading").html();
			var $divBox = $("#tm_loading");
			if(!emptyFlag){
				//获取模板
				$divBox = this.template(opts);
				this.showAnimate($divBox,opts);
				if(opts.width)$divBox.width(opts.width);
				if(opts.width)$divBox.height(opts.height);
				if(opts.closeFlag)this.bindEvent($divBox,opts);
				opts.$mask = this.mask();
				//模板追加到body
				var $box = opts.parentDom || $("body");
				$box.append($divBox,opts);
				$box.append(opts.$mask);
				//给div定位
				this._position($divBox,opts);
				this._resize($divBox,opts);
			}else{
				$("#tm_loading").hide().find("#tm_loading_content").html(opts.content);
				this.showAnimate($("#tm_loading"),opts);
			}
			//事件的回调
			if(opts.callback)opts.callback($this,$divBox);
		},
		showAnimate : function($divBox,opts){
			if(opts.animate=='fade'){
				$divBox.fadeIn("slow");
			}else if(opts.animate=="slide"){
				$divBox.slideDown("slow");
			}else{
				$divBox.show();
			}
		},
		bindEvent:function($divBox,opts){//事件绑定
			$divBox.on("click",function(){
				if(opts.animate=='fade'){
					$(this).fadeOut("slow",function(){
						$(this).remove();
					});
				}else if(opts.animate=="slide"){
					$(this).slideUp("slow",function(){
						$(this).remove();
					});
				}else{
					$(this).remove();
				}
			});
		},
		_position : function($divBox,opts){//div定位元素的定位
			//loading自身的宽度和高度
			var width = $divBox.width();
			var height = $divBox.height();
			//这里是窗体的宽度和高度
			var $parentDom = (opts.parentDom || $(window));
			$parentDom.css("position","relative");
			var windowWidth = $parentDom.width();
			var windowHeight = $parentDom.height();
			var left = (windowWidth - width)/2;
			var top = 300;
			$divBox.css({"left":left,"top":top});
		},
		//窗体自适应
		_resize : function($divBox,opts){
			$(window).resize(function(){
				opts._position($divBox,opts);
			});
		},
		template : function(opts){//模板
			return $("<div id='cd_loading'><span id='cd_loading_content'>"+opts.content+"</span></div>");//这个就是动态创建一个元素
		},
		mask:function(){
			return $("<div class='overlay-mask'>");
		}
	};

	//默认参数定义
	tmLoading.defaults = {
		width:"145",
		height:"",
		closeFlag :true,	
		content:"数据拼命加载中...",
		animate:"slow",
		parentDom:null,
		// delayTime:0,
		callback:function($this){
			
		}
	}

	win.cdLoading = tmLoading;
})(window);

//隐藏方法
var loadHide = {
	loadCall:function(boxObj){
			this.hide = function(){
				boxObj.fadeOut();
				boxObj.next().fadeOut();
			}
	},
	hide:null
};