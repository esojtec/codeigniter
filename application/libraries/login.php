<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login 
{	
	var $CI = NULL;
	
	public function __Construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->database('default');
		$this->CI->config->load('permisos');
		$this->recordar();
	}
	
	function login($usuario = '',$password = '',$recordar = FALSE)
	{
		$query = $this->CI->db->select('u.id_usuario,u.nombre,u.usuario,tu.nombre AS tipo')
					->from('usuario `u`')
					->join('tipo_usuario `tu`','tu.id_tipo_usuario=u.id_tipo_usuario')
					->where(array('u.usuario'=>$usuario,'u.password'=>md5($password)))
				 	->get();
		
		if ($query->num_rows() == 1)
		{
			$row = $query->row();
		
			$login = array(
					'id_usuario'=>$row->id_usuario,
					'usuario'=>$row->usuario,
					'tipo'=>$row->tipo,
					'keyword'=>$this->CI->config->item($row->tipo),
					'nombre'=>$row->nombre
					);
			$this->CI->session->set_userdata($login);
			
			if($recordar)
			{
				$this->CI->load->library('encrypt');
			
				$usuario = $this->CI->encrypt->encode($usuario);
				$password = $this->CI->encrypt->encode($password);
				$expiracion = 60*24*60*60;
				
				$this->CI->input->set_cookie('usuario',$usuario,$expiracion);
				$this->CI->input->set_cookie('password',$password,$expiracion);
			}
			
			return true;
		}
		
		return false;
	}
	
	function recordar()
	{
		$this->CI->load->library('encrypt');
		$usuario = $this->CI->encrypt->decode($this->CI->input->cookie('usuario'));
		$password = $this->CI->encrypt->decode($this->CI->input->cookie('password'));
		
		return ($this->login($usuario,md5($password)))? true : $this->CI->session->sess_destroy();
	}
	
	function cerrar($no_recordar = FALSE)
	{	
		if($no_recordar == TRUE)
		{
			$expiracion = 0;
			$this->CI->input->set_cookie('usuario','',$expiracion);
			$this->CI->input->set_cookie('password','',$expiracion);
		}
		
		return ($this->CI->session->sess_destroy())? true : false;	
	}
}
?>