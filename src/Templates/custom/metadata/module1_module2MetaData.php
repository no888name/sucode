<?php

// created: 2019-09-11 14:09:36
$dictionary[':lower_case_rel_name'] = [
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => [
    ':lower_case_rel_name' => [
      'lhs_module' => ':lhsModule',
      'lhs_table' => ':lhsTable',
      'lhs_key' => ':lhsKey',
      'rhs_module' => ':rhsModule',
      'rhs_table' => ':rhsTable',
      'rhs_key' => ':rhsKey',
      'relationship_type' => 'many-to-many',
      'join_table' => ':lower_case_rel_name',
      'join_key_lhs' => ':join_key_lhs',
      'join_key_rhs' => ':join_key_rhs',
    ],
  ],
  'table' => ':lower_case_rel_name',
  'fields' => [
    'id' => [
      'name' => 'id',
      'type' => 'id',
    ],
    'date_modified' => [
      'name' => 'date_modified',
      'type' => 'datetime',
    ],
    'deleted' => [
      'name' => 'deleted',
      'type' => 'bool',
      'default' => 0,
    ],
    ':join_key_lhs' => [
      'name' => ':join_key_lhs',
      'type' => 'id',
    ],
    ':join_key_rhs' => [
      'name' => ':join_key_rhs',
      'type' => 'id',
    ],
  ],
  'indices' => [
    0 => [
      'name' => 'idx_:lower_case_rel_name_pk',
      'type' => 'primary',
      'fields' => [
        0 => 'id',
      ],
    ],
    1 => [
      'name' => 'idx_:lower_case_rel_name_ida1_deleted',
      'type' => 'index',
      'fields' => [
        0 => ':join_key_lhs',
        1 => 'deleted',
      ],
    ],
    2 => [
      'name' => 'idx_:lower_case_rel_name_idb2_deleted',
      'type' => 'index',
      'fields' => [
        0 => ':join_key_rhs',
        1 => 'deleted',
      ],
    ],
    3 => [
      'name' => ':lower_case_rel_name_alt',
      'type' => 'alternate_key',
      'fields' => [
        0 => ':join_key_rhs',
      ],
    ],
  ],
];
