<?php

class Subir extends CI_Controller
{
	public function __Construct()
	{
		parent::__Construct();
	}
	
	public function index()
	{		
		$this->load->view('ajax');		
	}
	
	public function subir2()
	{
		$allowedExtensions = array('pdf','jpg','txt');

		$sizeLimit = 4 * 1024 * 1024;
		
		$fileName = $_FILES['qqfile']['name'];
		$contenido = file_get_contents($_FILES["qqfile"]["tmp_name"], FILE_USE_INCLUDE_PATH);
		$fileSize = $_FILES['qqfile']['size'];
		
		$parametros = array(
			$fileName,
			$fileSize,
			$contenido
		);
		
		$qqFile = array(
			'allowedExtensions'=>$allowedExtensions,
			'sizeLimit'=>$sizeLimit,
			'webservice' => array(
				'clase'=>'imprimir',
				'metodo'=>'impresion',
				'parametros'=>$parametros,
			)
		);
//		array('allowedExtensions'=>$allowedExtensions,'sizeLimit'=>$sizeLimit)
		$this->load->library('qqFileUploader',$qqFile);

		$result = $this->qqfileuploader->handleUpload('./public/uploads/');
	
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES); 
	}

}

?>