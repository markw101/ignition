<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        authlogin.php
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
$userRequestIP = '192.168.1.1';
$userSecKey = GetUserProfile($userNumber, TRUE);
$userSecKey = bin2dna40($userSecKey['user_privkey']);

$transRecord = $timeStamp . $userNumber . $requestNumber . $userRequestIP . MINERNUM;
$signThis = hash('sha3-512', $transRecord, TRUE);

// ----- ROOT AMEND (CHILD) RECORD
$data = [
  'request_type' => 'ACCEPT',
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

echo "ACCEPT:" . $userRequestIP

exit();
