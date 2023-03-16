<?php
function balance($API_KEY){
    $options = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Authorization: Bearer " . $API_KEY."\r\n"."Content-type: application/json",
            'timeout' => 15 * 60 // 超时时间（单位:s）
        ),'ssl'=>array('verify_peer' => false,'verify_peer_name' => false)
    );
    $context = stream_context_create($options);
    $response = @file_get_contents('https://service-pg8o2i4q-1252415387.hk.apigw.tencentcs.com/dashboard/billing/credit_grants', false, $context);

    if (isset($response)) {

        $json_array = json_decode($response, true);
        return $json_array;
    }
}

function completions($API_KEY,$TEXT)
{
    $json_data = array(
    'max_tokens' => 2000,
    // 'messages' => array(
    //     array('role' => $selectedRole, 'content' => $TEXT)
    // )
    );
    $randomArray = array(
        array('role' => 'user', 'content' => $TEXT),
        array('role' => 'system', 'content' => $TEXT),
        array('role' => 'assistant', 'content' => $TEXT),
    );
    $randomArrayIndex = array_rand($randomArray);
    $randomMessage = array_slice($randomArray, $randomArrayIndex, 1);
    $json_data['messages'] = $randomMessage;
    // 定义两个模型名称
    $model_1 = 'gpt-3.5-turbo-0301';
    $model_2 = 'gpt-3.5-turbo';
    
    // 随机选择一个模型
    $random_model = random_int(0, 1) == 0 ? $model_1 : $model_2;
    
    //发送 Chat GPT API 请求并接收响应
    $json_data['model'] = $random_model;
    //构造 API 请求参数
    
    $params = json_encode($json_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Authorization: Bearer " . $API_KEY."\r\n"."Content-type: application/json",
            'content' => $params,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        ),'ssl'=>array('verify_peer' => false,'verify_peer_name' => false)
    );
    $context = stream_context_create($options);
    $urls = array(
        'https://chatgpt-api.shn.hk/v1/',
        'https://service-pg8o2i4q-1252415387.hk.apigw.tencentcs.com/v1/chat/completions',
        'https://chat-web-hdmaemigen.cn-hongkong.fcapp.run/v1/chat/completions',
        'https://lnkcast.com/v1/chat/completions'
    );
    foreach ($urls as $url) {
        $response = @file_get_contents($url, false,$context);
    
        if ($response !== false) {
            // 成功获取数据，处理响应并退出循环
            // $response = @file_get_contents('https://chat-web-hdmaemigen.cn-hongkong.fcapp.run/v1/chat/completions', false, $context);
            // $response = @file_get_contents('https://chatgpt-api.shn.hk/v1/', false, $context);
            $text = "服务器连接错误,请稍后再试!";
        
            if (isset($response)) {
                $json_array = json_decode($response, true);
                if( isset( $json_array['choices'][0]['message'] ) ) {
                    $text = str_replace( "\\n", "\n", $json_array['choices'][0]['message']['content'] );
                    // print($json_array);
                } elseif( isset( $json_array['error']['message']) ) {
                    $text = $json_array['error']['message'];
                } else {
                    $text = "余额不足,请购买key。";
                }
            }
            
            return $json_array;
        }
    }
    if ($response === false) {
        // 所有链接都无法访问，输出错误消息
        die('Failed to access any URL');
        // 在这里处理响应
        echo $response;
    }
    
}

function imges($API_KEY,$TEXT)
{

    $params = json_encode(array(
        'prompt' => $TEXT,
        "n" => 1,
        "size" => "1024x1024",

    ));

    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Authorization: Bearer " . $API_KEY."\r\n"."Content-type: application/json",
            'content' => $params,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        ),'ssl'=>array('verify_peer' => false,'verify_peer_name' => false)
    );
    $context = stream_context_create($options);
    $response = @file_get_contents('https://chat-web-hdmaemigen.cn-hongkong.fcapp.run/v1/images/generations', false, $context);

    $text['text'] ="服务器连接错误,请稍后再试!";
    $text['status'] = 0;
    if (isset($response)) {
        $json_array = json_decode($response, true);

        if( isset( $json_array['data'][0]['url'] ) ) {
            $text['status'] = 1;
            $text['text'] = str_replace( "\\n", "\n", $json_array['data'][0]['url'] );
        } elseif( isset( $json_array['error']['message']) ) {
            $text['status'] = 0;
            $text['text'] = $json_array['error']['message'];
        } else {
            $text['status'] = 0;
            $text['text'] = "出现一点小问题,可能是网络问题,也可能是您的关键字违规。";
        }
    }
    return $text;
}