<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Itb\ProductRepository;
use Itb\AccountRepository;

$faker = Faker\Factory::create();

// ---------  Populate Product Database  ----------

$productRepository = new ProductRepository();

$productRepository->createTableProducts();

$images = ["drone.jpg","image1.jpg", "image2.jpg", "image3.jpg", "image4.jpg","image5.jpg", "image6.jpg", "image7.jpg", "track.jpg", "pilots.jpg"];

for($i=0;$i<10;$i++){

    $p = new \Itb\Product();

    $p->setName($faker->firstName());
    $p->setImage($images[$i]);
    $p->setPrice($faker->randomFloat());
    $p->setDescription($faker->sentence);

    $productRepository->insertProduct($p);
}

// ---------  Populate Accounts Database  ----------

$accountsRepository = new AccountRepository();

$accountsRepository->createTableAccounts();

for($i=0;$i<10;$i++) {

    $a = new \Itb\Account();

    $a->setFirstName($faker->firstName);
    $a->setSecondName($faker->lastName);
    $a->setCountry($faker->country);
    $a->setPassword(password_hash($faker->password(6),PASSWORD_DEFAULT));
    $a->setUser($faker->userName);

    $accountsRepository->insertAccount($a);
}


//---------------Populate Staff Database-------------------


$staffRepository = new \Itb\StaffRepository();
$staffRepository->createTableStaff();

for($i=0;$i<10;$i++) {

    $s = new \Itb\Staff();

    $s->setUserName($faker->firstName);
    $s->setPassword(password_hash($faker->password(6),PASSWORD_DEFAULT));
    $s->setPrivilege(0);

    $staffRepository->insertUser($s);
}

header("Location: index.php");
exit();




