<?php


namespace Itb;


class ProcessingController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function processVisitorFormAction(){

        $firstName = filter_input(INPUT_POST, 'firstName');
        $secondName = filter_input(INPUT_POST, 'secondName');
        $country = filter_input(INPUT_POST, 'country');
        $email = filter_input(INPUT_POST, 'email');

            $v = new Visitor();
            $v->setFirstName($firstName);
            $v->setSecondName($secondName);
            $v->setCountry($country);
            $v->setEmail($email);
            $visitorRepository = new VisitorRepository();
            $visitorRepository->createTableAccounts();
            $visitorRepository->insertAccount($v);

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

    }

    public function deleteSession(){
        $_SESSION = [];

        if (ini_get('session.use_cookies')){
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        header("Location: index.php");

    }

    public function setupDatabaseAction(){
            include_once __DIR__ . '/../setup_Scripts/setupDatabase.php';
    }

    public function deleteDatabaseAction(){
            include_once __DIR__ . '/../setup_Scripts/dropTables.php';
    }

}