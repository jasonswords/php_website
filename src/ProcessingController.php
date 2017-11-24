<?php


namespace Itb;


class ProcessingController
{
    private $twig;
    private $username;

    public function __construct($twig, $username)
    {
        $this->twig = $twig;
        $this->username = $username;
    }

    public function processAccountFormAction(){

        $firstName = filter_input(INPUT_POST, 'firstName');
        $secondName = filter_input(INPUT_POST, 'secondName');
        $country = filter_input(INPUT_POST, 'country');
        $user1 = filter_input(INPUT_POST, 'user1');
        $user2 = filter_input(INPUT_POST, 'user2');
        $password1 = filter_input(INPUT_POST, 'password1');
        $hashed = password_hash(filter_input(INPUT_POST, 'password2'), PASSWORD_DEFAULT);
        if(($user1 == $user2) && (password_verify($password1,$hashed)))
        {
            $m = new Account();
            $m->setFirstName($firstName);
            $m->setSecondName($secondName);
            $m->setCountry($country);
            $m->setPassword($hashed);
            $m->setUser($user1);
            $accountRepository = new AccountRepository();
            $accountRepository->createTableAccounts();
            $accountRepository->insertAccount($m);
        }
        else
        header("Location: index.php?action=error1");
        exit();
    }

    public function processProductAction(){
        $name = filter_input(INPUT_POST, 'name');
        $price = filter_input(INPUT_POST, 'price');
        $description = filter_input(INPUT_POST, 'description');
        $image = filter_input(INPUT_POST, 'image');
        $p = new Product();
        $p->setName($name);
        $p->setPrice($price);
        $p->setImage($image);
        $p->setDescription($description);
        $productRepository = new ProductRepository();
        $productRepository->createTableProducts();
        $productRepository->insertProduct($p);
    }

    public function displayStaffAction(){
        $staff = new StaffRepository();
        $staffMember = $staff->getAllStaff();
        $template = 'staff.html.twig';
        $argsArray = [
            'pageTitle' => 'Staff',
            'staff'  => $staffMember
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function createStaffAction(){
        $template = 'createStaff.html.twig';
        $argsArray = [
            'pageTitle' => 'Create Staff'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function editStaffAction($id){
        $staff = new StaffRepository();
        $staffMember = $staff->getOneById($id);
        $template = 'editStaff.html.twig';
        $args = [
            'pageTitle' => 'Edit Staff',
            'staff' => $staffMember
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function deleteStaffAction($id){
        $staff = new StaffRepository();
        $staff->deleteOneUser($id);
        header("Location: index.php?action=staff");
        exit();
    }

    public function processStaffUpdateAction($id, $userName, $password, $privilege){
        $staffRepository = new StaffRepository();
        $staffRepository->createTableStaff();
        $staffRepository->updateStaffTable($id, $userName, $password, $privilege);
        header("Location: index.php?action=displayStaff");
        exit();
    }

    public function setupDatabaseAction(){
            include_once __DIR__ . '/../setup_Scripts/setupDatabase.php';
    }

    public function deleteDatabaseAction(){
            include_once __DIR__ . '/../setup_Scripts/dropTables.php';
    }

}