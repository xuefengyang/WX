<?php

	require_once 'WXApi.php';

	
	
	class WXConfig
	{
		
		function __construct(){
			
		}

		public static function configFactory($url){
			$api = new WXApi();
			$jsApiList = ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone'];
			$wxConfig = array('appId' => WXApi::WX_APP_ID,'timestamp' => time(),'nonceStr' => $api->getNonceStr(),
				'jsApiList' => $jsApiList);
			try {
				$wxConfig['signature'] = $api->getSignasture($wxConfig['nonceStr'],$wxConfig['timestamp'],$url);
			} catch (Exception $e) {	
				return False;
			}	
			return $wxConfig;


		}
	}	

?>