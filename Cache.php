<?php 

	class Cache{
		
		const EXPIRE_IN = 7200;

		const CACHE_DIR = __DIR__;

		private $isDebug = False; 

		function __construct(){
			if ($this->isDebug) {
				echo "Current cache path: <br>";
				echo(self::CACHE_DIR);
			}	
		}

		public function getAccessToken(){
			$AccessTokenFile = self::CACHE_DIR.DIRECTORY_SEPARATOR.'token.txt';
			return $this->getData($AccessTokenFile);
		}	

		public function setAccessToken($accessToken){
			if ($accessToken) {
				$filename = self::CACHE_DIR.DIRECTORY_SEPARATOR.'token.txt';
				return $this->setData($filename,$accessToken);
			}
			return False;

		}

		public function getTicket(){
			$TicketFile = self::CACHE_DIR.DIRECTORY_SEPARATOR.'ticket.txt';
			return $this->getData($TicketFile);
		}

		public function setTicket($ticket){
			if ($ticket) {
				$filename = self::CACHE_DIR.DIRECTORY_SEPARATOR.'ticket.txt';
				$this->setData($filename,$ticket);
			}
		}

		private function getData($file){
			//$AccessTokenFile = self::CACHE_DIR.DIRECTORY_SEPARATOR.'token.txt';
			if (!file_exists($file)) {
				return False;
			}
			$modifyTime = filemtime($file);
			if (!$modifyTime) {
				return False;
			}
			//7000秒的时候就需要刷新了
			if ((time() - $modifyTime) >= 7000) {
				return False;
			}
			if ($this->isDebug) {
				echo "";
				echo(basename($file)." modifyTime :".date('Y-m-d H:i:s',$modifyTime));
			}
			$content = file_get_contents($file);
			return $content;
		}	

		private function setData($file,$data){
			return file_put_contents($file, $data);
		}


	}








?>