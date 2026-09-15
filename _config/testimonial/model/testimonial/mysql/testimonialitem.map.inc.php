<?php
$xpdo_meta_map['testimonialItem']= array (
  'package' => 'testimonial',
  'version' => '1.1',
  'table' => 'testimonial_items',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' =>
  array (
    'engine' => 'InnoDB',
  ),
  'fields' =>
  array (
    'name' => '',
    'desc' => '',
    'spec_id' => 0,
    'rating' => 1,
    'is_hidden' => 0,
    'createdAt' => NULL,
    'updatedAt' => NULL,
  ),
  'fieldMeta' =>
  array (
    'name' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'desc' =>
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'spec_id' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'rating' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'is_hidden' =>
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'createdAt' =>
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'updatedAt' =>
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
  ),
);
/*
CREATE TABLE `my__vtv`.`modx_testimonial_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL DEFAULT '',
  `desc` MEDIUMTEXT NOT NULL,
  `spec_id` INT UNSIGNED NOT NULL DEFAULT 0,
  `rating` INT UNSIGNED NOT NULL DEFAULT 1,
  `is_hidden` TINYINT(1) NOT NULL DEFAULT 0,
  `createdAt` DATETIME NOT NULL,
  `updatedAt` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_is_hidden` (`is_hidden`),
  INDEX `idx_spec_id` (`spec_id`),
  INDEX `idx_rating` (`rating`),
  INDEX `idx_created` (`createdAt`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
*/
