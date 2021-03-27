<?php

$dictionary[':lhsSingular']['fields'][':linkName'] = [
    'name' => ':linkName',
    'type' => 'link',
    'relationship' => ':relationshipName',
    'source' => 'non-db',
    'vname' => ':label',
    'module' => ':rhsModule', //important to have
    'bean_name' => ':rhsModule', //importanto to have
];

$dictionary[':lhsSingular']['relationships'][':relationshipName'] = [
    'lhs_module' => ':lhsName',
    'lhs_table' => ':lhsTable',
    'lhs_key' => ':lhsKey',
    'rhs_module' => ':rhsModule',
    'rhs_table' => ':rhsTable',
    'rhs_key' => ':rhsKey',
    'relationship_type' => 'one-to-many',
];
