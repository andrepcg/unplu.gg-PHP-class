<?php

define('GET',"GET");
define('POST',"POST");
define('PUT',"PUT");


class Unplugg {

	private $auth_token;
	private $meter_id;
	private $home_id;
	private $url;
	private $apitypes = array();
	private $timef = array();

	public function Unplugg($auth = 0,$mid = 0) {

        $this->auth_token = $auth;
        $this->meter_id = $mid;

			
		$this->url = 'http://unplu.gg/';
		
		$this->apitypes = array(1 => "challenges",
			2 => "consumptions",
			3 => "homes",
			4 => "meters",
			5 => "readings",
			6 => "simulations"
		);
			
		$this->timef = array(1 => "all_time",
			2 => "today",
			3 => "yesterday",
			4 => "last_week",
			5 => "last_month",
			6 => "last_semester"
		);
			
    }


	
	public function updateParams($auth,$mid,$hid) {
        $this->auth_token = $auth;
        $this->meter_id = $mid;
		$this->home_id = $hid;
    }
	
	
	private function unpluggCurlPost($api, $post_fields){
	echo $post_fields;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $this->url.$this->apitypes[$api].'.json');
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST, POST);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_HTTPHEADER, array('Host: unplu.gg', 'Content-Type: application/json','Content-Length: ' . strlen($post_fields)));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $post_fields);  
		
		

		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	private function unpluggCurlGet($api, $timeframe, $sent_id){
		
		$ch = curl_init();
		if($sent_id == 0)
			curl_setopt($ch,CURLOPT_URL, $this->url.$this->apitypes[$api].'.json'.(($this->apitypes[$api] === "consumptions") ? (($timeframe != 0) ? "/?period=".$this->timef[$timeframe] : "") : "").(($timeframe != 0) ? "&" : "?")."auth_token=".$this->auth_token);
		else
			curl_setopt($ch,CURLOPT_URL, $this->url.$this->apitypes[$api].'/'.$sent_id.'.json?auth_token='.$this->auth_token);
			
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST, GET);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;	
	}
	
	public function getConsumptions($timeframe = 1, $sent_id = 0){
		if($timeframe > 0 && $timeframe <= 6)
			return $this->unpluggCurlGet(2,$timeframe, $sent_id);
	}
	
	public function postConsumptions($watts, $datetime = 0){
		$fields = array(
			'auth_token' => $this->auth_token,
			'meter_id' => $this->meter_id,
			'consumption_value' => $watts
		);
		if($datetime != 0)
			$fields['date'] = $datetime;
			
		return $this->unpluggCurlPost(2,json_encode($fields));
	}

	
	public function getHomes($id = 0){
		return $this->unpluggCurlGet(3,0,$id);
	}
	
	public function postHomes($address, $ac, $heater, $people, $typo){
		if(isset($address, $ac, $heater, $people, $typo)){
			$fields = array(
				'address' => $address,
				'has_ac' => $ac,
				'has_heater' => $heater,
				'people' => $people,
				'typo' => $typo
			);
			return $this->unpluggCurlPost(3,json_encode($fields));
		}
		else
			return -1;
	}
	
	public function getMeters($id = 0){
		return $this->unpluggCurlGet(4,0,$id);
	}
	/*
	public function postMeters($tag, $time_zone, $device_type){
		if(isset($tag, $time_zone, $device_type)){
			$fields = array(
				'home_id' => $this->home_id,
				'tag' => $tag,
				'time_zone' => $time_zone,
				'device_type' => $device_type
			);
			return $this->unpluggCurlPost(4,json_encode($fields));
		}
		else
			return -1;
	}
	*/
	
	public function getReadings($id = 0){
		return $this->unpluggCurlGet(5,0,$id);
	}
	
	public function postReadings($value, $datetime){
		if(isset($value, $datetime)){
			$fields = array(
				'meter_id' => $this->meter_id,
				'value' => $value,
				'date' => $datetime
			);
			return $this->unpluggCurlPost(5,json_encode($fields));
		}
		else
			return -1;
	}
	
	public function getSimulations($id = 0){
		return $this->unpluggCurlGet(6,0,$id);
	}
	
	public function getChallenges($id = 0){
		return $this->unpluggCurlGet(1,0,$id);
	}
	
	

}




?>