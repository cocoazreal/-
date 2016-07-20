<?php
	include "TopSdk.php";
	date_default_timezone_set('Asia/Shanghai'); 

	$httpdns = new HttpdnsGetRequest;
	$client = new ClusterTopClient("4272","0ebbcccfee18d7ad1aebc5b135ffa906");
	$client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
	var_dump($client->execute($httpdns,"6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805"));

    $c = new TopClient;
    $c->appkey = '23293105';
    $c->secretKey = 'c8e46af46c375ceaf9cf264ace4733eb';
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("登录验证");
    $req->setSmsParam("{'code':'1234','product':'alidayu'}");
    $req->setRecNum("13554663692");
    $req->setSmsTemplateCode("SMS_4035564");
    $resp = $c->execute($req);
    var_dump($resp);
    $result = "success";
    echo $result;
?>