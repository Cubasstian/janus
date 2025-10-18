-- Migración: Índices recomendados para rendimiento de métricas, auditorías y overrides
-- Fecha: 2025-09-28
-- Objetivo: acelerar consultas sobre historial y estados.

-- 1. Índice compuesto sobre historial por solicitud y fecha (orden cronológico)
ALTER TABLE solicitudes_historico
  ADD INDEX idx_sh_solicitud_fecha (fk_solicitudes, fecha_creacion);

-- 2. Índice sobre campo estado en solicitudes para métricas de inventario
ALTER TABLE solicitudes
  ADD INDEX idx_solicitudes_estado (estado);

-- 3. Índice sobre procesos.numero_contrato para auditorías de integridad que filtran por presencia de contrato
ALTER TABLE procesos
  ADD INDEX idx_procesos_numero_contrato (numero_contrato);

-- 4. Índice sobre procesos.numero_rp para validaciones tempranas (opcional)
ALTER TABLE procesos
  ADD INDEX idx_procesos_numero_rp (numero_rp);

-- 5. (Opcional) Índice sobre procesos.contratista si se consulta frecuentemente por persona
ALTER TABLE procesos
  ADD INDEX idx_procesos_contratista (contratista);

-- NOTAS:
-- - Verificar que los índices no existan previamente antes de ejecutar en producción.
-- - Ajustar nombres si hay colisiones.
-- - Para rollback: DROP INDEX idx_nombre ON tabla;
