<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Itb\ProductRepository;
use Itb\AccountRepository;


$productRepository = new ProductRepository();

$productRepository->dropTableProducts();


$accountsRepository = new AccountRepository();

$accountsRepository->dropTableAccounts();

$staffRepository = new \Itb\StaffRepository();

$staffRepository->dropTableStaff();

header("Location: index.php");
exit();




