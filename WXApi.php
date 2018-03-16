<?php
	
	require_once 'Cache.php';

	
	class WXApi{
		
		const WX_APP_ID = '';
		const WX_SECRET = '';	

		public $cache = '';

		function __construct(){
			$this->cache = new Cache();
		}	

		public function getToken(){
			$token = $this->cache->getAccessToken();
			if (empty($token)) {
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::WX_APP_ID."&secret=".self::WX_SECRET;
				$result = json_decode($this->getRemoteData($url),true);
				if ($result['access_token']) {
					$this->cache->setAccessToken($result['access_token']);
					return $result['access_token'];
				}

				return False;
			}
			return $token;
		}

		/**
		 *  
		 * 获取微信凭据
		 */
		public function getTicket(){
			$ticket = $this->cache->getTicket();
			if (empty($ticket)) {
				$token = $this->getToken();
				if (empty($token)) {
					return False;
				}
				$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi";
				$result = json_decode($this->getRemoteData($url),true);
				if ($result['ticket']) {
					$this->cache->setTicket($result['ticket']);
					return $result['ticket'];
				}
				return False;
			}
			return False;
		}

		public function getRemoteData($url){
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	            curl_setopt($curl, CURLOPT_URL, $url);
	            $res = curl_exec($curl);
	            curl_close($curl);
	            return $res;
	        //return file_get_contents($url);
		}

		public function getNonceStr($length = 16){
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	        $nonceStr = "";
	        for ($i = 0; $i < $length; $i++) {
	            $nonceStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	        }
	        return $nonceStr;
		}


		public function getSignasture($noncestr,$timestamp,$url){
			$ticket = $this->getTicket();
			if (empty($ticket)) {
				throw new Exception("Error Processing Request", 1);
			}	
			$string = "jsapi_ticket=".$ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url;
			return sha1($string);
		}



	}

?>
