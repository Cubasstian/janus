<?php
// Permisos para revisar (cambiar estado) de documentos por rol.
// Ajustar según políticas organizacionales.
return [
    // Roles que pueden aceptar / rechazar documentos generales
    'default' => ['Administrador','Revisor'],
    // Documentos médicos quizás solo Salud Ocupacional
    'Certificado médico' => ['Administrador','SaludOc'],
    // Minuta y Acta de inicio sólo Jurídica puede marcarlas (ejemplo)
    'Minuta' => ['Administrador','Juridica'],
    'Acta de inicio' => ['Administrador','Juridica']
];