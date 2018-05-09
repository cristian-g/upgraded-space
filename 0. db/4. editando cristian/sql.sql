CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 DEFAULT '',
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `birthdate` date NOT NULL,  
  `password` CHAR(60) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL,
  `email_activation_key` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`uuid`),
  UNIQUE KEY `UNIQUE_EMAIL` (`email`),
  UNIQUE KEY `UNIQUE_USERNAME` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 DEFAULT '',
  `id_user` int(11) unsigned NOT NULL,
  `id_parent` int(11) unsigned,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `ext` varchar(255) CHARACTER SET utf8mb4 DEFAULT '',
  `bytes_size` int(32) unsigned,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`uuid`),
  FOREIGN KEY (`id_user`) REFERENCES user(id),
  FOREIGN KEY (`id_parent`) REFERENCES upload(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;