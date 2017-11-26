<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Itb\ProductRepository;
use Itb\VisitorRepository;

$productRepository = new ProductRepository();
$productRepository->dropTableProducts();


$accountsRepository = new VisitorRepository();
$accountsRepository->dropTableAccounts();

$staffRepository = new \Itb\StaffRepository();
$staffRepository->dropTableStaff();

$leagueReppo = new \Itb\LeagueRepository();
$leagueReppo->dropTableLeague();

header("Location: index.php");
exit();




