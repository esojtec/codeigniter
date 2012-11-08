<?php

class clase
{
	function __Construct()
	{
	}
	
	function pasar($color,$tamano,$precio)
	{
		echo $color.'<br/>';
		echo $tamano.'<br/>';
		echo $precio;
	}
	
	function pasar2($color,$tamano,$precio,$nuevo)
	{
		echo $color.'<br/>';
		echo $tamano.'<br/>';
		echo $precio.'<br/>';
		echo $nuevo;
	}
}

$obj = new clase();

$array1 = array(
	'color'=>'blanco',
	'tamano'=>'grande',
	'precio'=>1023
);

$array2 = array(
	'color'=>'negro',
	'tamano'=>'mediano',
	'precio'=>512,
	'nuevo'=>'sip'
);

//CODEIGNITER

//call_user_func_array(array($this->MODEL o $this->LIBRARY,"Nombre_Funcion"),$array);

call_user_func_array(array($obj,"pasar"),$array1);
call_user_func_array(array($obj,"pasar2"),$array2);

?>