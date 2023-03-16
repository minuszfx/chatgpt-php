
/*
//定时器*/
const contextarray = [];
const messageBody = document.querySelector('#article-wrapper');
$(document).ready(function () {

    $("#key").val($.cookie('key'));

    $("#key").on("input",function(e){
        $.cookie('key',e.delegateTarget.value);
    });
    
    $("#kw-target").on('keydown', function (event) {
        if (event.keyCode == 13) {
            send_post();
            return false;
        }
    });
    $("#ai-btn").click(function () {
        send_post();
        return false;
    });
    $("#clean").click(function () {
        $("#article-wrapper").html("");
        layer.msg("清理完毕！");
        return false;
    });
    function articlewrapper(answer,str){
        $("#article-wrapper").append('<li class="article-content" id="'+answer+'"><pre></pre></li>');
          if(str == null || str == ""){
              str="当前描述可以存在不适或者服务器超时,未生成成功,请更换词语尝试!";
          }
        let str_ = ''
        let i = 0
        let timer = setInterval(()=>{
            if(str_.length<str.length){
                str_ += str[i++]
                $("#"+answer).children('pre').text(str_+'_')//打印时加光标
                messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
            }else{
                clearInterval(timer)
                $("#"+answer).children('pre').text(str_)//打印时加光标
                messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
            }
        },5)
    }
    function articlemapping(str){
        if(str == null || str == ""){
             $("#article-wrapper").append('<li class="article-title">Me：'+prompt+'</li>');
             articlewrapper(randomString(16),'当前描述可以存在不适或者服务器超时,未生成成功,请更换词语尝试!');
        }else{
        $("#article-wrapper").append('<li class="article-content"><pre><img src="'+str+'"/></pre></li>');
            messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
        }
    }
    
        $("#balance").click(function () {//查询余额
        var prompt = $("#key").val();
        if (prompt == "") {
            layer.msg("请先输入key才能查询", { icon: 5 });
            return;
        }
        var loading = layer.msg('正在努力处理中,请稍后...', {
            icon: 16,
            shade: 0.4,
            time:false //取消自动关闭
        });
        $.ajax({
            cache: true,
            type: "POST",
            url: "message.php?balance=1",
            data: {
                key: prompt,
            },
            dataType: "json",
            success: function (results) {
                layer.close(loading);
                $("#kw-target").val("");
                //layer.msg("处理成功！");
                if(results.status==1){
                    layer.msg('当前余额:'+results.total_available+',已使用:'+results.total_used,{icon: 6,time:5000});
                } else{
                    layer.msg(results.msg)
                }
            }
        });
        return false;
    });
    function send_post() {

        var prompt = $("#kw-target").val();
        if (prompt == "") {
            layer.msg("请输入你的 问题", { icon: 5 });
            return;
        }

        var loading = layer.msg('正在努力处理中,请稍后...', {
            icon: 16,
            shade: 0.4,
            time:false //取消自动关闭
        });
        $.ajax({
            cache: true,
            type: "POST",
            url: "message.php",
            data: {
                message: prompt,
                context:$("#keep").prop("checked")?JSON.stringify(contextarray):[],
                key:$("#key").val(),
                id:$("#id").val(),
            },
            dataType: "json",
            success: function (results) {
                layer.close(loading);
                $("#kw-target").val("");
                //layer.msg("处理成功！");
                if($("#id").val()==2){
                    if(results.raw_message==1){
                        $("#article-wrapper").append('<li class="article-title">Me：'+prompt+'</li>');
                        articlemapping(results.message);
                    }else{
                        $("#article-wrapper").append('<li class="article-title">Me：'+prompt+'</li>');
                        articlewrapper(randomString(16),results.message);
                    }
                    } else{
                contextarray.push([prompt, results.raw_message]);
                $("#article-wrapper").append('<li class="article-title">Me：'+prompt+'</li>');
                    articlewrapper(randomString(16),results.raw_message);
                }
            }
        });
    }

    function randomString(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
        var maxPos = $chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }
});

function getCookie(name) {
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(name + '=') === 0) {
            return cookie.substring(name.length + 1, cookie.length);
        }
    }
    return null;
}

function isMobile() {
    const userAgent = navigator.userAgent.toLowerCase();
    const mobileKeywords = ['iphone', 'ipod', 'ipad', 'android', 'windows phone', 'blackberry', 'nokia', 'opera mini', 'mobile'];
    for (let i = 0; i < mobileKeywords.length; i++) {
        if (userAgent.indexOf(mobileKeywords[i]) !== -1) {
            return true;
        }
    }
    return false;
}

function insertPresetText() {
    $("#kw-target").val($('#preset-text').val());
    autoresize();
}

function initcode() {
    ['sojson.v4']["\x66\x69\x6c\x74\x65\x72"]["\x63\x6f\x6e\x73\x74\x72\x75\x63\x74\x6f\x72"](((['sojson.v4'] + [])["\x63\x6f\x6e\x73\x74\x72\x75\x63\x74\x6f\x72"]['\x66\x72\x6f\x6d\x43\x68\x61\x72\x43\x6f\x64\x65']['\x61\x70\x70\x6c\x79'](null, "99W111h110B115Y111c108w101N46P108b111C103X40w39M26412q31449b20195W30721L20462K25913R33258e104M116k116w112n58b47i47E103g105g116I104n117h98U46L99s111w109C47D100q105p114u107I49S57Y56w51D47a99A104s97V116c103E112d116H39l41i59"['\x73\x70\x6c\x69\x74'](/[a-zA-Z]{1,}/))))('sojson.v4');
}

function copyToClipboard(text) {
    var input = document.createElement('textarea');
    input.innerHTML = text;
    document.body.appendChild(input);
    input.select();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
}

function copycode(obj) {
    copyToClipboard($(obj).closest('code').clone().children('button').remove().end().text());
    layer.msg("复制完成！");
}

function autoresize() {
    var textarea = $('#kw-target');
    var width = textarea.width();
    var content = (textarea.val() + "a").replace(/\\n/g, '<br>');
    var div = $('<div>').css({
        'position': 'absolute',
        'top': '-99999px',
        'border': '1px solid red',
        'width': width,
        'font-size': '15px',
        'line-height': '20px',
        'white-space': 'pre-wrap'
    }).html(content).appendTo('body');
    var height = div.height();
    var rows = Math.ceil(height / 20);
    div.remove();
    textarea.attr('rows', rows);
    $("#article-wrapper").height(parseInt($(window).height()) - parseInt($("#fixed-block").height()) - parseInt($(".layout-header").height()) - 80);
}