<?php session_start();
   require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
   // Include the PHPMailer classes
   // If these are located somewhere else, simply change the path.
   require_once( ROOT_PATH . "incs/phpMailer/class.phpmailer.php");
   require_once( ROOT_PATH . "incs/phpMailer/class.smtp.php");
   require_once( ROOT_PATH . "incs/phpMailer/language/phpmailer.lang-en.php");


   extract($_POST);
   // $name, $email, $subject, $message


   // mostly the same variables as before
   // ($to_name & $from_name are new, $headers was omitted) 
   $to_name = "mail to us - company name";
   $to = "boakyed9@gmail.com";
   // $subject = $subject;
   // $message = $message;
   $message = wordwrap($message,70);
   $from_name = $name;
   $from = $email;
   
   // PHPMailer's Object-oriented approach
   $mail = new PHPMailer();
   
   // Can use SMTP
   // comment out this section and it will use PHP mail() instead
   $mail->IsSMTP();

   $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
   $mail->SMTPAuth = true; // authentication enabled
   $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
   $mail->Host = "smtp.gmail.com";
   $mail->Port = 465; // or 587
   $mail->IsHTML(true);
   $mail->Username = "webmaster@gmail.com";
   $mail->Password = "password";
   
   // Could assign strings directly to these, I only used the 
   // former variables to illustrate how similar the two approaches are.
   $mail->FromName = $from_name;
   $mail->From     = $from;
   $mail->AddAddress($to, $to_name);
   $mail->Subject  = $subject;
   $mail->Body     = $message;
   
   $result = $mail->Send();
   
   if($result){
      $_SESSION['message'] = "Thank you for submitting this online form.\n";
   }else{
       $_SESSION['message'] = "Error! Mail could not be Sent.\n";
   }

   header ("Location: ./"); 
   exit; 