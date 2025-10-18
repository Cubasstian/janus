-- Script seed tipos de documentos básicos usados en prerequisitos
-- Inserta solo si no existe uno con el mismo nombre.
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Hoja de vida' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Hoja de vida');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Certificado médico' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Certificado médico');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Ficha de requerimiento' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Ficha de requerimiento');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Minuta' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Minuta');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Contrato' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Contrato');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Solicitud de afiliación' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Solicitud de afiliación');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'RP' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'RP');
INSERT INTO documentos_tipo (nombre)
SELECT * FROM (SELECT 'Acta de inicio' AS n) x
WHERE NOT EXISTS (SELECT 1 FROM documentos_tipo WHERE nombre = 'Acta de inicio');