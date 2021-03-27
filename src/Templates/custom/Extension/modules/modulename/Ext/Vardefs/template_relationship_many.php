<?php

$dictionary[':Singular']['fields'][':linkName'] = [
    'name' => ':linkName',
    'type' => 'link',
    'relationship' => ':lower_case_rel_name',
    'source' => 'non-db',
    'vname' => ':label',
    'module' => ':Module', //important to have
    'bean_name' => ':Module', //importanto to have
    'id_name' => ':join_key',
];

