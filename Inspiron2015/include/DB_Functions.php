<?php
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Storing new participant
     * returns user details
     */
    public function storeUser($ticketno, $name, $phno, $email, $college, $event) {
        date_default_timezone_set("Asia/Kolkata");
        $stmt = $this->conn->prepare("INSERT INTO participant_details(TICKET_NO, NAME, PHONE_NO, EMAIL_ID, COLLEGE, EVENT, REGISTRATION_DATE) VALUES(?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $ticketno, $name, $phno, $email, $college, $event);
        $result = $stmt->execute();
        $stmt->close();
 
        //check for successful store
        if ($result) {
            /* $stmt = $this->conn->prepare("SELECT * FROM participant_details WHERE TICKET_NO = ?");
            $stmt->bind_param("s", $ticketno);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close(); */
      
 	    $headers = "From: Placement Office UVCE<inspiron@campusuvce.in>\r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html;\r\n";
	    $sendto = "$email";
	    $subject = "Inspiron 15.0 - UVCE"; // Subject
	    $message ="Dear <strong>$name</strong><br>
		<p style=color:blue><i>Thank you for registering for $event in Insipron 15.0</i></p>
		Please note your Ticket Number is <strong> $ticketno</strong><br><br>
		As we get a little closer to the event date, we will be sending you complete details about the fest. In the meantime, we’d love it if you could SPREAD THE WORD about Inspiron 15.0 with your friends!<br><br>
		Thank you again for your registration. If you have any questions, please feel free to drop a mail to inspiron@campusuvce.in!<br><br>
		Regards,<br>
		Placement Team, UVCE";
	    mail($sendto, $subject, $message, $headers);

 
            return true;
        } else {
            return false;
        }
    }
 
    /**
     * Check participant is existed or not
     */
    public function isUserExisted($ticketno) {
        $stmt = $this->conn->prepare("SELECT TICKET_NO from participant_details WHERE TICKET_NO = ?");
 
        $stmt->bind_param("s", $ticketno);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // participant existed 
            $stmt->close();
            return true;
        } else {
            // participant not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Check participant's ticket validity
     */
    public function validateTicketno($ticketno) {
      if (!preg_match("/^[0-9]*$/",$ticketno)) {
      return true;
     }
     else {
       return false;
     }
   }

    /**
     * Check participant's email validity
     */
    public function validateEmail($email) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
     }
     else {
       return false;
     }
   }

   /**
     * Check participant's phone validity
       
     */
    public function validatePhoneno($phoneno) {
      if (!preg_match("/[7-9][0-9]{9}/",$phoneno)) {
         return true;
      }
      else {
       return false;
     }
   }

   /**
     * Check participant's name validity
     */
    public function validateName($name) {
	if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
  	   return true;
         }
        else {
       return false;
     }
   }
 
}
 
?>
