-- Migración: Flujo extendido con CIIP y reindexación de estados
-- Ejecutar en la base de datos correspondiente.

-- 1. Columnas CIIP (si no existen)
ALTER TABLE procesos
  ADD COLUMN IF NOT EXISTS fecha_ciip DATE NULL,
  ADD COLUMN IF NOT EXISTS resultado_ciip VARCHAR(100) NULL,
  ADD COLUMN IF NOT EXISTS observaciones_ciip TEXT NULL;

-- 2. Columnas EEP (por si faltan)
ALTER TABLE procesos
  ADD COLUMN IF NOT EXISTS fecha_eep DATE NULL,
  ADD COLUMN IF NOT EXISTS resultado_eep VARCHAR(100) NULL,
  ADD COLUMN IF NOT EXISTS observaciones_eep TEXT NULL;

-- 3. Columnas Validación perfil (por si faltan)
ALTER TABLE procesos
  ADD COLUMN IF NOT EXISTS perfil_validado TINYINT(1) NULL,
  ADD COLUMN IF NOT EXISTS fecha_validacion_perfil DATE NULL,
  ADD COLUMN IF NOT EXISTS observaciones_perfil TEXT NULL;

-- 4. Columnas Recoger validación perfil (por si faltan)
ALTER TABLE procesos
  ADD COLUMN IF NOT EXISTS fecha_recoger_perfil DATE NULL,
  ADD COLUMN IF NOT EXISTS observaciones_recoger_perfil TEXT NULL;

-- 5. Columnas para solicitud de afiliación
ALTER TABLE procesos
  ADD COLUMN IF NOT EXISTS fecha_solicitud_afiliacion DATE NULL,
  ADD COLUMN IF NOT EXISTS observaciones_solicitud_afiliacion TEXT NULL;

-- Notas:
-- - La reindexación de estados se maneja solo a nivel lógico; no es necesario actualizar registros existentes
--   salvo que se requiera migrar procesos en curso al nuevo mapa. Si hay procesos en estados 7/8 previos,
--   decidir una regla de traducción (ej.: antiguo 7->8, 8->9, etc.).
-- - Ajustar triggers o vistas/materializaciones que dependan de los números anteriores.
