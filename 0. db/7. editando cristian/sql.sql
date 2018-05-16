CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 DEFAULT '',
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `birthdate` date NOT NULL,  
  `password` CHAR(60) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL,
  `email_activation_key` varchar(100) NOT NULL,
  `default_picture` boolean,
  `extension` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`uuid`),
  UNIQUE KEY `UNIQUE_EMAIL` (`email`),
  UNIQUE KEY `UNIQUE_USERNAME` (`username`),
  UNIQUE KEY `UNIQUE_EMAIL_ACTIVATION_KEY` (`email_activation_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 DEFAULT '',
  `id_user` int(11) unsigned NOT NULL,
  `id_parent` int(11) unsigned,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `ext` varchar(255) CHARACTER SET utf8mb4 DEFAULT '',
  `bytes_size` int(32) unsigned,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`uuid`),
  FOREIGN KEY (`id_user`) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY (`id_parent`) REFERENCES upload(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_upload` int(11) unsigned NOT NULL,
  `id_user_destination` int(11) unsigned NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_upload`) REFERENCES upload(id) ON DELETE CASCADE,
  FOREIGN KEY (`id_user_destination`) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `notification` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_share` int(11) unsigned NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 DEFAULT '',
  `message` text CHARACTER SET utf8mb4,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_share`) REFERENCES share(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;