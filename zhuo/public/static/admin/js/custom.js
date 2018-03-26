/**
 * 单选和复选 二次封装                                                                                                                     [description]
 */
$(function(){
	$(".i-checks").hover(function(){
		$(this).find(".checkbox_square-green").addClass("hover");
	},function(){
		$(this).find(".checkbox_square-green").removeClass("hover");
	});

	// check("radio");
	// check("checkbox");
	check(["radio","checkbox"]);
	function check(type){
		var flag = false;

		function isArray(o){
			return Object.prototype.toString.call(o)=='[object Array]';
		}

		if(isArray(type)){
			for(var index in type){
				if(type[index] === "radio") {flag = true;}
				if(type[index] === "checkbox") {flag = false;}
				checkDisable(type[index],flag);
			}
		}else{
			if(type === "radio") {flag = true;}
			checkDisable(type,flag);
		}
	}

	function checkDisable(type,flag){
		var isChecked = isDisabled = false;
		var doms = $(".form-group").find("."+type);
		var dom = doms.find("."+type+"_square-green");
		dom.on("click",function(){
			var isChecked = $(this).hasClass("checked");
			var isDisabled = $(this).hasClass("disabled");
			if(isDisabled){
				return false;
			}
			if(flag){
				var pDoms = $(this).parents("."+type).siblings("."+type);
				pDoms.find(".radio_square").removeClass("checked");
				$(this).addClass("checked");
			}else{
				$(this)[isChecked ? "removeClass" : "addClass"]("checked");
				$(this).find("input").attr("checked",!isChecked);
			}
			return false;
		});
	}
});