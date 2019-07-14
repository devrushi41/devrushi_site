<?php
$name = test_input($_POST['name']);
$email = test_input($_POST['email']);
$phone = test_input($_POST['phone']);
$msg = test_input($_POST['msg']) ;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$ip = getUserIpAddr();
//echo $ip;
if (!empty($name) && !empty($email) && !empty($msg) && !empty($phone)) {
    include('../config/config.php');
    //database name
    //create connection
	
  	if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z ]*$/",$name) || !preg_match("/^(?:(?:\+|0{0,2})91(\s*[\ -]\s*)?|[0]?)?[6789]\d{9}|(\d[ -]?){10}\d$/",$phone) ) {
        echo "Invalid name or email or phone number format"; 
    }else{
  
        if (mysqli_connect_error()) {
            die('Connect Error(' . mysqli_connect_errno() . ')' . mysqli_connect_error());
        } else {
          	$mymail = 'no-replay@devrushi.com';
            $to = 'devrushi41@gmail.com'; // Receiver Email ID, Replace with your email ID
            $subject = 'Form Submission';
            $ourmessage = "Name :" . $name . "\n" . "Phone No :" . $phone . "\n" . "Wrote the following :" . "\n" . $msg . "\n\n" . "From:" . $email;
          	$usermessage = "Thank you for contacting DevRushi We will be back to you Soon.."."\n"."Name :" . $name . "\n" . "Phone No :" . $phone . "\n" . "Your message :" . $msg . "\n\n" . "From email:" . $email;
            $headers = "From: " . $mymail;
            mail($to, $subject, $ourmessage, $headers);
          	mail($email , $subject, $usermessage, $headers);
            $INSERT = "INSERT Into devrushi (name, email, phone, msg,ip) values('$name', '$email', '$phone', '$msg','$ip')";  //table name

            if (mysqli_query($conn, $INSERT)) {
                echo "Thank you $name Your Message sent successful";
            } else {
                echo "There was a problem sending message";
                //echo "error";
            }
            /*$stmt = $conn->prepare($INSERT);
            $stmt->bind_param('ssss',$name,$email, $phone, $msg);
            $stmt->execute();
            echo "<script>  alert('Thank you $name Your Message sent successful'); window.location = 'index.html' </script>";
            $stmt->close();
            $conn->close();*/
            mysqli_close($conn);
        }
	}
} else {
    echo "All fields are required";
    //throw new Exception("All fields are required");

}
