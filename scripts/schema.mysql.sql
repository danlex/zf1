CREATE TABLE `guestbook` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(50) NULL DEFAULT NULL,
    `comment` TEXT NULL,
    `created` TIMESTAMP NULL DEFAULT NULL,
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
