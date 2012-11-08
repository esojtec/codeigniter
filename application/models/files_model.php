<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Files_model extends CI_Model{
    
    public function __construct(){
        parent::__Construct();
		
        $this->load->database();
        $this->load->library('XMLModelParser', 'xmlmodelparser');
        $this->load->helper('url');
        //$this->load->helper('_string');
    }

    /*
     * params: $idUsers = array de ids de usuarios
     *         $types = array de tipos de archivos o de requisitos
     * regresa un array de usuarios con nada uno de los archivos que tiene cargado para esa solicitud
     * si el usuario no tiene archivos para esa solicitud regresa -1 para ese usuario
     */
    public function getFilesByUsers($Xml) {		
		$SoapClient = new SoapClient(HOST_PRODUCTION_WS . "files_miempresa/servicios/wsdl.php?wsdl");
		
		$user = $this->session->userdata("user");	
		
		$Response = $SoapClient->getFilesByUsers($this->securityhandler->encrypt($Xml, $user["token"]), $this->securityhandler->encrypt($user["idUser"], $user["token"]), $user["token"]);
	
		if(isset($Response->XML)) {
			$Xml 	   = (string) $Response->XML;
			$Xml 	   = $this->xmlmodelparser->Base64($Xml, 3, "Decode");
			$SimpleXML = new SimpleXMLElement($Xml);

			$i = 0;
			foreach($SimpleXML->files->file as $User) {				
				$Return[$i]["id_usuario"] 	   	= (string) $User->id_usuario;
				$Return[$i]["id_archivo"] 	   	= (string) $User->id_archivo;
				$Return[$i]["id_tipo_archivo"] 	= (string) $User->id_tipo_archivo;
				$Return[$i]["mime_type"] 	   	= (string) $User->mime_type;
				$Return[$i]["tamano"] 		   	= (string) $User->tamano;
				$Return[$i]["descripcion"] 	   	= (string) $User->descripcion;
				$Return[$i]["descripcion"]      = Acentos($Return[$i]["descripcion"]);
				$Return[$i]["original"]		   	= (string) $User->original;
				$Return[$i]["fecha_creacion"]  	= (string) $User->fecha_creacion;
				$Return[$i]["medium"]		    = (string) $User->medium;							
				$Return[$i]["original_content"] = (string) $User->original_content;
				$Return[$i]["original_content"] = base64_decode($Return[$i]["original_content"]);
				$Return[$i]["medium_content"] 	= (string) $User->medium_content;
				$Return[$i]["medium_content"] 	= base64_decode($Return[$i]["medium_content"]);
				$i++;
			}
		} else {
			$Return = $Response->Error;
			
			if($Return == "Token Invalido") {
				header("location: ".base_url() . "index.php");
			} else {
				die($Return);
			}
		}
		
		return $Return;
    }
    
    /*
     * params: $idUser : id de la persona 
     *         $idSolicitude : solicitud a la que pertenece
     *         $idFile : tipo de requisito
     *  Nos regresa un archivo especifico de una persona, tomando encuenta la solicitud a la que corresponde.
     *
     */
    public function getFile($idUser, $idSolicitude, $idFile) {
		$SoapClient = new SoapClient(HOST_PRODUCTION_WS . "files_miempresa/servicios/wsdl.php?wsdl");

        $user = $this->session->userdata("user");
        
		$idUser 	  = $this->securityhandler->encrypt($idUser, 	   $user["token"]);
		$idSolicitude = $this->securityhandler->encrypt($idSolicitude, $user["token"]);
		$idFile		  = $this->securityhandler->encrypt($idFile, 	   $user["token"]);
		
		$Response	  = $SoapClient->getFile($idUser, $idSolicitude, $idFile, $this->securityhandler->encrypt($user["idUser"], $user["token"]), $user["token"]);
		
		if(isset($Response->XML)) {
			$Xml = (string) $Response->XML;
			$Xml = $this->xmlmodelparser->Base64($Xml, 3, "Decode");
			
			$SimpleXML = new SimpleXMLElement($Xml);

			$Return["id_archivo"] 		= (string) $SimpleXML->file->id_archivo;
			$Return["id_persona"] 		= (string) $SimpleXML->file->id_persona;
			$Return["id_tipo_archivo"] 	= (string) $SimpleXML->file->id_tipo_archivo;
			$Return["mime_type"] 		= (string) $SimpleXML->file->mime_type;
			$Return["tamano"] 			= (string) $SimpleXML->file->tamano;
			$Return["descripcion"] 		= Acentos((string) $SimpleXML->file->descripcion);
			$Return["fecha_creacion"] 	= (string) $SimpleXML->file->fecha_creacion;
			$Return["original"] 		= (string) $SimpleXML->file->original;
			$Return["medium"] 			= (string) $SimpleXML->file->medium;
			$Return["original_content"] = (string) $SimpleXML->file->original_content;
			$Return["medium_content"] 	= (string) $SimpleXML->file->medium_content;
		}
		
		return $Return;
    }
    
    /*
     * params: $idUser
     *         $idSolicitude
     *         $mimeType
     *         $idType
     *         $createDate
     *
     * Guardar el registro del archivo en la base de datos, validar que no este guardado ese archivo para esa solicitude
     */
    public function saveFile($idUser, $idSolicitude, $idType, $original, $medium, $mimeType, $fileSize, $responsable) { 
		$SoapClient = new SoapClient(HOST_PRODUCTION_WS . "files_miempresa/servicios/wsdl.php?wsdl", array("cache_wsdl" => WSDL_CACHE_NONE));
		
		$user = $this->session->userdata("user");
		
		$idUser 	  = $this->securityhandler->encrypt($idUser, 	   $user["token"]);
		$idSolicitude = $this->securityhandler->encrypt($idSolicitude, $user["token"]);
		$idType		  = $this->securityhandler->encrypt($idType, 	   $user["token"]);
		$original	  = $this->securityhandler->encrypt($original, 	   $user["token"]);
		$medium		  = $this->securityhandler->encrypt($medium, 	   $user["token"]);
		$mimeType	  = $this->securityhandler->encrypt($mimeType, 	   $user["token"]);
		$fileSize	  = $this->securityhandler->encrypt($fileSize, 	   $user["token"]);
		$responsable  = $this->securityhandler->encrypt($responsable,  $user["token"]);		
		
		$Response	  = $SoapClient->saveFile($idUser, $idSolicitude, $idType, $original, $medium, $mimeType, $fileSize, $responsable, $this->securityhandler->encrypt($user["idUser"], $user["token"]), $user["token"]);
		
		if(isset($Response->XML)) {
			return true;
		} else {
			return false;
		}
    }

    public function downloadFile($idFile){
        $user = $this->session->userdata('user');
        $idUserToken = $user["idUser"];
        $token = $user["token"];

        $data = array();

        try{
            $soapClient = new SoapClient(HOST_PRODUCTION_WS . "files/servicios/wsdl.php?wsdl", array("cache_wsdl" => WSDL_CACHE_NONE));

            $response = $soapClient->downloadFile(
                $this->securityhandler->encrypt($idFile, $token),
                $this->securityhandler->encrypt($idUserToken, $token),
                $token
            );

            //utils::pre($response, false);

//            $xml = $this->xmlmodelparser->Base64($response->XML, 3, "Decode");
//            $xml = new SimpleXMLElement($xml);
            
//            utils::pre($xml, false);

            $data = $response;

//            utils::pre($data, false);
//            $data->original_content = base64_decode( $data->original_content ) ;
//            utils::pre($data, false);
        } catch(SoapFault $fault){
            $data["error"] = "Error Web Service de Archivos\n".$fault->getTraceAsString();
        }

        return $data;
    }

    /**
     *
     * @param <type> $idSolicitud
     * @param <type> $idPersona
     * @param <type> $idTypeArchive
     * @param <type> $file
     * @param <type> $filename
     * @param <type> $mime
     * @param <type> $filesize
     * @return string 
     */
    public function uploadFile($idSolicitud, $idPersona, $idTypeArchive, $file, $filename, $mime, $filesize, $responsableSanitario = 0){
        
        $user = $this->session->userdata('user');
        $idUserToken = $user["idUser"];
        $token = $user["token"];
        try {
                        
            $soapClient = new SoapClient(HOST_PRODUCTION_WS . "files/servicios/wsdl.php?wsdl", array("cache_wsdl" => WSDL_CACHE_NONE));
        
//utils::pre(array($idPersona,
//                $idSolicitud,
//                $idTypeArchive,
//                $this->securityhandler->encrypt($idUserToken, $token),
//                $token));
            
            $upload = $soapClient->uploadFile(
                $this->securityhandler->encrypt($idPersona, $token),
                $this->securityhandler->encrypt($idSolicitud, $token),
                $this->securityhandler->encrypt($idTypeArchive, $token),
                $this->securityhandler->encrypt($file, $token),
                $this->securityhandler->encrypt($filename, $token),
                $this->securityhandler->encrypt($mime, $token),
                $this->securityhandler->encrypt($filesize, $token),
                $this->securityhandler->encrypt($responsableSanitario, $token),
                $this->securityhandler->encrypt($idUserToken, $token),
                $token
            );
                        
            if(isset($upload->Error)) {
                $data["Error"] = "Error al subir: ".$upload->Error;
            } else {
                $xml = $this->xmlmodelparser->Base64($upload->XML, 3, "Decode");
                $xml = new SimpleXMLElement($xml);
                //utils::pre($xml);
                
                // obtener el id del archivo
            
            //utils::pre($xml,TRUE);
                
                $data = $xml->idArchivo[0];
            }
        } catch(SoapFault $fault) {

            $data["Error"] = "Error Web Service de Archivos\n".$fault->getTraceAsString();
        }

        return $data;
    }
    
    public function updateFile($idFile, $file, $filename, $mime, $filesize){

        $user = $this->session->userdata('user');
        $idUserToken = $user["idUser"];
        $token = $user["token"];

        try{
            $soapClient = new SoapClient(HOST_PRODUCTION_WS . "files/servicios/wsdl.php?wsdl");
            
            $upload = $soapClient->updateFile(
                    $this->securityhandler->encrypt($idFile, $token),
                    $this->securityhandler->encrypt($file, $token),
                    $this->securityhandler->encrypt($filename, $token),
                    $this->securityhandler->encrypt($mime, $token),
                    $this->securityhandler->encrypt($filesize, $token),
                    $this->securityhandler->encrypt($idUserToken, $token),
                    $token
                    );

            if(isset($upload->Error)){
                $data["error"] = $upload->Error;
            } else {
                
                $xml = $this->xmlmodelparser->Base64($upload->XML, 3, "Decode");
                $xml = new SimpleXMLElement($xml);
                // obtener el id del archivo
                $data =  $xml->idArchivo[0];
            }

        } catch(SoapFault $fault){
            $data["error"] = "Error Web Service de Archivos\n".$fault->getTraceAsString();
        }
        
        return $data;
    }


    public function uploadFileModulo($idTramite, $idTipoTramite, $idTypeArchive, $file, $filename, $mime, $filesize){
        $user = $this->session->userdata('user');
        $idUserToken = $user["idUser"];
        $token = $user["token"];

        try{
            $soapClient = new SoapClient(HOST_PRODUCTION_WS . "files/servicios/wsdl.php?wsdl", array("cache_wsdl" => WSDL_CACHE_NONE));


            $upload = $soapClient->uploadFileModulo(
                    $this->securityhandler->encrypt($idTramite, $token),
                    $this->securityhandler->encrypt($idTipoTramite, $token),
                    $this->securityhandler->encrypt($idTypeArchive, $token),
                    $this->securityhandler->encrypt($file, $token),
                    $this->securityhandler->encrypt($filename, $token),
                    $this->securityhandler->encrypt($mime, $token),
                    $this->securityhandler->encrypt($filesize, $token),
                    $this->securityhandler->encrypt($idUserToken, $token),
                    $token
                    );


            if(isset($upload->Error)){
                $data["error"] = $upload->Error;
            } elseif($upload != NULL)  {

                $xml = $this->xmlmodelparser->Base64($upload->XML, 3, "Decode");
                $xml = new SimpleXMLElement($xml);
                // obtener el id del archivo
                $data =  $xml->idArchivo;
            } else {
				$data["error"] = "Error Web Service de Archivos\n";
			}

        } catch(SoapFault $fault){
            $data["error"] = "Error Web Service de Archivos\n".$fault->getTraceAsString();
        }

        return $data;
    }


    /*
     * Función que nos regresa el ultimo ID de la tabla de ca_Archivos
     */
    public function getLastID() {
		$query = $this->db->query("SELECT id_Archivo FROM ca_Archivos ORDER BY id_Archivo Desc LIMIT 1");
		
		$Data = $query->result_array();
		
		if($query->num_rows() > 0) {
			return ($Data[0]["id_Archivo"] + 1);
		} else {
			return 1;
		}
    }
    
    /*
     * params: $idUser
     *         $idFile
     * Función que permite al usuario reemplazar un archivo, osea que
     * Modificamos el campo fecha_Modificacion del registro del archivo, los otros datos siguen igual
     */
    public function replaceFile($idFile) {
		$SoapClient = new SoapClient(HOST_PRODUCTION_WS . "files_miempresa/servicios/wsdl.php?wsdl");
		
		$user = $this->session->userdata("user");
		
		$idFile = $this->securityhandler->encrypt($idFile, $user["token"]);
		
		$Response = $SoapClient->replaceFile($idFile, $this->securityhandler->encrypt($user["idUser"], $user["token"]), $user["token"]);
		
		if(isset($Response->XML)) {
			return true;
		} else {
			return (string) $Response->Error;
		}  
    }
    
    /*
     * params: $idUser
     *         $idType
     *         $idSolicitude
     * Función que permite al usuario reutilizar un documento para otra solicitud,
     * osea que agrega un nuevo registro en la tabla re_Arc_Per_Sol
     */
    public function reuseFile($idUser, $idType, $idSolicitude) {
		$SoapClient = new SoapClient(HOST_PRODUCTION_WS . "files_miempresa/servicios/wsdl.php?wsdl");
		
		$user = $this->session->userdata("user");
		
		$idUser 	  = $this->securityhandler->encrypt($idUser, $user["token"]);
		$idType 	  = $this->securityhandler->encrypt($idType, $user["token"]);
		$idSolicitude = $this->securityhandler->encrypt($idSolicitude, $user["token"]);
		
		$Response = $SoapClient->reuseFile($idUser, $idType, $idSolicitude, $this->securityhandler->encrypt($user["idUser"], $user["token"]), $user["token"]);
		
		if(isset($Response->XML)) {
			return true;
		} else {
			return (string) $Response->Error;
		} 
    }

    public function getFilename($id_Archivo){
        $user = $this->session->userdata('user');
        $idUserToken = $user["idUser"];
        $token = $user["token"];

        $data = array();

        try{
            $soapClient = new SoapClient(HOST_PRODUCTION_WS . "files/servicios/wsdl.php?wsdl", array("cache_wsdl" => WSDL_CACHE_NONE));

            $response = $soapClient->getName(
                $this->securityhandler->encrypt($id_Archivo, $token),
                $this->securityhandler->encrypt($idUserToken, $token),
                $token);

            if (isset($response->Error)){
                $result['error'] = (string) $response->Error;
            } else {
				
				$xml = $this->xmlmodelparser->Base64($response->XML, 3, "Decode");
                $xml = new SimpleXMLElement($xml);
				
				$data = $xml->filename;
            }

        } catch(SoapFault $fault){
            $data["error"] = "Error Web Service de Archivos\n".$fault->getTraceAsString();
        }
        
        return $data;
    }
    
    /**
     * 
     **/
    public function findAll(){
		return $this->db->get("ca_Archivos")->result_array();
	}
}
