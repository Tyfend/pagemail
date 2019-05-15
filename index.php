<?php 

	require_once 'config.php';
	require_once 'vendor/autoload.php';
	date_default_timezone_set('Europe/Paris') ;


// étape 1
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

// étape 2
function rand_pwd($nb_car = 10, $chaine ='azertyuiopqsdfghjklmwxcvbn0123456789') {
	$nb_lettre = strlen($chaine) -1;
	$generation = '';
	for($i=0; $i < $nb_car; $i++) {
		$pos = mt_rand(0, $nb_lettre);
		$car = $chaine[$pos];
		$generation .= $car;
	}
	return $generation;
}


if (session_status() != PHP_SESSION_ACTIVE){
	session_start();
}

// version 2 étape 2
	// function idcool($length = 12){
	//     $text="azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890";
	//     return substr(md5(str_shuffle(str_repeat($text, $length))), 0, $length);
	// }

// autre façon pour token
	//$text="azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890";
	//var_dump(substr(md5(str_shuffle(str_repeat($text, 12))), 0, 12));
	//var_dump(substr(uniqid(), 1,13));


//envoi du mail avec token 
if (isset($_SESSION["mail"]) && ($_SESSION["mail"]) == 'coucou'){
	$token = rand_pwd(12);
	// $token = idcool(); 
	fopen($token, 'w');

	sendMail(	"Coucou", 
				$contact,  
				["html" => '<h1>J\'ai réussi !</h1>'.'<p>Voici ton token aléatoire :</p>'.$token, 'text' => 'un texte']
			);

	unset($_SESSION["mail"]);
}else{
	$_SESSION["mail"] = 'coucou';
	echo 'Rafraîchir la page pour voir votre surprise';
}



