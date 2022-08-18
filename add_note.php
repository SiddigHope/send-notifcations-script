<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';
require_once 'config/FCMPushNotification.php'; 

$db = getDbInstance();

// Serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
    $data_to_db = array_filter($_POST);

    $teacher = filter_input(INPUT_GET, 'teacher', FILTER_SANITIZE_STRING);

    $db->where('MatriculeF', $teacher);
    // Get data to pre-populate the form.
    $teacherInfo = $db->getOne('personnels_token');

    // send notification to this user uning FCM
    $FCMPushNotification = new \BD\FCMPushNotification('AAAA77VehKo:APA91bGbzAzAZrhLgbn2fXGdfJxAgQwwrTSE9SSAEjwWT34NNurmC8yb4nQNz9jqRDPJEXjRG47u-gI2BbmCwWtkLDpg7f3kmote_jBT5-CQNM1G07isj933rMZlJCve6zXm-DXST5ne');
        $aPayload = array(
            'data' => array("test"=>123),
            'notification' => array(
                'title' => $data_to_db['title'],
                'body'=> $data_to_db['message'],
                'sound'=> 'default'
            )
        );
        $aOptions = array(
            'time_to_live' => 0 //means messages that can't be delivered immediately are discarded. 
        );

        $aResult = $FCMPushNotification->sendToDevice(
            $teacherInfo['token'],
            $aPayload,
            $aOptions // optional
        );


    // Insert user and timestamp
    $data_to_db['sent_to'] = $teacher;
    // $data_to_db['time'] = date('Y-m-d H:i:s');

    $db = getDbInstance();
    $last_id = $db->insert('private_note', $data_to_db);

    if ($last_id)
    {
        $_SESSION['success'] = 'تم ارسال الرسالة';
        // Redirect to the listing page
        header('Location: enrolled.php');
        // Important! Don't execute the rest put the exit/die.
    	exit();
    }
    else
    {
        echo 'Insert failed: ' . $db->getLastError();
        exit();
    }
}

// We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;
?>
<?php include BASE_PATH.'/includes/header.php'; ?>
<div id="page-wrapper" style="margin-right:20%;width:80%;">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"> ارسال اشعار </h2>
        </div>
    </div>
    <!-- Flash messages -->
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>
    <form class="form" action="" method="post" id="customer_form" enctype="multipart/form-data">
        <?php include BASE_PATH.'/forms/send_note.php'; ?>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
   $('#customer_form').validate({
       rules: {
            title: {
                required: true,
                minlength: 3
            },
            message: {
                required: true,
                minlength: 10
            },
        }
    });
});
</script>
<?php include BASE_PATH.'/includes/footer.php'; ?>
