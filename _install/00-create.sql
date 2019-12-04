DROP DATABASE IF EXISTS `foodie`;
CREATE DATABASE IF NOT EXISTS `foodie`;

USE `foodie`;
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `address` varchar(255) NOT NULL,
    `email` varchar(60) NOT NULL,
    `type` ENUM('producer', 'customer') NOT NULL,
    `image` varchar(255) NOT NULL,
    `lat` DECIMAL(10,8),
    `lng` DECIMAL(11,8),
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at`  datetime NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`phone`),
    UNIQUE KEY (`email`)
) ENGINE=InnoDB;

USE `foodie`;
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `first_name` varchar(60) NOT NULL,
    `last_name` varchar(60) NOT NULL,
    `phone` varchar(20) NOT NULL,
    `address` varchar(255) NOT NULL,
    `email` varchar(60) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` ENUM('producer_admin', 'producer_user', 'customer_admin','customer_user','user') NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at`  datetime NOT NULL,
    `company_id` int(10) unsigned,
    PRIMARY KEY (`id`, `role`),
    UNIQUE KEY (`phone`),
    UNIQUE KEY (`email`),
    FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
) ENGINE=InnoDB;

use `foodie`;
DROP TABLE IF EXISTS `meal_categories`;
CREATE TABLE `meal_categories` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL,
    `image` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at`  datetime NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `user_role` ENUM('producer_admin', 'producer_user', 'customer_admin','customer_user','user') NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY (`name`),
    FOREIGN KEY (`user_id`, `user_role`) REFERENCES `users` (`id`, `role`)
)ENGINE=InnoDB;

use `foodie`;
DROP TABLE IF EXISTS `meals`;
CREATE TABLE `meals` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(60) NOT NULL,
    `description` text,
    `image` varchar(255) NOT NULL,
    `price` decimal(8,2) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at`  datetime NOT NULL,
    `meal_category_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `user_role` ENUM('producer_admin', 'producer_user', 'customer_admin','customer_user','user') NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY (`name`),
    UNIQUE KEY (`image`),
    FOREIGN KEY (`meal_category_id`) REFERENCES `meal_categories` (`id`),
    FOREIGN KEY (`user_id`, `user_role`) REFERENCES `users` (`id`, `role`)
)ENGINE=InnoDB;

use `foodie`;
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `price` decimal(8,2) NOT NULL,
    `discount` decimal(3,2) default '1',
    `delivery_datetime` datetime NOT NULL,
    `status` ENUM('ordered','cancelled', 'processing', 'deliverded') NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at`  datetime NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    `company_id` int(10) unsigned NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
)ENGINE=InnoDB;

use `foodie`;
DROP TABLE IF EXISTS `meal_order`;
CREATE TABLE `meal_order` (
    `order_id` int(10) unsigned NOT NULL,
    `meal_id` int(10) unsigned NOT NULL,
    `price` decimal(8,2) NOT NULL,
    `quantity` int(3) NOT NULL,
    PRIMARY KEY(`order_id`, `meal_id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
    FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`)
)ENGINE=InnoDB;
