<?php
// Definición formal del flujo según especificación BPMN corregida
return [
    ['estado'=>1, 'nombre'=>'Cargar documentación', 'accion'=>'cargarDocumentacion', 'siguiente'=>3],
    ['estado'=>2, 'nombre'=>'Ocupar necesidad', 'accion'=>'ocuparNecesidad', 'siguiente'=>1],
    ['estado'=>3, 'nombre'=>'Revisar documentación', 'accion'=>'revisarDocumentacion', 'siguiente'=>6],
    ['estado'=>4, 'nombre'=>'Crear tercero', 'accion'=>'crearTercero', 'siguiente'=>5],
    ['estado'=>5, 'nombre'=>'Expedir CDP', 'accion'=>'expedirCDP', 'siguiente'=>6],
    ['estado'=>6, 'nombre'=>'Llenar ficha de requerimiento', 'accion'=>'fichaRequerimiento', 'siguiente'=>7],
    ['estado'=>7, 'nombre'=>'Evaluar examen preocupacional', 'accion'=>'evaluarEEP', 'siguiente'=>8],
    ['estado'=>8, 'nombre'=>'Validar perfil', 'accion'=>'validarPerfil', 'siguiente'=>11], // XOR: Si aprobado -> 11, Si rechazado -> 9
    ['estado'=>9, 'nombre'=>'Verificación CIIP', 'accion'=>'ciip', 'siguiente'=>3], // Bucle de retorno a revisión
    ['estado'=>10,'nombre'=>'Numerar contrato', 'accion'=>'numerar', 'siguiente'=>12], // AND Split a 12 y 13
    ['estado'=>11,'nombre'=>'Elaborar minuta', 'accion'=>'minuta', 'siguiente'=>10],
    ['estado'=>12,'nombre'=>'Emitir RP', 'accion'=>'expedirRP', 'siguiente'=>14], // AND Join con 13
    ['estado'=>13,'nombre'=>'Afiliar ARL', 'accion'=>'afiliarARL', 'siguiente'=>14], // AND Join con 12
    ['estado'=>14,'nombre'=>'Designar supervisor', 'accion'=>'designarSupervisor', 'siguiente'=>15],
    ['estado'=>15,'nombre'=>'Acta de inicio', 'accion'=>'actaInicio', 'siguiente'=>16],
    ['estado'=>16,'nombre'=>'Contratado', 'accion'=>'finalizar', 'siguiente'=>null],
];