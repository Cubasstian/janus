<?php

class helpers{
	public function getSession(){
		// Verificar que la sesión esté iniciada
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
		
		// Verificar que $_SESSION existe y tiene datos válidos
		if (!isset($_SESSION) || empty($_SESSION)) {
			return [
				'ejecuto' => true,
				'data' => []
			];
		}
		
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