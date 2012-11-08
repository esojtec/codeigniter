<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_Validation extends CI_Form_Validation
{   
    function recaptcha_matches()
    {
        $this->CI->config->load('recaptcha');
        $public_key = $this->CI->config->item('recaptcha_public_key');
        $private_key = $this->CI->config->item('recaptcha_private_key');
        $response_field = $this->CI->input->post('recaptcha_response_field');
        $challenge_field = $this->CI->input->post('recaptcha_challenge_field');
        $response = recaptcha_check_answer($private_key,
                                           $_SERVER['REMOTE_ADDR'],
                                           $challenge_field,
                                           $response_field);
        if ($response->is_valid)
        {
            return TRUE;
        }
        else 
        {
            $this->CI->form_validation->recaptcha_error = $response->error;
            $this->CI->form_validation->set_message('recaptcha_matches', 'El %s es incorrecto. Por favor vuelva a intentarlo.');
            return FALSE;
        }
    }
	
	function alpha_spaces($str)
	{
		return ( preg_match("/^[a-z]+[a-z\s]*$/i", $str)) ? TRUE : FALSE;
	}
	
	function alpha_acentos($str)
	{
		return (preg_match("/^[a-záéíóúñ\s]+$/i", $str))? TRUE : FALSE;
	}
	
	function alpha_numeric_acentos($str)
	{
		return (preg_match("/^[a-záéíóúñ]+[0-9\s_]*$/i", $str))? TRUE : FALSE;
	}
	
	function alpha_numeric_spaces($str)
	{
		return ( preg_match("/^([a-z0-9])+([a-z0-9\s])*$/i", $str)) ? TRUE : FALSE;
	}
	
	function fecha($str)
	{
		$this->CI->load->helper('date');
		if(! stripos('/',$str))
		{
			$separar = explode('/',$str);
			if(count($separar) == 3)
			{
				$dias = $separar[0];
				$mes = $separar[1];
				$anio = $separar[2];
				
				if($dias <= days_in_month($mes,$anio) && ($mes <= 12 && $mes > 0) && ($anio > 0 && $anio < 2100))
				{
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
    
} 