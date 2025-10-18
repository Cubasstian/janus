<?php
// Umbrales de monitoreo / alertas. Ajustar según tolerancias operativas.
return [
    // Si el porcentaje (overrides / transiciones) en la ventana (overrideStats dias) supera este valor -> alerta danger
    'override_ratio_window' => 0.15,
    // Si el ratio últimas 24h supera este valor -> alerta danger
    'override_ratio_24h' => 0.20,
    // Promedio diario mínimo de overrides por usuario para marcar anomalía
    'override_user_avg_daily' => 3
];
