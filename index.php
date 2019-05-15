<?php 

	require_once 'config.php';
	require_once 'vendor/autoload.php';



function sendMail($objet, $mailto, $msg, $cci = true)//:string
{
	require 'config.php';
	if(!is_array($mailto)){
		$mailto = [ $mailto ];
	}
	// Create the Transport
	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
	->setUsername($usermail)
	->setPassword($usermdp);
	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);
	// Create a message
	$message = (new Swift_Message($objet))
		->setFrom([$usermail]);
	if ($cci){
		$message->setBcc($mailto);
	}else{
		$message->setto($mailto);
	}
	if(is_array($msg) && array_key_exists("html", $msg) && array_key_exists("text", $msg))
	{
		$message->setBody($msg["html"], 'text/html');
		// Add alternative parts with addPart()
		$message->addPart($msg["text"], 'text/plain');
	}else if(is_array($msg) && array_key_exists("html", $msg) ){
		$message->setBody($msg["html"], 'text/html');
		$message->addPart($msg["html"], 'text/plain');
	}else if(is_array($msg) && array_key_exists("text", $msg)){
		$message->setBody($msg["text"], 'text/plain');
	}else if(is_array($msg)){
		die('erreur une clé n\'est pas bonne'); 
	}else{
		$message->setBody($msg, 'text/plain');
	}
	
	// Send the message
	return $mailer->send($message);
}

if (session_status() != PHP_SESSION_ACTIVE){
	session_start();
}



	if (isset($_SESSION["mail"]) && ($_SESSION["mail"]) == 'coucou'){
		$objet = "Coucou";
		$message = ["html" => '<h1>J\'ai réussi !</h1>', 'text' => 'un texte'];
		sendMail($objet, $contact, $message);
		unset($_SESSION["mail"]);
		
	}else{
		$_SESSION["mail"] = 'coucou';
		echo 'Rafraîchir la page pour voir votre surprise';
	}

	


