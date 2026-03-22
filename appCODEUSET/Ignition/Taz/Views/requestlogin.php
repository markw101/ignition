<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        requestlogin.php
    VERSION:     See BaseController.php
    DESCRIPTION: 
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

// ----- open database and write login request
//

// ----- create model to add a new record
$database = DBPREFIX . 'taz';
$allowedFields = GetFieldNames('serverlogins', $database);
$modelLogins = new \Ignition\Base\BaseModel('serverlogins', $allowedFields, $database);

$timeStamp = GetDNATime();
$requestNumber = dec2dna40($timeStamp) . bin2dna40(random_bytes(5));
$userNumber = GetUserEN();
$userRequestIP = $_SERVER['REMOTE_ADDR'];
$userSecKey = GetUserProfile($userNumber, TRUE);
$userSecKey = bin2dna40($userSecKey['user_privkey']);

$transRecord = $timeStamp . $userNumber . $requestNumber . $userRequestIP . MINERNUM;
$signThis = hash('sha3-512', $transRecord, TRUE);

// ----- ROOT AMEND (CHILD) RECORD
$data = [
  'request_type' => 'OPEN',
  'request_number' => $requestNumber,
  'request_user' => dna402bin($userNumber),
  'request_dna_ts' => $timeStamp,
  'request_miner' => MINERNUM,
  'request_record' => $transRecord,
  'request_sig' => dna402bin(\dna\Library\CryptoUtil::DetachedSign($signThis, $userSecKey)),
  'request_ip' => $userRequestIP
];

$modelLogins->insert($data);
$loginID = $modelLogins->getInsertID(); 

e('Login record ID', $loginID);
halt("Access request sent to openlogin daemon.");

e('Sending email to admin');

// AI SOLUTION
//$mail = new \Ignition\Taz\Views\PHPMailer(true); // Passing `true` enables exceptions
$pop = new \Ignition\Taz\Views\POP3();
$pop->connect('poppro.zoho.com', 995);
$pop->login('mark@solarsnap.com', 'fhytmKF4zN5C');

 // Recipients
$pop->setFrom('mark@solarsnap.com', 'Mark Williamson');
$pop->addAddress('legal@solarsnap.com', 'Legal department');

// Content
$pop->isHTML(true);
$pop->Subject = 'Test Email';
$pop->Body = '<h1>Hello!</h1><p>This is a test email sent using PHPMailer.</p>';
$pop->AltBody = 'Hello! This is a test email sent using PHPMailer.';

//Connect to the POP3 server
try {
    $mail->getMailMIME();
    echo 'Connected to POP3 server';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

$pop->send();
try {
echo 'Message has been sent';
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$pop->ErrorInfo}";
}

/*
$mail = new Ignition\Taz\Views\PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.yourserver.com';
$mail->SMTPAuth = true;
$mail->Username = 'username';
$mail->Password = 'password';
$mail->setFrom('from@example.com', 'Mailer');
$mail->addAddress('recipient@example.com');
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the body in plain text for non-HTML mail clients';
$mail->send();
*/

/*
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use \Ignition\Taz\Views\PHPMailer;
use \Ignition\Taz\Views\Exception;

//Load Composer's autoloader
//require 'vendor/autoload.php';

$mail = new \Ignition\Taz\Views\PHPMailer(true); // Passing `true` enables exceptions
$modelLogins = new \Ignition\Base\BaseModel('serverlogins', $allowedFields, 'taz');

//Server settings
$mail->Host = 'poppro.zoho.com';
$mail->Port = 995;
$mail->POP3Auth = true;
$mail->Username = 'mark@solarsnap.com';
$mail->Password = 'fhytmKF4zN5C';

//Connect to the POP3 server
try {
    $mail->getMailMIME();
    echo 'Connected to POP3 server';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

 // Recipients
$mail->setFrom('mark@solarsnap.com', 'Mark Williamson');
$mail->addAddress('legal@solarsnap.com', 'Legal department');

// Content
$mail->isHTML(true);
$mail->Subject = 'Test Email';
$mail->Body = '<h1>Hello!</h1><p>This is a test email sent using PHPMailer.</p>';
$mail->AltBody = 'Hello! This is a test email sent using PHPMailer.';

$mail->send();
try {
echo 'Message has been sent';
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
*/

halt('end taz login script');
