
// ajax封装
/**
 * funType  回调函数类型
 * @param opts
 */

var reqAjaxJson = {
    loadOpts: {},
    funType: null,
    funArr: ['complete','fail','noChange'],
    init: function(opts){
        var that = this;
        $.ajax({
            url:opts.url,
            data:opts.reqData,
            type:'POST',
            dataType:'json',
            success:function(data){
                opts.modalDom && opts.modalDom.modal('hide');
                that.funType = that.funArr[data.code]; //返回的是数据状态
                that.funType && (that.loadOpts = that.method[that.funType](data,opts.redirectP));
                that.loadOpts && kzLoading(that.loadOpts);
            }
        });
    },
    method: {
        complete: function(data,redirectP){
            var msgType = data['type'];
            return {
                content: data[msgType],
                callback:function(){
                    if(redirectP){
                        location.href = redirectP;
                    }
                }
            };
        },
        fail: function(data){
            var msgType = data['type'];
            return {
                content: data[msgType]
            };
        },
        noChange: function(data){
            var msgType = data['type'];
            return {
                content: data[msgType]
            };
        }
    }
};


function reqAjax(opts){
    var loadOpts = {},
        funType,
        funArr = ['complete','fail','noChange'];
    $.ajax({
        url:opts.url,
        data:opts.reqData,
        type:'POST',
        dataType:'json',
        success:function(data){
            opts.modalDom && opts.modalDom.modal('hide');
            funType = opts[funArr[data.code]]; //返回的是数据状态
            funType && (loadOpts = funType(data));
            kzLoading(loadOpts);
        }
    });
}

/**
 *
 * 删除
 * @param opts
 */
function modelReq(opts){
    var delId = 0;

    $(opts.btnClass).on("click",function(){
        delId = $(this).data('id');
    });

    // 删除分类
    $(opts.modalId).on("click",opts.sureClass,function(){
        reqAjaxJson.init({
            url: opts.url+"?id="+delId,
            reqData: "",
            modalDom: $(opts.modalId),
            redirectP: opts.redirectP
        });

    });
}


/**
 * 加载页面
 *
 * 点击按钮dom  --- clickDom
 * 弹窗内容区dom  --- modalConDom
 * 跳转链接  --- url
 *
 */
var loadPage = {
    trimBr: function (str) {
        str=str.replace(/\\r\\n|\\|"/g,"");
        return str;
    },
    init: function (opts) {
        var that = this;
        // 点击编辑
        opts.clickDom.on("click",function(){
            var curThat = $(this);
            var param_str = '';
            if(opts.attrArr){
                opts.attrArr.forEach(function(attr){
                    param_str += ("&"+attr+"="+curThat.data(attr+''));
                })
                if(param_str.indexOf('&') === 0){
                    param_str = param_str.replace(/&/,'?');
                }
            }
            opts.modalConDom.load(opts.url+param_str,'',function(result){
                opts.modalConDom.html(that.trimBr(result));
            });
        });
    }
};





