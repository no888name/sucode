<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

//change directories to where this file is located.
chdir(dirname(__FILE__));

require_once('include/entryPoint.php');
require_once('Faker.php');

$sapi_type = php_sapi_name();
// Allow only CLI invocation
if ('cli' != substr($sapi_type, 0, 3)) {
    sugar_die('is CLI only.');
}

//create bacup

//clean emails addresses

global $db;

$sql = "update email_addresses 
        inner join email_addr_bean_rel on email_addr_bean_rel.email_address_id = email_addresses.id 
        set email_address = concat('andleex+',REPLACE(email_addr_bean_rel.bean_id, '-', ''),'@gmail.com'),
		    email_address_caps = concat('andleex+',REPLACE(email_addr_bean_rel.bean_id, '-', ''),'@gmail.com')";
$stmt = $db->getConnection()->executeQuery($sql);


//do contacts
global $db;

$sql = "SELECT id FROM contacts";
$stmt = $db->getConnection()->executeQuery($sql, [

]);

echo 'Processing contacts'.$i.PHP_EOL;
$i = 1;

while($result = $stmt->fetch()){

//    echo 'Processing #'.$i.PHP_EOL;
//
//    $updateSql = "update contacts set first_name = :first_name, last_name = :last_name,
//            phone_home = :phone, phone_mobile = :phone, phone_work = :phone , phone_other = :phone, phone_fax = :phone,
//            primary_address_street = :primary_address_street,primary_address_city = :primary_address_city, primary_address_state = :primary_address_state,
//            primary_address_postalcode = :primary_address_postalcode,
//            primary_address_country = :primary_address_country
//            where id = :id
//            ";
//    $stmt1 = $db->getConnection()->executeQuery($updateSql, [
//        'first_name' => Faker::$firstNames[rand(0,count(Faker::$firstNames)-1)],
//        'last_name' => Faker::$lastNames[rand(0,count(Faker::$lastNames)-1)],
//        'phone' => Faker::$phoneNumbers[rand(0,count(Faker::$phoneNumbers)-1)],
//        'primary_address_street' => Faker::$streets[rand(0,count(Faker::$streets)-1)],
//        'primary_address_city' => Faker::$cities[rand(0,count(Faker::$cities)-1)],
//        'primary_address_state' => Faker::$states[rand(0,count(Faker::$states)-1)],
//        'primary_address_postalcode' => Faker::$postalCodes[rand(0,count(Faker::$postalCodes)-1)],
//        'primary_address_country' => Faker::$countries[rand(0,count(Faker::$countries)-1)],
//        'id' => $result['id']
//    ]);
//
//    $i++;
}

//do accounts
$sql = "SELECT id FROM accounts";
$stmt = $db->getConnection()->executeQuery($sql, [

]);
$i = 1;

while($result = $stmt->fetch()){

    echo 'Processing Account #'.$i.PHP_EOL;

    $updateSql = "update accounts set name = :name,
            phone_office = :phone, phone_alternate = :phone, phone_fax = :phone , 
            billing_address_street = :billing_address_street,billing_address_city = :billing_address_city, billing_address_state = :billing_address_state, 
            billing_address_postalcode = :billing_address_postalcode,
            billing_address_country = :billing_address_country,
            
            shipping_address_street = :shipping_address_street,shipping_address_city = :shipping_address_city, shipping_address_state = :shipping_address_state, 
            shipping_address_postalcode = :shipping_address_postalcode,
            shipping_address_country = :shipping_address_country
            where id = :id
            ";
    $stmt1 = $db->getConnection()->executeQuery($updateSql, [
        'name' => Faker::$companyNames[rand(0,count(Faker::$companyNames)-1)] . '#'.$i,
        'phone' => Faker::$phoneNumbers[rand(0,count(Faker::$phoneNumbers)-1)],
        'billing_address_street' => Faker::$streets[rand(0,count(Faker::$streets)-1)],
        'billing_address_city' => Faker::$cities[rand(0,count(Faker::$cities)-1)],
        'billing_address_state' => Faker::$states[rand(0,count(Faker::$states)-1)],
        'billing_address_postalcode' => Faker::$postalCodes[rand(0,count(Faker::$postalCodes)-1)],
        'billing_address_country' => Faker::$countries[rand(0,count(Faker::$countries)-1)],

        'shipping_address_street' => Faker::$streets[rand(0,count(Faker::$streets)-1)],
        'shipping_address_city' => Faker::$cities[rand(0,count(Faker::$cities)-1)],
        'shipping_address_state' => Faker::$states[rand(0,count(Faker::$states)-1)],
        'shipping_address_postalcode' => Faker::$postalCodes[rand(0,count(Faker::$postalCodes)-1)],
        'shipping_address_country' => Faker::$countries[rand(0,count(Faker::$countries)-1)],
        'id' => $result['id']
    ]);

    $i++;
}
//do opportunities

//do leads

//do users

