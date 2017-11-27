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

        header("Location: index.php?action=about");
        exit();
    }





    public function processProductAction(){

        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $imageName = basename($target_file);

                // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
                    // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
                    // Check file size
        if ($_FILES["fileToUpload"]["size"] > 50000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
                    // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
                    // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                die();
            }
        }

        $name = filter_input(INPUT_POST, 'name');
        $price = filter_input(INPUT_POST, 'price');
        $description = filter_input(INPUT_POST, 'description');
        $p = new Product();
        $p->setName($name);
        $p->setPrice($price);
        $p->setImage($imageName);
        $p->setDescription($description);
        $productRepository = new ProductRepository();
        $productRepository->createTableProducts();

        $productRepository->insertProduct($p);

        $id = $productRepository->getOneByName($name);


        if($id == null){
            header("Location: index.php?action=productError");
            exit();
        }
        else{
            header("Location: index.php?action=displaySingleProduct&id=<? $id >");
            exit();
        }


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