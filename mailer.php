<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        //your site secret key
        $secret = '6LcM7RgUAAAAAEyiUWYMOoZ_Z3_iTjnF6ZgQtVsC';

        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if ($responseData->success) {
            // Get the form fields and remove whitespace.
            $firstname = strip_tags(trim($_POST["FirstName"]));
            $firstname = str_replace(array("\r","\n"),array(" "," "),$firstname);
            $lastname = strip_tags(trim($_POST["LastName"]));
            $lastname = str_replace(array("\r","\n"),array(" "," "),$lastname);
            $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
            $phone = strip_tags(trim($_POST["Phone"]));
            $gender = strip_tags(trim($_POST["Gender"]));
            $address = strip_tags(trim($_POST["Address"]));
            $country = strip_tags(trim($_POST["Country"]));
            $dateofbirth = strip_tags(trim($_POST["DateofBirth"]));
            $modeofstudy = strip_tags(trim($_POST["ModeofStudy"]));
            $intake = strip_tags(trim($_POST["Intake"]));
            $course = strip_tags(trim($_POST["Course"]));
            $learncuz = strip_tags(trim($_POST["LearnCuz"]));

            // Check that data was sent to the mailer.
            if ( empty($firstname) OR empty($lastname) OR empty($phone) OR empty($gender) OR empty($address) OR empty($country) OR empty($dateofbirth) OR empty($modeofstudy) OR empty($intake) OR empty($course) OR empty($learncuz) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Set a 400 (bad request) response code and exit.
                // http_response_code(400);
                echo "Oops! There was a problem with your submission. Please complete the form and try again.";
                exit;
            }

            // update to db
            $con = mysql_connect("localhost","salat_cuz","CasDknf!2#53892");
            if (!$con) {
                die('Could not connect: ' . mysql_error());
            }

            mysql_select_db("salat_cuz", $con);
            
            $id = NULL;
            $date_received = date('Y-m-d H:m:s');

            mysql_query("INSERT INTO `cav_request_info`(`id`, `firstname`, `lastname`, `email`, `phone`, `gender`, `address`, `country`, `dateofbirth`, `modeofstudy`, `intake`, `course`, `learncuz`, `date_received`) VALUES 
    ('','" . $firstname . "','" . $lastname . "','" . $email . "','" . $phone . "','" . $gender . "','" . $address . "','" . $country . "','" . $dateofbirth . "','" . $modeofstudy . "','" . $intake . "','" . $course . "','" . $learncuz . "',NOW());
    ");
            mysql_close($con);

            

            echo "Thank You! Your application has been received.";
            // Set the recipient email address.
            // FIXME: Update this to your desired email address.
            $recipient = "ads@right-here.com";

            // Set the email subject.
            $subject = "Request Info";

            // Build the email content.
            $email_content = "First Name: $firstname\r\n";
            $email_content = "Name: $firstname $lastname\r\n";
            $email_content .= "Email: $email\r\n";
            $email_content .= "Phone: $phone\r\n";
            $email_content .= "Gender: $gender\n";
            $email_content .= "Address: $address\n";
            $email_content .= "Country: $country\n";
            $email_content .= "DateofBirth: $dateofbirth\n";
            $email_content .= "ModeofStudy: $modeofstudy\n";
            $email_content .= "Intake: $intake\n";
            $email_content .= "Course: $course\n";
            $email_content .= "Learn about Cuz From: $learncuz\n";

            // Build the email headers.
            $email_headers = "From: $firstname $lastname <$email>" . "\r\n";
            $email_headers .= "Bcc: samson.okitwi@right-here.com, danstan.ochieng@right-here.com, johnson.gitonga@right-here.com" . "\r\n";

            mail($recipient, $subject, $email_content, $email_headers);
            
            // Send the email.
            /*if (mail($recipient, $subject, $email_content, $email_headers)) {
                // Set a 200 (okay) response code.
                // http_response_code(200);
                echo "Thank You! Your message has been sent.";
            } else {
                // Set a 500 (internal server error) response code.
                // http_response_code(500);
                echo "Oops! Something went wrong and we couldn't send your message.";
            }*/
             header('Location: http://cavendishza.org/cavendishza-registration/thankyou.html');
        } else {
            echo "Please fill all the fields correctly.." ;
        }
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    // http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>