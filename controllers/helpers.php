<?php

class helpers{
	public function getSession(){
		return [
			'ejecuto' => true,
			'data' => $_SESSION
		];
	}

	public function destroySession(){
		session_destroy();
		return [
			'ejecuto' => true,
			'data' => 'Ejecutado'
		];	
	}
}