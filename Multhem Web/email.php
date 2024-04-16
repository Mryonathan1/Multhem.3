<?php

require 'PHPMailer/class.phpmailer.php';

function sendEmail($email, $msg){

    $mail = new PHPMailer(true); 

    $mail->IsSMTP();                           
    $mail->SMTPAuth   = false;                 
    $mail->Port       = 25;                    
    $mail->Host       = "localhost"; 
    $mail->Username   = "multhem-noreply@multhem.eu";   
    $mail->Password   = "Multhem@1234567890";            

    $mail->IsSendmail();  

    $mail->From       = "multhem-noreply@multhem.eu";
    $mail->FromName   = "Multhem-NoReply";

    $mail->AddAddress($email);
    $mail->Subject  = "Registration Confirmation - Multhem Public Info day";
	$mail->WordWrap   = 80; 

    $mail->MsgHTML($msg);
	$mail->IsHTML(true); 
    $mail->Send();
}

?>