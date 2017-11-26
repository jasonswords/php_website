<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Itb\ProductRepository;
use Itb\VisitorRepository;

$faker = Faker\Factory::create();

// ---------  Populate Product Database  ----------

$productRepository = new ProductRepository();

$productRepository->createTableProducts();

$images = ["image1.jpg", "image2.jpg", "image3.jpg", "image4.jpg", "image5.jpg", "image6.jpg", "image7.jpg", "motor.jpg", "drone.jpg", "pilots.jpg"];
$images2 = ["goggles.jpg","futaba.jpg", "image2.jpg", "taranis.png", "image4.jpg","cup.jpg", "tshirt.jpg", "motor.jpg", "track.jpg", "motor2.jpg"];

for($i=0;$i<10;$i++){

    $p = new \Itb\Product();

    $p->setName($faker->firstName());
    $p->setImage($images2[$i]);
    $p->setPrice($faker->randomFloat());
    $p->setDescription($faker->sentence);

    $productRepository->insertProduct($p);
}

// ---------  Populate Accounts Database  ----------

$accountsRepository = new VisitorRepository();

$accountsRepository->createTableAccounts();

for($i=0;$i<10;$i++) {

    $a = new \Itb\Visitor();

    $a->setFirstName($faker->firstName);
    $a->setSecondName($faker->lastName);
    $a->setCountry($faker->country);
    $a->setEmail($faker->email);

    $accountsRepository->insertAccount($a);
}


//---------------Populate Staff Database-------------------


$staffRepository = new \Itb\StaffRepository();
$staffRepository->createTableStaff();

//--------------- Hard Code admin users -----------------------

$staff = new \Itb\Staff();

$staff->setUserName('staff');
$staff->setPassword(password_hash('staff', PASSWORD_DEFAULT));
$staff->setPrivilege(0);
$staffRepository->insertUser($staff);

$staff->setUserName('admin');
$staff->setPassword(password_hash('admin', PASSWORD_DEFAULT));
$staff->setPrivilege(1);
$staffRepository->insertUser($staff);



//----------------- Add random users ---------------------------

for($i=0;$i<10;$i++) {

    $s = new \Itb\Staff();

    $s->setUserName($faker->firstName);
    $s->setPassword(password_hash($faker->password(6),PASSWORD_DEFAULT));
    $s->setPrivilege(0);

    $staffRepository->insertUser($s);
}

//---------------Populate League Database-------------------

$leagueRepo = new \Itb\LeagueRepository();
$leagueRepo->createTableLeague();

for($i=0;$i<10;$i++){

    $l = new \Itb\League();

    $l->setName($faker->name);
    $l->setCountry($faker->country);
    $l->setDrone($images[$i]);
    $l->setPosition($faker->randomDigit);

    $leagueRepo->insertLeagueMember($l);
}

header("Location: index.php");
exit();




