<?php
$xpdo_meta_map['exampleItem']= array (
  'package' => 'example',
  'version' => '1.1',
  'table' => 'example_items',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' =>
  array (
    'engine' => 'InnoDB',
  ),
  'fields' =>
  array (
    'name' => '',
    'desc' => '',
    'img_before' => '',
    'img_after' => '',
    'spec_id' => 0,
    'dept_id' => 0,
    'subdept_id' => 0,
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
    'img_before' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'img_after' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
    'dept_id' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'subdept_id' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
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
CREATE TABLE `my__vtv`.`modx_example_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL DEFAULT '',
  `desc` MEDIUMTEXT NOT NULL,
  `img_before` TEXT NOT NULL DEFAULT '',
  `img_after` TEXT NOT NULL DEFAULT '',
  `spec_id` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_hidden` TINYINT(1) NOT NULL DEFAULT 0,
  `dept_id` INT UNSIGNED NOT NULL DEFAULT 0,
  `subdept_id` INT UNSIGNED NOT NULL DEFAULT 0,
  `createdAt` DATETIME NOT NULL,
  `updatedAt` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_is_hidden` (`is_hidden`),
  INDEX `idx_spec_id` (`spec_id`),
  INDEX `idx_dept_id` (`dept_id`),
  INDEX `idx_subdept_id` (`subdept_id`),
  INDEX `idx_is_hidden` (`is_hidden`),
  INDEX `idx_created` (`createdAt`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
*/
