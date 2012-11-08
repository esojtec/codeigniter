<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="http://localhost/codeigniter/public/css/fileuploader.css" rel="stylesheet" type="text/css">	
    <style>    	
		body {font-size:13px; font-family:arial, sans-serif; width:700px; margin:100px auto;}
		#file-uploader
		{
			width:400px;

			clear:both;
		}
		#mensaje
		{
			clear:both;
			margin-top:10px;
		}
		
		
    </style>	
</head>
<body >		
	<div id="file-uploader">
        <noscript>			
            <p>Por favor habilite Javascript para poder Subir archivos.</p>
                <!-- or put a simple form for upload here -->
        </noscript>
	</div>
    <div id="mensaje" > Subir Imagen</div>
    <script src="http://localhost/codeigniter/public/js/fileuploader.js" type="text/javascript"></script>
    <script>        
        function createUploader(){            
            var uploader = new qq.FileUploader({
				element: document.getElementById('file-uploader'),
                action: 'http://localhost/codeigniter/subir/subir2',
				encoding: "multipart",
				multiple:false,
				onComplete: function(id, fileName, response){
					if(response.success)
						document.getElementById('mensaje').innerHTML = '';
					else
						document.getElementById('mensaje').innerHTML = response.error;
				},
				showMessage: function(message){
					document.getElementById('mensaje').innerHTML = message;
				},
            });           
        }
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = createUploader;     
    </script>

</body>
</html>