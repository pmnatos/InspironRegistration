<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);

if (isset($_POST['ticket_no']) && isset($_POST['name']) && isset($_POST['phone_no']) && isset($_POST['email']) && isset($_POST['college']) && isset($_POST['event'])) {
 
    // receiving the post params
    $ticketno = $_POST['ticket_no'];
    $name = $_POST['name'];
    $phno = $_POST['phone_no'];
    $email = $_POST['email'];
    $college = $_POST['college'];
    $event = $_POST['event'];
    $error = FALSE;

    if ($db->validateTicketno($ticketno)) {
        // validate participant's Ticket Number.
        $response["error"] = TRUE;
        $response["error_msg"] = "Only Numbers allowed in Ticket Number.";
        echo json_encode($response);
        $error = TRUE;
    }

    if ($db->validateName($name)) {
        // validate participant's name.
        $response["error"] = TRUE;
        $response["error_msg"] = "Only letters and white space allowed in Name.";
        echo json_encode($response);
        $error = TRUE;
    }

    if ($db->validatePhoneno($phno)) {
        // validate participant's Phone Number.
        $response["error"] = TRUE;
        $response["error_msg"] = "Invalid Phone Number Format.";
        echo json_encode($response);
        $error = TRUE;
    }
	
    if ($db->validateEmail($email)) {
        // validate participant's Email ID.
        $response["error"] = TRUE;
        $response["error_msg"] = "Invalid Email ID Format.";
        echo json_encode($response);
        $error = TRUE;
    }
    
    if ($db->validateName($college)) {
        // validate participant's college.
        $response["error"] = TRUE;
        $response["error_msg"] = "Only letters and white space allowed in College Name.";
        echo json_encode($response);
        $error = TRUE;
    }

    if ($db->validateName($event)) {
        // validate participant's event.
        $response["error"] = TRUE;
        $response["error_msg"] = "Only letters and white space allowed in Event Name.";
        echo json_encode($response);
        $error = TRUE;
    }

    if(!$error){
    // check if participant is already existed with the same ticket number
    if ($db->isUserExisted($ticketno)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with Ticket number :" . $ticketno;
        echo json_encode($response);
    } else {
        // create a new participant
        $user = $db->storeUser($ticketno, $name, $phno, $email, $college, $event);
        if ($user) {
            // participant stored successfully
            $response["error"] = FALSE;
	    $response["success_msg"] = "Participant : ". $name ." successfully registered.";
            echo json_encode($response);
            
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!Please try Re-registering!!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (Ticket No, Name, Phone No., Email , College or Event) is missing!!";
    echo json_encode($response);
}
}
?>