<?PHP

class androidNoti {

    //put your code here
    // constructor
    function __construct() {
        
    }

    function sendChatNotification($registration_ids, $message, $title = "Default",$type="offer",$card_id=false) {

        $registration_ids = array($registration_ids);

        $payload['notification_type'] = $type;
        $payload['card_id'] = $card_id;
        
        
        $res['data']['title'] = $title;
        $res['data']['is_background'] = TRUE;
        $res['data']['message'] = $message;
        $res['data']['image'] = "";
        $res['data']['payload'] = $payload;
        $res['data']['timestamp'] = date('Y-m-d G:i:s');


        //$message = array('data' => array('message' => $message, 'title' => "title this is"));
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $res,
        );

        //define('GOOGLE_API_KEY', 'AIzaSyBDBgMhqJ4zjJZmAY7hBNkN3Sy1tPpeKDc');
        // define('GOOGLE_API_KEY', 'AIzaSyClckOVunPMX2zwYYna8DPAy9bn4-5VdSI');// old working
        //define('GOOGLE_API_KEY', 'AIzaSyDdzGV3CScpTgOfQbNUk9EEdEm18eYqTXQ');// release key
        $GOOGLE_API_KEY = "AIzaSyAFTS5jutj1tLuu-zCUqRx_4CvQtIZwhtk";
        $headers = array(
            'Authorization:key=' . $GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        //echo json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if ($result === false)
            die('Curl failed ' . curl_error());

        curl_close($ch);
        $myfile = file_put_contents('notification_test.txt', $result . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

}

?>