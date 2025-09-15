<?php
require_once "documentos.php";

class archivos{
	public function cargarDocumento($datos){
		if(move_uploaded_file($_FILES['file']['tmp_name'],'documentos/'.$datos['id'].'.pdf')){
			return [
				'ejecuto' => true,
				'msg' => 'Carga correcta'
			];
		}else{
			return [
				'ejecuto' => false,
				'msg' => 'Error al cargar la imagen'
			];
		}
	}

	public function getDocumento($datos){
		$file = 'documentos/'.$datos['id'].'.pdf';
		// Verificar si el archivo existe
		if(file_exists($file)) {
			// Lee el archivo en formato binario
    		$fileContent = file_get_contents($file);    
    		// Codifica el contenido en base64 para enviarlo en formato JSON
    		$base64File = base64_encode($fileContent);    
    		// Enviar la respuesta JSON con el archivo codificado
    		header('Content-Type: application/json');
    		return [
    			'ejecuto' => true,
    			'file' => $base64File
    		];
		}else{
    		return [
				'ejecuto' => false,
				'mensajeError' => 'El archivo no existe'
			];
		}
	}

	public function existDocumento($datos){
		$file = 'documentos/'.$datos['id'].'.pdf';
		if(file_exists($file)) {
			return [
				'ejecuto' => true,
				'mensaje' => true
			];
		}else{
			return [
				'ejecuto' => false,
				'mensajeError' => 'El archivo no existe'
			];
		}
	}
}