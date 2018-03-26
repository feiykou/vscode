<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/12/15 15:28
 *
 */

namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    // 需要返回客户端的状态
    public function render(\Exception $e){
        echo 1;
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }
        else{
            //如果是调试模式，返回服务器具体的错误信息
            if(config('app_debug')){
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg = "服务器内部错误，不想告诉你";
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        // 返回到客户端的信息
        $request = Request::instance();
        $result = [
            'msg'        => $this->msg,
            'error_code' => $this->errorCode,
            'url'        => $request->url()
        ];
        return json($result,$this->code);
    }

    private function recordErrorLog(\Exception $e){
        echo 1;
        Log::init([
            // 日志记录方式，内置 file socket 支持扩展
            'type'  => 'File',
            // 日志保存目录
            'path'  => LOG_PATH,
            // 日志记录级别
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}