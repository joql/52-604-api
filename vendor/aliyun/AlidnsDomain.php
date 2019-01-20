<?php
/*
 * 加密浏览地址
 * --Made by Luyao
 */

if(!defined('ALIDNS_DOMAIN_ROOT')){
    define('ALIDNS_DOMAIN_ROOT',dirname(__FILE__).'/');
    require_once(ALIDNS_DOMAIN_ROOT.'aliyun-php-sdk-core/Config.php');
}
use Alidns\Request\V20150109 as Domain;
class AlidnsDomain {
	// 添加解析记录
	public function domainParsing($access_key_id, $access_key_secret, $args){
	   $iClientProfile = DefaultProfile::getProfile("cn-hangzhou",$access_key_id,$access_key_secret);
	   $client = new DefaultAcsClient($iClientProfile);

	   $request = new Domain\AddDomainRecordRequest();
	   $request->setMethod("GET");
	   $request->setAcceptFormat("JSON");
	   $request->setDomainName($args['DomainName']);
	   $request->setRR($args['RR']);
	   $request->setType($args['Type']);
	   $request->setValue($args['Value']);

	   $response = $client->getAcsResponse($request);
	   if($response->RecordId){
	      return true;
	   }
	}
	// 列出解析记录
	public function parsingList($access_key_id, $access_key_secret, $args){
	   $iClientProfile = DefaultProfile::getProfile("cn-hangzhou",$access_key_id,$access_key_secret);
	   $client = new DefaultAcsClient($iClientProfile);

	   $request = new Domain\DescribeDomainRecordsRequest();
	   $request->setMethod("GET");
	   $request->setAcceptFormat("JSON");
	   $request->setDomainName($args['DomainName']);
	   $request->setRRKeyWord($args['RRKeyWord']);

	   $response = $client->getAcsResponse($request);
	   return $response;
	}
}