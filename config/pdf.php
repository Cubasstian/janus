<?php
// Configuración centralizada para PDFs JANUS
return [
    'stamps' => [
        // Umbrales de estado para mostrar sello de "APROBADO"
        'fr_aprobado_min_estado' => 13,
        'acta_aprobado_min_estado' => 16,
        // Estilo del sello
        'color' => [ 'r' => 0, 'g' => 128, 'b' => 0 ],
        'textColor' => [ 'r' => 0, 'g' => 100, 'b' => 0 ],
        'box' => [ 'x' => 135, 'y' => 20, 'w' => 65, 'h' => 18 ],
        'title' => 'APROBADO'
    ],
];
