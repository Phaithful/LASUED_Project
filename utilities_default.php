<?php
session_start();
$params = session_get_cookie_params();
setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"], true, true);

include_once("libs/dbfunctions.php");
include('classes/Users.php');
include('classes/Contact.php');
include('classes/Subscription.php');

// ✅ Validate 'op' exists
if (!isset($_REQUEST['op'])) {
    echo json_encode([
        "response_code" => 99,
        "response_message" => "Invalid request. Operation not specified."
    ]);
    exit;
}

$op = $_REQUEST['op']; // e.g., users.loginUser

// ✅ Validate op is in correct format
$operation = explode(".", $op);
if (count($operation) !== 2) {
    echo json_encode([
        "response_code" => 98,
        "response_message" => "Invalid operation format."
    ]);
    exit;
}

// ✅ Check class exists before instantiation
$class = $operation[0];
$method = $operation[1];

if (!class_exists($class)) {
    echo json_encode([
        "response_code" => 97,
        "response_message" => "Class '$class' not found."
    ]);
    exit;
}

$params = $_REQUEST;
$foo = new $class;
$response = call_user_func_array([$foo, $method], [$params]);

// ✅ If already JSON, don't double encode
if (is_array($response)) {
    echo json_encode($response);
} else {
    echo $response;
}
