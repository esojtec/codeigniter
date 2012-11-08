<?php

/*
// PROTOTIPO DE ARRAY QUE SE DEBE PASAR AL CONSTRUCTOR

$array = array(
	'allowedExtensions'=>$allowedExtensions,
	'sizeLimit'=>$sizeLimit,
	'webservice'=>array(
		'clase'=>$clase,
		'metodo'=>$metodo,
		'parametros'=>$params //en array
	)
);

*/

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;
    
    function __construct($params = array()){

        $allowedExtensions = array_map("strtolower", $params['allowedExtensions']);
        $this->allowedExtensions = (isset($allowedExtensions))? $allowedExtensions : $this->allowedExtensions;        
        $this->sizeLimit = (isset($params['sizeLimit']))? $params['sizeLimit'] : $this->sizeLimit;
        $this->checkServerSettings();    
        
        if (isset($params['webservice'])) {
            $this->file = new SaveFtp($params['webservice']);
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'Incremente post_max_size y upload_max_filesize a $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload(){
    
        if (!$this->file){
            return array('error' => 'No se subio el archivo.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'Archivo esta vacio');
        }
        
        if ($size > $this->sizeLimit) {
            $sizeMax = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            return array('error' => 'El tamaño maximo del archivo debe ser de '.$sizeMax);
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'El archivo tiene una extensión invalida, debe ser alguna de las siguientes: '. $these . '.');
        }
        
        if ($data = $this->file->save()){
                if(! isset($data['error'])){
                        return array('success'=>true,'filename'=>$data);
                }else{
                        return $data;
                }
			
        } else {
            return array('error'=> 'No se pudo guardar el archivo.' .
                'La subida fue cancelada, o error de servidor encontrado');
        }
        
    }    
}

class SaveFtp
{
	private $CI = NULL;
	private $clase = '';
	private $metodo = '';
	private $parametros = array();
	
	public function __Construct($params = array())
	{
		//Instanciamos el objeto de codeigniter para poder utilizar las librerias propias del framework
		$this->CI =& get_instance();
		
		if(isset($params['clase']) || isset($params['metodo']) || isset($params['parametros']))
		{
			$this->clase = $params['clase']; //Definimos el modelo que se llama localizado en la carpeta Models
			$this->metodo = $params['metodo']; //Definimos el metodo a llamar dentro la clase
			$this->parametros = $params['parametros']; //Definimos los parametros que recibe el metodo
		}
		else
		{
			die('Se debe definir el Modelo,Metodo y Parametros');
		}
	}
	
	function save()
	{
		$modelo = $this->clase;
		$metodo = $this->metodo;
		$parametros = $this->parametros;
		
		$this->CI->load->Model($modelo);
		if($data = call_user_func_array(array($this->CI->$modelo,"$metodo"),$parametros)){
			if(is_array($data)){
				$data = array_change_key_case ($data);
			}
			return $data;
		}else{
			return false;
		}
	}
	
	function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}


/*
// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('uploads/');
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
*/