<?php
// Mapa de permisos según roles especificados en BPMN CORREGIDO
return [
    // Tareas del flujo principal según especificación corregida
    'cargarDocumentacion' => ['PS'],                           // Tarea 1
    'ocuparNecesidad' => ['Administrador'],                    // Tarea 2 (ÚNICO para Administrador)
    'revisarDocumentacion' => ['Revisor'],                     // Tarea 3
    'crearTercero' => ['Financiera'],                          // Tarea 4
    'expedirCDP' => ['Financiera'],                            // Tarea 5
    'fichaRequerimiento' => ['UGA'],                           // Tarea 6
    'evaluarEEP' => ['SaludOcupacional'],                      // Tarea 7
    'validarPerfil' => ['GestionHumana'],                      // Tarea 8
    'ciip' => ['GestionHumana'],                               // Tarea 9
    'numerar' => ['Secretaria'],                               // Tarea 10
    'minuta' => ['UGA'],                                       // Tarea 11 (UGA)
    'expedirRP' => ['Financiera'],                             // Tarea 12
    'afiliarARL' => ['GestionHumana'],                         // Tarea 13
    'designarSupervisor' => ['UGA'],                           // Tarea 14 (UGA)
    'actaInicio' => ['UGA'],                                   // Tarea 15 (UGA)
    'finalizar' => ['UGA'],                                    // Tarea 16 (UGA)
    
    // Funciones de sistema - Administrador mantiene acceso total de visualización
    'checkPrerequisitos' => ['Administrador','UGA','GestionHumana','Financiera','SaludOcupacional','Revisor'],
    'getMapaEstados' => ['Administrador','UGA','GestionHumana','Financiera','SaludOcupacional','Revisor'],
    'getDefinicionFlujo' => ['Administrador','UGA','GestionHumana','Financiera','SaludOcupacional','Revisor'],
    'diagnosticoFlujo' => ['Administrador'],
    'ping' => ['Administrador','UGA','GestionHumana','Financiera','SaludOcupacional','Revisor','PS'],
    'forceTransicion' => ['Administrador'],
    'forceTransicionStatus' => ['Administrador'],
    'metricsFlujo' => ['Administrador'],
    'auditoriaIntegridad' => ['Administrador'],
    'getHistoricoProceso' => ['Administrador','UGA','GestionHumana','Financiera'],
    'exportMetricsFlujo' => ['Administrador'],
    'exportAuditoriaIntegridad' => ['Administrador'],
    'overrideStats' => ['Administrador'],
    'exportOverrideStats' => ['Administrador'],
    'healthStatus' => ['Administrador'],
    'overrideAnomalies' => ['Administrador'],
    'selfTest' => ['Administrador'],
    'alertasFlujo' => ['Administrador'],
    'exportAlertasFlujo' => ['Administrador']
];
