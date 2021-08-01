<?php
// XKCD Comic Project By Ashutosh Jangid
session_start();
if (($_GET['tok']) && ($_GET['mail'])) {
    if (($_GET['tok']) == $_SESSION['token']) {         // Token matching 


        $number = rand(1, 2500);
        $json = file_get_contents('http://xkcd.com/' . $number . '/info.0.json');
        $obj = json_decode($json, true);

        $email = $_GET['mail'];
        require 'PHPMailerAutoload.php'; //PHPmailer main file

        $mail = new PHPMailer;

        // $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com;';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'test@email.com';                 // SMTP username
        $mail->Password = 'Enter_password';                          // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('test@email.com', 'XKCD');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $timest  =  time();
        $mail->addAttachment($obj['img'], $timest . '.jpg');       // image as Attachment with timestamp naming.
        $mail->Subject = $obj['safe_title'];                    //safe_title as subject

        $mail->Body    = '<pre>' . $obj['transcript'] . '</pre><br><img src=' . "$obj[img]" . ' alt=' . "$obj[alt]" . ' width="500" height="600">'; //Body of the Mail includes Data and Image.

        if (!$mail->send()) {
            echo "<script>alert('Message could not be sent.');window.location.href = 'index.php';</script>"; //Page will redirect to home page after Error messege and Host will get error messege on mail
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo "<script>alert('Comic has been mailed to $email');window.location.href = 'index.php';</script>"; //Page will redirect to home page after success messege
        }
    }
}
