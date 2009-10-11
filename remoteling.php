<?php
class RemotelingException extends Exception { }
	
class Remoteling {

	function __construct($api_key) {
		$this->host = 'http://127.0.0.1:3000/';
		$this->api_key = $api_key;
	}
	
	public function all_keys() {
	}
	
	public function set($key,$val) {
		$ch = $this->new_curl();
		$url = $this->host.'store/'.$key;
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $val);
		curl_setopt($ch, CURLOPT_URL, $url);
		
		return $this->run_curl($ch);
	}
	
	public function get($key) {
		$ch = $this->new_curl();
		$url = $this->host.'store/'.$key;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		return $this->run_curl($ch);
	}
	
	public function push($queue, $val) {
		$ch = $this->new_curl();
		
		$url = $this->host.'queue/'.$queue;
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $val);
		curl_setopt($ch, CURLOPT_URL, $url);
		
	  return $this->run_curl($ch);
	}
	
	public function pop($queue) {
		$ch = $this->new_curl();
		$url = $this->host.'queue/'.$queue;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		return $this->run_curl($ch);
	}
	
	public function run_serialized($code, $vars) {
		$ch = $this->new_curl();
		
		$url = $this->host.'process/';
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'process[code]='.urlencode($code).'&process[variables]='.$vars);
		curl_setopt($ch, CURLOPT_URL, $url);
		
	  return $this->run_curl($ch);
	}
	
	public function run($proc_name, $vars) {
		$ch = $this->new_curl();
		$url = $this->host.'process/'.$proc_name;
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		curl_setopt($ch, CURLOPT_URL, $url);
		
	  return $this->run_curl($ch);	
	}
	
	
	private function new_curl() {
		$ch = curl_init();
#		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':foo');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		return $ch;
	}
	
	private function run_curl($ch) {
		$http_result = curl_exec($ch);
		$error = curl_errno($ch);
		$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($error) {
			switch($error) {
				case 7:
					throw new RemotelingException('Unable to connect to Remoteling server at '.$this->host);
				default:
					throw new RemotelingException(curl_error($ch));
			}
		}
		
		return $http_result;
	}
}

?>