<?php
/**
  * wechat php test
  */

//define your token
require_once("order.class.php");
require_once("controlOrder.class.php");

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
                            break;
                        case "小幺":
                            $this->responseBlog();
                            break;
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
                    $keyword =explode('-',$keyword);
                    //$content = "预约成功\n"."电话：".$keyword[1]."\n姓名：".$keyword[2]."\n问题：".$keyword[3];
                    $content = "预约失败，请输入：预约-电话-姓名-问题";
                    
                    if(sizeof($keyword)==4)
                    {
                        $userOrder = new Order($keyword[2], $keyword[1], $keyword[3]);
                        ControlOrder::insert_order($userOrder);
                        $content = "预约成功\n"."姓名：".$userOrder->name."\n电话：".$userOrder->phone."\n问题：".$userOrder->problem;
                    }
                    
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
    public function responseBlog()
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
                            <ArticleCount>5</ArticleCount>
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

                    $title1 = "笔记本选购指南 （作者：电脑110技术顾问 孤独凤凰战士）";
                    $description1 = "哈工大电脑110俱乐部";
                    $picUrl1 = "http://1.diannao110weixin.sinaapp.com/image/1.jpg";
                    $url1 = "http://user.qzone.qq.com/1507733185/blog/1420944883";
                    
                    $title2 = "【小幺经验谈】电脑开不开机？且开且珍惜";
                    $description2 = "哈工大电脑110俱乐部";
                    $picUrl2 = "http://1.diannao110weixin.sinaapp.com/image/110.jpg";
                    $url2 = "http://user.qzone.qq.com/1507733185/blog/1420457404";

                    $title3 = "“微会”免费电话原理小探究";
                    $description3 = "哈工大电脑110俱乐部";
                    $picUrl3 = "http://1.diannao110weixin.sinaapp.com/image/110.jpg";
                    $url3 = "http://user.qzone.qq.com/1507733185/blog/1420285932";

                    $title4 = "Win7系统的C盘扩容（正确操作不会损坏磁盘资料）";
                    $description4 = "哈工大电脑110俱乐部";
                    $picUrl4 = "http://1.diannao110weixin.sinaapp.com/image/110.jpg";
                    $url4 = "http://user.qzone.qq.com/1507733185/blog/1420091111";

                    $title5 = "拆机清灰步骤图文详解 ps:适用于大多数笔记本电脑";
                    $description5 = "哈工大电脑110俱乐部";
                    $picUrl5 = "http://1.diannao110weixin.sinaapp.com/image/110.jpg";
                    $url5 = "http://user.qzone.qq.com/1507733185/blog/1419992711";
                    
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, 
                        $title1, $description1, $picUrl1, $url1, 
                        $title2, $description2, $picUrl2, $url2,
                        $title3, $description3, $picUrl3, $url3,
                        $title4, $description4, $picUrl4, $url4,
                        $title5, $description5, $picUrl5, $url5);
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
                    $picUrl = "http://1.diannao110weixin.sinaapp.com/image/1.jpg";
                    $url = "http://mp.weixin.qq.com/s?__biz=MzA4MDMzMjkwNg==&mid=203657939&idx=1&sn=29e673ce63cd5f5b257487bd58b83cfc#rd";

                    $title1 = "微信平台【义诊预约】功能说明";
                    $description1 = "哈工大电脑110俱乐部";
                    $picUrl1 = "http://1.diannao110weixin.sinaapp.com/image/1.jpg";
                    $url1 = "http://mp.weixin.qq.com/s?__biz=MzA4MDMzMjkwNg==&mid=203657939&idx=2&sn=350a553cfbf13de0763b56ed4cb9bc47#rd";
                    
                    $title2 = "微信平台【电脑110小幺】功能说明";
                    $description2 = "哈工大电脑110俱乐部";
                    $picUrl2 = "http://1.diannao110weixin.sinaapp.com/image/1.jpg";
                    $url2 = "http://mp.weixin.qq.com/s?__biz=MzA4MDMzMjkwNg==&mid=203659892&idx=1&sn=12e47a9f76965fd473add8d2b5528ecd#rd";
                    
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
