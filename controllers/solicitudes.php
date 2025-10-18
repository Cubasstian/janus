<?php
require_once "libs/baseCrud.php";
require_once "procesos.php";
require_once "solicitudesHistorico.php";
require_once "necesidades.php";

class solicitudes extends baseCrud{
	protected $tabla = 'solicitudes';

	// Resumen de solicitudes por estado (opcional filtrar por gerencia del usuario no admin)
	public function getResumenEstados($datos){
		$restrict = '';
		$types = '';
		$params = [];
		// Filtro explícito por gerencia (si viene gerenciaId y es numérico >0)
		if(isset($datos['gerenciaId']) && (int)$datos['gerenciaId']>0){
			$restrict .= ' AND ger.id = ?';
			$types .= 'i';
			$params[] = (int)$datos['gerenciaId'];
		}
		if(isset($datos['soloGerencia']) && (int)$datos['soloGerencia'] === 1){
			if(isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] !== 'Administrador'){
				$restrict .= ' AND ger.id = ?';
				$types .= 'i';
				$params[] = (int)$_SESSION['usuario']['gerencia'];
			}
		}
		if(!empty($datos['startDate']) && !empty($datos['endDate'])){
			$restrict .= ' AND DATE(sol.fecha_creacion) BETWEEN ? AND ?';
			$types .= 'ss';
			$params[] = $datos['startDate'];
			$params[] = $datos['endDate'];
		}
		$sql = "SELECT sol.estado, COUNT(1) AS cantidad
			FROM solicitudes sol INNER JOIN (procesos pro INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
			WHERE 1=1 $restrict
			GROUP BY sol.estado
			ORDER BY sol.estado";
		$db = new database();
		$res = $db->ejecutarPreparado($sql, $types, $params);
		// Fallback: si no hay datos y se solicita fallbackSimple=1 -> contar directo sobre solicitudes
		if($res['ejecuto'] && empty($res['data']) && !empty($datos['fallbackSimple'])){
			try{
				$db2 = new database();
				$sql2 = "SELECT estado, COUNT(1) cantidad FROM solicitudes WHERE 1=1";
				$types2=''; $params2=[];
				if(!empty($datos['startDate']) && !empty($datos['endDate'])){ $sql2.=' AND DATE(fecha_creacion) BETWEEN ? AND ?'; $types2='ss'; $params2=[$datos['startDate'],$datos['endDate']]; }
				$res2 = $db2->ejecutarPreparado($sql2,$types2,$params2);
				if($res2['ejecuto']){ $res = $res2; }
			}catch(\Exception $e){ /* ignore */ }
		}
		if(isset($datos['debug']) && (int)$datos['debug']===1){
			$res['debug'] = [ 'sql'=>$sql, 'types'=>$types, 'params'=>$params ];
		}
		return $res;
	}

	// KPIs para dashboard
	public function getKPIs($datos){
		$restrict=''; $types=''; $params=[]; $debugInfo=[];
		// Filtro explícito por gerencia (aceptado para cualquier rol; la lista de gerencias ya está filtrada en endpoint gerencias)
		if(isset($datos['gerenciaId']) && (int)$datos['gerenciaId']>0){
			$restrict .= ' AND ger.id = ?';
			$types .= 'i';
			$params[] = (int)$datos['gerenciaId'];
		}
		if(isset($datos['soloGerencia']) && (int)$datos['soloGerencia']===1){
			if(isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol']!=='Administrador'){
				$restrict.=' AND ger.id = ?'; $types.='i'; $params[]=(int)$_SESSION['usuario']['gerencia'];
			}
		}
		if(!empty($datos['startDate']) && !empty($datos['endDate'])){
			$restrict.=' AND DATE(sol.fecha_creacion) BETWEEN ? AND ?'; $types.='ss'; $params[]=$datos['startDate']; $params[]=$datos['endDate'];
		}
		$pv = isset($datos['porVencerDias']) ? (int)$datos['porVencerDias'] : 15; if($pv<=0 || $pv>120){ $pv=15; }
		$types.='i'; $params[]=$pv;
		$sql = "SELECT
			SUM(sol.estado = 1) AS pendientesUbicar,
			IFNULL(ROUND(AVG(DATEDIFF(NOW(), sol.fecha_creacion)),1),0) AS diasPromedio,
			SUM( (p.fecha_fin IS NOT NULL) AND (DATEDIFF(p.fecha_fin, NOW()) BETWEEN 0 AND ?) AND (sol.estado BETWEEN 1 AND 16) ) AS porVencerse,
			SUM(sol.estado BETWEEN 1 AND 16) AS enTramite,
			SUM(sol.estado >= 17) AS completados,
			COUNT(1) AS total
			FROM solicitudes sol
			INNER JOIN (procesos p INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON p.fk_necesidades = nec.id) ON sol.fk_procesos = p.id
			WHERE 1=1 $restrict";
		$db = new database();
		$res = $db->ejecutarPreparado($sql,$types,$params);
		if($res['ejecuto'] && empty($res['data']) && !empty($datos['fallbackSimple'])){
			try{
				$db2 = new database();
				$sql2 = "SELECT
				SUM(estado = 1) AS pendientesUbicar,
				IFNULL(ROUND(AVG(DATEDIFF(NOW(), fecha_creacion)),1),0) AS diasPromedio,
				0 AS porVencerse,
				SUM(estado BETWEEN 1 AND 16) AS enTramite,
				SUM(estado >= 17) AS completados,
				COUNT(1) AS total
				FROM solicitudes WHERE 1=1";
				$types2=''; $params2=[];
				if(!empty($datos['startDate']) && !empty($datos['endDate'])){ $sql2.=' AND DATE(fecha_creacion) BETWEEN ? AND ?'; $types2='ss'; $params2=[$datos['startDate'],$datos['endDate']]; }
				$res2 = $db2->ejecutarPreparado($sql2,$types2,$params2);
				if($res2['ejecuto']){ $res = $res2; }
			}catch(\Exception $e){ /* ignore */ }
		}
		if(isset($datos['debug']) && (int)$datos['debug']===1){
			$res['debug'] = [ 'sql'=>$sql, 'types'=>$types, 'params'=>$params ];
		}
		return $res;
	}

	// Historial para mini-sparklines (últimos N días de nuevas y completadas)
	public function getKPIHistory($datos){
		// Ajuste: cuando se filtra por gerencia el placeholder ? aparece en AMBAS subconsultas,
		// por lo que debemos duplicar el parámetro (antes solo se pasaba 1 y causaba mismatch).
		$dias = isset($datos['dias']) ? (int)$datos['dias'] : 14; if($dias<3 || $dias>60) $dias=14;
		$filtraGer = false; $gerId = null;
		if(isset($datos['soloGerencia']) && (int)$datos['soloGerencia']===1){
			if(isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol']!=='Administrador'){
				$filtraGer = true; $gerId = (int)$_SESSION['usuario']['gerencia'];
			}
		}
		$restrict = $filtraGer ? ' AND ger.id = ?' : '';
		$sql = "SELECT fecha, SUM(nuevas) nuevas, SUM(completadas) completadas FROM (
			SELECT DATE(sol.fecha_creacion) fecha, COUNT(1) nuevas, 0 completadas
			FROM solicitudes sol INNER JOIN (procesos pro INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
			WHERE sol.fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL ? DAY) $restrict
			GROUP BY DATE(sol.fecha_creacion)
			UNION ALL
			SELECT DATE(sol.fecha_modificacion) fecha, 0 nuevas, COUNT(1) completadas
			FROM solicitudes sol INNER JOIN (procesos pro INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
			WHERE sol.fecha_modificacion >= DATE_SUB(CURDATE(), INTERVAL ? DAY) AND sol.estado >= 17 $restrict
			GROUP BY DATE(sol.fecha_modificacion)
		) t GROUP BY fecha ORDER BY fecha";
		// Orden de placeholders cuando filtra gerencia: dias, ger, dias, ger  (sin filtro: dias, dias)
		$types = '';
		$params = [];
		$types .= 'i'; $params[] = $dias; // primera INTERVAL
		if($filtraGer){ $types .= 'i'; $params[] = $gerId; }
		$types .= 'i'; $params[] = $dias; // segunda INTERVAL
		if($filtraGer){ $types .= 'i'; $params[] = $gerId; }
		$db = new database();
		return $db->ejecutarPreparado($sql, $types, $params);
	}

	// Configuración dashboard (key/value). Crear tabla si no existe.
	private function ensureDashConfigTable(){
		try{
			$db = new database();
			$db->ejecutarConsulta("CREATE TABLE IF NOT EXISTS configuracion_dashboard (clave VARCHAR(50) PRIMARY KEY, valor VARCHAR(100) NULL)");
		}catch(\Exception $e){ /* ignore */ }
	}
	public function getDashConfig($datos){
		$this->ensureDashConfigTable();
		$db = new database();
		$res = $db->ejecutarConsulta('SELECT clave, valor FROM configuracion_dashboard');
		$defaults = [ 'threshold_info'=>7, 'threshold_warn'=>15, 'threshold_danger'=>30, 'porVencerDefault'=>15 ];
		if($res['ejecuto']){
			foreach($res['data'] as $row){ $defaults[$row['clave']] = is_numeric($row['valor'])? (float)$row['valor'] : $row['valor']; }
		}
		return ['ejecuto'=>true,'data'=>[$defaults]];
	}
	public function setDashConfig($datos){
		if(!isset($_SESSION['usuario']['rol']) || $_SESSION['usuario']['rol'] !== 'Administrador'){
			return ['ejecuto'=>false,'mensajeError'=>'No autorizado'];
		}
		$this->ensureDashConfigTable();
		$permitidas = ['threshold_info','threshold_warn','threshold_danger','porVencerDefault'];
		$db = new database();
		foreach($permitidas as $k){
			if(isset($datos[$k])){
				$db->ejecutarPreparado('REPLACE INTO configuracion_dashboard (clave, valor) VALUES(?,?)','ss',[$k, (string)$datos[$k]]);
			}
		}
		return ['ejecuto'=>true];
	}

	// Export ZIP empaquetando todos los datasets principales
	public function exportDashboardZip($datos){
		$files = [];
		// Reutilizar endpoints existentes
		$r1 = $this->getResumenEstados($datos); if($r1['ejecuto']){ $csv="estado,cantidad\n"; foreach($r1['data'] as $row){ $csv.=$row['estado'].",".$row['cantidad']."\n"; } $files['resumen_estados.csv']=$csv; }
		$r2 = $this->getKPIs($datos); if($r2['ejecuto'] && !empty($r2['data'])){ $k=$r2['data'][0]; $csv='metric,valor\n'; foreach($k as $kk=>$vv){ $csv.=$kk.','.$vv."\n"; } $files['kpis.csv']=$csv; }
		$r3 = $this->getTrendSemanal($datos); if($r3['ejecuto']){ $csv='semana,nuevas,completadas\n'; foreach($r3['data'] as $row){ $csv.=$row['semana'].",".$row['nuevas'].",".$row['completadas']."\n"; } $files['trend.csv']=$csv; }
		$r4 = $this->getKPIHistory($datos); if($r4['ejecuto']){ $csv='fecha,nuevas,completadas\n'; foreach($r4['data'] as $row){ $csv.=$row['fecha'].",".$row['nuevas'].",".$row['completadas']."\n"; } $files['kpi_history.csv']=$csv; }
		if(isset($datos['estado']) && (int)$datos['estado']>0){ $r5 = $this->getSolicitudesDashboard($datos); if($r5['ejecuto']){ $csv='id,estado,fecha_creacion,dias_desde_cambio,gerencia,dependencia,unidad,profesion,ps,cedula,honorarios,presupuesto\n'; foreach($r5['data'] as $row){ $line = [ $row['id'],$row['estado'],$row['fecha_creacion'],$row['dias_desde_cambio'],$row['gerencia'],$row['dependencia'],$row['unidad'],$row['profesion'],$row['ps'],$row['cedula'],$row['honorarios'],$row['presupuesto'] ]; $csv.=implode(',',array_map(function($v){return str_replace(["\n","\r",","],[' ',' ',' '],$v);},$line))."\n"; } $files['detalle_estado.csv']=$csv; } }
		try{
			$zip = new \ZipArchive();
			$tmp = tempnam(sys_get_temp_dir(), 'dashzip_');
			$zip->open($tmp, \ZipArchive::OVERWRITE);
			foreach($files as $name=>$content){ $zip->addFromString($name, $content); }
			$zip->close();
			$data = file_get_contents($tmp); @unlink($tmp);
			return ['ejecuto'=>true,'zip'=>base64_encode($data)];
		}catch(\Exception $e){ return ['ejecuto'=>false,'mensajeError'=>'Error creando ZIP']; }
	}

	// Export CSV resumen estados
	public function exportResumenEstados($datos){
		$r = $this->getResumenEstados($datos);
		if(!$r['ejecuto']) return $r;
		$csv = "estado,cantidad\n";
		foreach($r['data'] as $row){ $csv .= $row['estado'].",".$row['cantidad']."\n"; }
		return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
	}

	// Export CSV KPIs
	public function exportKPIs($datos){
		$r = $this->getKPIs($datos);
		if(!$r['ejecuto']) return $r;
		$csv = "pendientesUbicar,diasPromedio,porVencerse,enTramite,completados,total\n";
		if(!empty($r['data'])){
			$k = $r['data'][0];
			$csv .= $k['pendientesUbicar'].",".$k['diasPromedio'].",".$k['porVencerse'].",".$k['enTramite'].",".$k['completados'].",".$k['total']."\n";
		}
		return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
	}

	// Export CSV Trend semanal
	public function exportTrend($datos){
		$r = $this->getTrendSemanal($datos);
		if(!$r['ejecuto']) return $r;
		$csv = "semana,nuevas,completadas\n";
		foreach($r['data'] as $row){ $csv .= $row['semana'].",".$row['nuevas'].",".$row['completadas']."\n"; }
		return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
	}

	// Listado detallado para drill-down de dashboard
	public function getSolicitudesDashboard($datos){
		$restrict = '1=1';
		$types = '';
		$params = [];
		// estado específico
		if(isset($datos['estado']) && (int)$datos['estado']>0){
			$restrict .= ' AND sol.estado = ?';
			$types .= 'i';
			$params[] = (int)$datos['estado'];
		}
		// gerencia (si aplica)
		if(isset($datos['soloGerencia']) && (int)$datos['soloGerencia'] === 1){
			if(isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] !== 'Administrador'){
				$restrict .= ' AND ger.id = ?';
				$types .= 'i';
				$params[] = (int)$_SESSION['usuario']['gerencia'];
			}
		}
		// rango de fechas (creación)
		if(!empty($datos['startDate']) && !empty($datos['endDate'])){
			$restrict .= ' AND DATE(sol.fecha_creacion) BETWEEN ? AND ?';
			$types .= 'ss';
			$params[] = $datos['startDate'];
			$params[] = $datos['endDate'];
		}
		// gerencia específica (solo si admin - se valida rol para evitar eludir control)
		if(isset($datos['gerenciaId']) && (int)$datos['gerenciaId']>0){
			if(isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'Administrador'){
				$restrict .= ' AND ger.id = ?';
				$types .= 'i';
				$params[] = (int)$datos['gerenciaId'];
			}
		}
		// filtro profesión LIKE
		if(!empty($datos['profesionLike'])){
			$restrict .= ' AND nec.profesion LIKE ?';
			$types .= 's';
			$params[] = '%'.$datos['profesionLike'].'%';
		}
		$sql = "SELECT sol.id, sol.estado, DATE(sol.fecha_creacion) fecha_creacion, DATEDIFF(NOW(), sol.fecha_modificacion) dias_desde_cambio,
				ger.nombre AS gerencia, dep.dependencia, dep.unidad, nec.profesion, usu.nombre AS ps, usu.cedula, nec.honorarios, nec.presupuesto
			FROM solicitudes sol
			INNER JOIN (procesos pro INNER JOIN usuarios usu ON pro.contratista = usu.id
				INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id)
				ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
			WHERE $restrict
			ORDER BY sol.fecha_creacion DESC LIMIT 200"; // límite de seguridad
		$db = new database();
		return $db->ejecutarPreparado($sql, $types, $params);
	}

	public function exportSolicitudesEstado($datos){
		$r = $this->getSolicitudesDashboard($datos);
		if(!$r['ejecuto']) return $r;
		$csv = "id,estado,fecha_creacion,dias_desde_cambio,gerencia,dependencia,unidad,profesion,ps,cedula,honorarios,presupuesto\n";
		foreach($r['data'] as $row){
			$line = [
				$row['id'], $row['estado'], $row['fecha_creacion'], $row['dias_desde_cambio'],
				$row['gerencia'], $row['dependencia'], $row['unidad'], $row['profesion'],
				$row['ps'], $row['cedula'], $row['honorarios'], $row['presupuesto']
			];
			$csv .= implode(',', array_map(function($v){ return str_replace(["\n","\r",","], [' ',' ',' '], $v); }, $line))."\n";
		}
		return ['ejecuto'=>true,'csv'=>base64_encode($csv)];
	}

	public function exportSolicitudesEstadoXlsx($datos){
		$r = $this->getSolicitudesDashboard($datos);
		if(!$r['ejecuto']) return $r;
		try{
			// Carga PHPExcel
			require_once 'plugins/PHPExcel/PHPExcel.php';
			$excel = new \PHPExcel();
			$excel->getProperties()->setCreator('JANUS')->setTitle('Solicitudes por estado');
			$sheet = $excel->setActiveSheetIndex(0);
			$cols = ['ID','Estado','Fecha creación','Días sin cambio','Gerencia','Dependencia','Unidad','Profesión','PS','Cédula','Honorarios','Presupuesto'];
			$colIndex=0; foreach($cols as $c){ $sheet->setCellValueByColumnAndRow($colIndex++,1,$c); }
			$fila=2;
			foreach($r['data'] as $row){
				$colIndex=0;
				$vals = [ $row['id'],$row['estado'],$row['fecha_creacion'],$row['dias_desde_cambio'],$row['gerencia'],$row['dependencia'],$row['unidad'],$row['profesion'],$row['ps'],$row['cedula'],$row['honorarios'],$row['presupuesto'] ];
				foreach($vals as $v){ $sheet->setCellValueByColumnAndRow($colIndex++,$fila,$v); }
				$fila++;
			}
			// Formatos simples
			$sheet->setTitle('Detalle');
			foreach(range('A','L') as $col){ $sheet->getColumnDimension($col)->setAutoSize(true); }
			$writer = \PHPExcel_IOFactory::createWriter($excel,'Excel2007');
			ob_start();
			$writer->save('php://output');
			$data = ob_get_clean();
			return ['ejecuto'=>true,'xlsx'=>base64_encode($data)];
		}catch(\Exception $e){
			return ['ejecuto'=>false,'mensajeError'=>'Error generando XLSX'];
		}
	}

	// Tendencia semanal (últimas N semanas) de nuevas y completadas
	public function getTrendSemanal($datos){
		$weeks = isset($datos['weeks']) ? (int)$datos['weeks'] : 8; if($weeks<=0 || $weeks>52) $weeks = 8;
		$filtraGer = false; $gerId = null;
		// Filtro explícito por gerencia (para cualquier rol; seguridad depende de lo que expone el listado de gerencias)
		if(isset($datos['gerenciaId']) && (int)$datos['gerenciaId']>0){
			$filtraGer = true; $gerId = (int)$datos['gerenciaId'];
		}
		if(isset($datos['soloGerencia']) && (int)$datos['soloGerencia']===1){
			if(isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol']!=='Administrador'){
				$filtraGer = true; $gerId = (int)$_SESSION['usuario']['gerencia'];
			}
		}
		$useRange = (!empty($datos['startDate']) && !empty($datos['endDate']));
		$restrictCre = '';
		$restrictCom = '';
		if($filtraGer){ $restrictCre .= ' AND ger.id = ?'; $restrictCom .= ' AND ger.id = ?'; }
		if($useRange){
			$restrictCre .= ' AND DATE(sol.fecha_creacion) BETWEEN ? AND ?';
			$restrictCom .= ' AND DATE(sol.fecha_modificacion) BETWEEN ? AND ?';
		}
		$sql = "SELECT semana, SUM(nuevas) nuevas, SUM(completadas) completadas FROM (
			SELECT YEARWEEK(sol.fecha_creacion,1) AS semana, COUNT(1) nuevas, 0 completadas
			FROM solicitudes sol INNER JOIN (procesos pro INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
			WHERE sol.fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL ? WEEK) $restrictCre
			GROUP BY YEARWEEK(sol.fecha_creacion,1)
			UNION ALL
			SELECT YEARWEEK(sol.fecha_modificacion,1) AS semana, 0 nuevas, COUNT(1) completadas
			FROM solicitudes sol INNER JOIN (procesos pro INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
			WHERE sol.fecha_modificacion >= DATE_SUB(CURDATE(), INTERVAL ? WEEK) AND sol.estado >= 17 $restrictCom
			GROUP BY YEARWEEK(sol.fecha_modificacion,1)
		) t GROUP BY semana ORDER BY semana DESC LIMIT 12";
		// Construimos manualmente el orden de parámetros:
		// 1) weeks
		// 2) (ger) opcional para creación
		// 3) (startDate, endDate) opcionales para creación
		// 4) weeks
		// 5) (ger) opcional para modificación
		// 6) (startDate, endDate) opcionales para modificación
		$types=''; $params=[];
		$types.='i'; $params[]=$weeks;
		if($filtraGer){ $types.='i'; $params[]=$gerId; }
		if($useRange){ $types.='ss'; $params[]=$datos['startDate']; $params[]=$datos['endDate']; }
		$types.='i'; $params[]=$weeks;
		if($filtraGer){ $types.='i'; $params[]=$gerId; }
		if($useRange){ $types.='ss'; $params[]=$datos['startDate']; $params[]=$datos['endDate']; }
		$db = new database();
		return $db->ejecutarPreparado($sql,$types,$params);
	}

	public function crear($datos){
		$resultado = parent::insert([
			'info' => $datos
		]);
		//Guardo historico
		if($resultado['ejecuto']){
			$info = [
				'info'=>[
					'fk_solicitudes'=>$resultado['insertId'],
					'informacion' => json_encode($datos)
				]
			];
			$objHistorico = new solicitudesHistorico();
			$respuesta = $objHistorico->insert($info);
			if($respuesta['ejecuto']){
				return $resultado;
			}
		}else{
			return $resultado;
		}
	}

	public function getSolicitudes($datos){
		$where = '';
		$types = '';
		$params = [];
		
		// Criterio base de búsqueda
		switch ($datos['criterio']) {
			case 'id':
				$where = "sol.id = ?"; 
				$types .= 'i'; 
				$params[] = (int)$datos['id'];
				break;
			case 'gerencia':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'dependencia':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'ps':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'cedula':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'profesion':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'idProceso':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'area':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
			case 'todas':
				$where = '1=1';
				break;
			default:
				// Por defecto mostrar según permisos del usuario
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$where = '1=1';
				} else {
					$where = 'ger.id = ?'; 
					$types .= 'i'; 
					$params[] = (int)$_SESSION['usuario']['gerencia'];
				}
				break;
		}
		
		// Filtro por estado si se especifica
		if(isset($datos['estado']) && (int)$datos['estado'] > 0){
			$where .= ' AND sol.estado = ?';
			$types .= 'i';
			$params[] = (int)$datos['estado'];
		}
		$sql = "SELECT
					sol.id,
					sol.estado,
					ger.id AS idGerencia,
					ger.nombre AS gerencia,
					dep.dependencia,
					dep.unidad,
					nec.id AS idNecesidad,
					nec.profesion,
					nec.honorarios,
					nec.presupuesto,
					pro.id AS idProceso,
					usu.id AS idPS,
					usu.nombre AS ps,
					usu.cedula,
					usu.telefono,
					DATEDIFF(NOW(),sol.fecha_modificacion) AS tiempo
				FROM
					solicitudes sol INNER JOIN ((procesos pro INNER JOIN usuarios usu ON pro.contratista = usu.id) INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
				WHERE $where";
		$db = new database();
		return $db->ejecutarPreparado($sql, $types, $params);
	}

	public function getSolicitudAll($datos){
		$where = '0=1';
		$types = '';
		$params = [];
		switch ($datos['criterio']) {
			case 'id':
				$val = (int)$datos['valor'];
				$where = 'sol.id = ?';
				$types = 'i';
				$params[] = $val;
				break;
			default:
				$where = '0=1';
				break;
		}
		$sql = "SELECT
					sol.id,
					ger.id AS idGerencia,
					ger.nombre AS gerencia,
					dep.dependencia,
					dep.unidad,
					nec.id AS idNecesidad,
					nec.pacc,
					nec.profesion,
					nec.objeto,
					nec.alcance,
					nec.honorarios,
					nec.presupuesto,
					pro.id AS idProceso,
					usu.id AS idPS,
					usu.nombre AS ps,
					usu.cedula,
					usu.correo,
					usu.telefono,
					usu.foto,
					sol.cdp_numero,
					sol.cdp_valor,
					sol.cdp_fecha,
					sol.estado,
					sol.fecha_creacion
				FROM
					solicitudes sol INNER JOIN ((procesos pro INNER JOIN usuarios usu ON pro.contratista = usu.id) INNER JOIN (necesidades nec INNER JOIN (dependencias dep INNER JOIN gerencias ger ON dep.fk_gerencias = ger.id) ON nec.fk_dependencias = dep.id) ON pro.fk_necesidades = nec.id) ON sol.fk_procesos = pro.id
				WHERE $where";
		$db = new database();
		return $db->ejecutarPreparado($sql, $types, $params);
	}

	public function setEstado($datos){
        //Actualizo la solicitud
        $resultado = parent::update($datos);        
        //Guardo historico
        if($resultado['ejecuto']){
            $info = [
                'info'=>[
                    'fk_solicitudes'=>$datos['id'],
                    'informacion'=>json_encode($datos['info'])
                ]
            ];
            $sh = new solicitudesHistorico();
            $historico = $sh->insert($info);
			if($historico['ejecuto']){
				// Si devolvieron la solicitud a estado 6 (Examen preocupacional), limpiar campos EEP del proceso para permitir re-evaluación
				if(isset($datos['info']['estado']) && (int)$datos['info']['estado'] === 6){
					try{
						$db = new database();
						// Obtener proceso asociado
						$rs = $db->ejecutarPreparado('SELECT fk_procesos FROM solicitudes WHERE id = ? LIMIT 1', 'i', [ (int)$datos['id'] ]);
						if($rs['ejecuto'] && !empty($rs['data'])){
							$idProceso = (int)$rs['data'][0]['fk_procesos'];
							// Limpiar EEP en procesos; usar NULL reales
							$db2 = new database();
							$db2->ejecutarPreparado('UPDATE procesos SET fecha_eep = NULL, resultado_eep = NULL, observaciones_eep = NULL, fecha_modificacion = NOW() WHERE id = ?', 'i', [ $idProceso ]);
						}
					}catch(\Exception $e){ /* noop */ }
				}
				return $resultado;
            }
        }else{
			return $resultado;
		}
    }

    public function setProceso($datos){    	
		//Actualizo el contrato
		$objProcesos = new procesos();
		$respuesta = $objProcesos->update($datos['infoP']);
		if($respuesta['ejecuto']){
			//Actualizo la solicitud
			return $this->setEstado($datos['infoS']);
		}
	}

	public function ocuparNecesidad($datos){
		//Ocupo Necesidad
		$objNecesidades = new necesidades();
		$respuesta = $objNecesidades->update($datos['infoN']);
		if($respuesta['ejecuto']){
			//Actualizo el proceso
			return $this->setProceso($datos);
		}
	}

	public function getExportCDP($datos){
		$sql = "SELECT
					usu.nombre,
					ni.imputacion,
					ni.valor
				FROM
					(necesidades nec INNER JOIN necesidades_imputaciones ni ON nec.id = ni.fk_necesidades) INNER JOIN ((procesos pro INNER JOIN solicitudes sol ON pro.id = sol.fk_procesos) INNER JOIN usuarios usu ON pro.contratista = usu.id) ON nec.id = pro.fk_necesidades
				WHERE
					sol.estado = 4
				ORDER BY
					usu.nombre";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}