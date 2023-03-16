<?php
require_once(__DIR__."/config.php");

header( "Content-Type: application/json" );
$context = json_decode( $_POST['context'] ?? "[]" ) ?: [];
//这里可以替换成你自己的key 共享key稳定性极差， 需要可联系qq:872672419 
//$open_ai_key =$_POST['key']?:'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';
$open_ai_key =$_POST['key']?:'sk-xxxxxx';

if(!empty($_GET['balance'])){
    if(empty($_POST['key'])){
        echo json_encode( [
            "msg" =>"请输入key在查询",
            "status" => "0",
        ] );
        exit();
    }
    $info=balance($open_ai_key);
    if(!$info){
        echo json_encode( [
            "msg" =>"key错误",
            "status" => "0",
        ] );
        exit();
    }
    echo json_encode( [
        "total_available" => $info['total_available'],//剩余
        "total_used"=>$info['total_used'],//已使用
        "total_granted"=>$info['total_granted'],//全部
        "status" => "1",
    ] );
    exit();
}

// 设置默认的请求文本prompt
$prompt = "";

// 添加文本到prompt
if( empty( $context ) ) {
    // 如果没有内容，下面是默认内容
    $prompt .= "";
    $please_use_above = "";
} else {
    // 将上次的问题和答案作为问题进行提交
    $prompt .= "";
    $context = array_slice( $context, -5 );
    foreach( $context as $message ) {
        $prompt .= "Question:\n" . $message[0] . "\n\nAnswer:\n" . $message[1] . "\n\n";
    }
    $please_use_above = ". Please use the questions and answers above as context for the answer.";
}
// add new question to prompt
$prompt = $prompt . "Question:\n" . $_POST['message'] . $please_use_above . "\n\nAnswer:\n\n";


// create a new completion
if($_POST['id']==2){
    // balance($open_ai_key);
    exit();
} else {
    // if (isset($_POST["role"])) {
    //     // 获取用户选择的角色并将其设置为默认值
    //     $selectedRole = $_POST["role"];
    // } else {
    //     // 如果 $_POST["role"] 变量不存在，则设置默认值
    //     $selectedRole = "assistant";
    // }
      // 处理表单数据和相应的逻辑
    $json_array=completions($open_ai_key,$prompt);
    $text = str_replace( "\\n", "\n", $json_array['choices'][0]['message']['content'] );
    echo json_encode( [
        "message" => str_replace(array("\r\n", "\r", "\n"), "", strip_tags($text)),
        "raw_message" => $text,
        "data" => $json_array,
        "status" => "success",]);
    
    exit();
}
