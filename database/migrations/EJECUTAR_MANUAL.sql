-- ==========================================
-- EJECUTAR ESTE SQL MANUALMENTE EN LA BASE DE DATOS
-- ==========================================

-- 1. Hacer user_id nullable en orders
ALTER TABLE `orders`
  DROP FOREIGN KEY `orders_user_id_foreign`;

ALTER TABLE `orders`
  MODIFY COLUMN `user_id` BIGINT UNSIGNED NULL;

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign`
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE SET NULL;

-- 2. Agregar campos de pending_group_token y pending_email
ALTER TABLE `orders`
  ADD COLUMN `pending_group_token` VARCHAR(255) NULL AFTER `order_number`,
  ADD COLUMN `pending_email` VARCHAR(255) NULL AFTER `pending_group_token`;

-- 3. Agregar índice para pending_group_token
ALTER TABLE `orders`
  ADD INDEX `orders_pending_group_token_index` (`pending_group_token`);

-- Verificar que se aplicaron los cambios
DESCRIBE `orders`;
