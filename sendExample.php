<?php 

require 'FCMPushNotification.php'; 
	
	$mysqli = new mysqli("localhost", "root", "ertyerty2020","grh_personnel");
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	    exit();
	}else{
	    // echo "connected";
	}
	$mysqli->set_charset('utf8');
	// $_POST['teacherId'] = '1197318010139900';

	if (isset($_POST['title'])) {

		$title = $_POST['title'];
		$message = $_POST['message'];
		
		$FCMPushNotification = new \BD\FCMPushNotification('AAAA77VehKo:APA91bGbzAzAZrhLgbn2fXGdfJxAgQwwrTSE9SSAEjwWT34NNurmC8yb4nQNz9jqRDPJEXjRG47u-gI2BbmCwWtkLDpg7f3kmote_jBT5-CQNM1G07isj933rMZlJCve6zXm-DXST5ne');

		$sDeviceToken = [];
		
		$tokens=array();

		$sql = "SELECT * FROM personnels_token WHERE token != ''";
 
		$result = $mysqli->query($sql);
		 
		if ($result->num_rows >0) {

		while ($row = $result->fetch_array()) {
				array_push($sDeviceToken,$row['token']);
			}
		}

		$aPayload = array(
			'data' => array("test"=>123),
			'notification' => array(
				'title' => $title,
				'body'=> $message,
				'sound'=> 'default'
			)
		);
		
		$aOptions = array(
			'time_to_live' => 0 //means messages that can't be delivered immediately are discarded. 
		);

		$aResult = $FCMPushNotification->sendToDevices(
			$sDeviceToken,
			$aPayload,
			$aOptions // optional
		);

		if ($aResult->success>0) {
			echo json_encode([
				'message' => 'sent',
			]);
		}else{
			echo json_encode([
				'message' => 'not sent',
			]);
		}
	}

?>