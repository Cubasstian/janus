<?php
// Prerequisitos según flujo BPMN corregido con compuertas AND/XOR
return [
    // Tarea 1: Cargar documentación - activada después de ocupar necesidad
    'cargarDocumentacion' => [],
    
    // Tarea 2: Ocupar necesidad - punto de inicio del flujo
    'ocuparNecesidad' => [],
    
    // Tarea 3: Revisar documentación - AND Split con Crear tercero
    'revisarDocumentacion' => [],
    
    // Tarea 4: Crear tercero - paralelo con revisión documentación
    'crearTercero' => [],
    
    // Tarea 5: Expedir CDP - requiere documentación revisada
    'expedirCDP' => [],
    
    // Tarea 6: Llenar ficha requerimiento - AND Join (requiere 3 Y 5)
    'fichaRequerimiento' => [],
    
    // Tarea 7: Evaluar examen preocupacional
    'evaluarEEP' => ['Ficha de requerimiento'],
    
    // Tarea 8: Validar perfil - XOR Gateway (aprobado->11, rechazado->9)
    'validarPerfil' => ['Certificado médico'],
    
    // Tarea 9: Verificación CIIP - bucle de retorno a revisión
    'ciip' => [],
    
    // Tarea 10: Numerar contrato - después de minuta
    'numerar' => ['Minuta'],
    
    // Tarea 11: Elaborar minuta - ruta aprobada del XOR
    'minuta' => ['Ficha de requerimiento'],
    
    // Tarea 12: Emitir RP - AND Split con Afiliar ARL
    'expedirRP' => ['Contrato'],
    
    // Tarea 13: Afiliar ARL - paralelo con Emitir RP
    'afiliarARL' => ['Contrato'],
    
    // Tarea 14: Designar supervisor - AND Join (requiere 12 Y 13)
    'designarSupervisor' => [],
    
    // Tarea 15: Acta de inicio
    'actaInicio' => ['RP'],
    
    // Tarea 16: Contratado - evento de finalización
    'finalizar' => ['Acta de inicio']
];