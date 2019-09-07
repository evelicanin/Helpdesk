<?php
$to = "dvedad@ping.ba";		
$cc = array();
$cc[0] = "pejic.dalibor@gmail.com";		
//$cc[1] = "pdalibor@ping.ba";
//$cc[2] = "malmin@ping.ba";
$cc[1] = "dvedad@ping.ba";
$cc[2] = "pdalibor@ping.ba";
$cc[3] = "malmin@ping.ba";
$cc[4] = "hzejnil@ping.ba";
$cc[5] = "aadmir@ping.ba";
$cc[5] = "njnizama@ping.ba";
	
$subject = "test mail servera9";	
$message = "testiranje mail servera9";

$broj = count($cc);
// Additional headers
$headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
$headers .= 'From: Ping HelpDesk <helpdesk@noreply.com>' . "\r\n";
$headers .= 'Cc: ';
$i=0;
while($i < $broj){
    if($i == $broj - 1)
	  $headers .= $cc[$i];
	else
	  $headers .= $cc[$i].', ';
	$i++;
}
$headers .= "\r\n";
$headers.="Return-Path:<helpdesk@noreply.com>\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

$message = $message."\n\n---------------------------------------------------------------------------------\nOvo je automatski poslan email "
                     ."iz aplikacije PING HelpDesk! NE ODGOVARATI na email\n---------------------------------------------------------------------------------";

//$dodatni_param = "-f noreply@ping.com>";

// Mail it
if(mail($to, $subject, $message, $headers)){  
  echo "mail poslan";
}else{  
  echo " Email nije poslan!";

}
?>