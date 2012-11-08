<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailer 
{
	var $Mail = NULL;
	var $Host = 'ssl://smtp.gmail.com';
	var $SMTPAuth = true;
	var $SMTPSecure = 'ssl';
	var $IsHTML = true;
	var $Port = 465; 
	var $Username = 'lmartinreyes@gmail.com';
	var $Password = 'sistemas1';

	var $From = 'esojtec@gmail.com';
	var $FromName = 'HARDWARESOFT';

	var $Body = '';
	var $AltBody = '';
	var $AddAttachment = array();
	
	public function __Construct()
	{
		require_once('PHPMailer/class.phpmailer.php');
		$this->Mail = new PHPMailer();
	}
	
	public function SetBody($body)
	{
		$this->Body = $body;
		$this->AltBody = strip_tags($this->Body,'<br/>');
	}
	
	public function SetAltbody($altbody)
	{
		$this->AltBody = strip_tags($altbody,'<br/>');
	}
	
	public function SetAttachment($attachment)
	{
		$this->AddAttachment = $attachment;
	}
	
	public function Send_email($email,$nombre,$body = FALSE,$asunto = '')
	{
		if (! $body == FALSE)
			$this->SetBody($body);
			
		$this->Mail->IsSMTP();
		$this->Mail->SMTPAuth = $this->SMTPAuth;
		$this->Mail->Host = $this->Host;
		$this->Mail->Port = $this->Port;
		$this->Mail->Username = $this->Username;
		$this->Mail->Password = $this->Password;
		$this->Mail->From = $this->From;
		$this->Mail->FromName = $this->FromName;
		$this->Mail->SetFrom($this->From,$this->FromName);
		$this->Mail->AddReplyTo($this->From,$this->FromName);
		$this->Mail->Subject = $asunto;
		
		$this->Mail->Body = $this->Body;
		$this->Mail->AltBody = $this->AltBody;
		$this->Mail->AddAddress($email,$nombre);
		
		foreach ($this->AddAttachment as $nombre => $file)
		{
			$this->Mail->AddAttachment($file,$nombre);
		}
		
		$this->Mail->IsHTML($this->IsHTML);

		$i=5;
		$enviado = FALSE;
		 
		while (($enviado === FALSE) && ($i>1))
		{
			$enviado = $this->Mail->Send();
			--$i;
		}
		
		return $enviado;
	}
	
	public function Get_email($email,$nombre,$body = FALSE,$asunto = '')
	{		
		if (! $body == FALSE)
			$this->SetBody($body);
			
		$this->Mail->IsSMTP();
		$this->Mail->SMTPAuth = $this->SMTPAuth;
		$this->Mail->Host = $this->Host;
		$this->Mail->Port = $this->Port;
		$this->Mail->Username = $this->Username;
		$this->Mail->Password = $this->Password;
		$this->Mail->From = $email;
		$this->Mail->FromName = $nombre;
		$this->Mail->SetFrom($email,$nombre);
		$this->Mail->AddReplyTo($email,$nombre);
		$this->Mail->Subject = $asunto;
		
		$this->Mail->Body = $this->Body;
		$this->Mail->AltBody = $this->AltBody;
		$this->Mail->AddAddress($this->From,$this->FromName);
		
		foreach ($this->AddAttachment as $nombre => $file)
		{
			$this->Mail->AddAttachment($file,$nombre);
		}
		
		$this->Mail->IsHTML($this->IsHTML);

		$i=5;
		$enviado = FALSE;
		 
		while (($enviado === FALSE) && ($i>1))
		{
			$enviado = $this->Mail->Send();
			--$i;
		}
		
		return $enviado;
	}
	
}

?>