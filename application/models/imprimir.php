<?php

class Imprimir extends CI_Model
{
	public function impresion($num1,$num2,$num3)
	{
		echo $num1.'<br>';
		echo $num2.'<br>';
		echo $num3.'<br>';
	}
	public function cadena()
	{
		echo 'hola';
	}
}

?>