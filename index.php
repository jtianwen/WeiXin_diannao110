<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->checkMsg();
}


class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function checkMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();

                if(!empty( $keyword )){
                    if(preg_match("/预约-1/", $keyword)){
                         $this->responseOrder();
                    }
                    switch ($keyword)
                    {  
                        case "功能":
                            $this->responseFunction();
                        default:
                            $this->responseDefault();
                    }
                    
                }
        }
    }
    public function responseOrder()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml> ";             
                if(!empty( $keyword ))
                {
                    $msgType = "text";
                    $content = "预约成功";
                    
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);
                    echo $resultStr;
                }else{
                    echo "Input something...";
                }

        }else {
            echo "";
            exit;
        }
    }
    public function responseFunction()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>3</ArticleCount>
                            <Articles>
                            <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                            <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                            <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                            </Articles>
                            </xml> ";             
                if(!empty( $keyword ))
                {
                    $msgType = "news";
                    $title = "哈工大电脑110俱乐部";
                    $description = "哈工大电脑110俱乐部";
                    $picUrl = "http://1.tianwenweixintest.sinaapp.com/image/1.jpg";
                    $url = "http://mp.weixin.qq.com/s?__biz=MzA4MDMzMjkwNg==&mid=203657939&idx=1&sn=29e673ce63cd5f5b257487bd58b83cfc#rd";

                    $title1 = "微信平台【义诊预约】功能说明";
                    $description1 = "哈工大电脑110俱乐部";
                    $picUrl1 = "http://2.tianwenweixintest.sinaapp.com/image/1.jpg";
                    $url1 = "http://mp.weixin.qq.com/s?__biz=MzA4MDMzMjkwNg==&mid=203657939&idx=2&sn=350a553cfbf13de0763b56ed4cb9bc47#rd";
                    
                    $title2 = "微信平台【电脑110小幺】功能说明";
                    $description2 = "哈工大电脑110俱乐部";
                    $picUrl2 = "http://2.tianwenweixintest.sinaapp.com/image/1.jpg";
                    $url2 = "";
                    
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, 
                        $title, $description, $picUrl, $url, 
                        $title1, $description1, $picUrl1, $url1,
                        $title2, $description2, $picUrl2, $url2);
                    echo $resultStr;
                }else{
                    echo "Input something...";
                }

        }else {
            echo "";
            exit;
        }
    }
    public function responseDefault()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml> ";             
                if(!empty( $keyword ))
                {
                    $msgType = "text";
                    $content = "感谢您的留言。回复“功能”查看更多。";
                    
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);
                    echo $resultStr;
                }else{
                    echo "Input something...";
                }

        }else {
            echo "";
            exit;
        }
    }
        
    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>