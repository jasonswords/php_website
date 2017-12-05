<?php


namespace Itb;


class LoginController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function processLoginAction()
    {
        $userName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $staffRepository = new StaffRepository();

        if(!empty($userName)) {
            $account = $staffRepository->getOneByUserName($userName);

            if (null == $account) {
               header("Location: index.php?action=loginError");
                exit();
            } else {
                if (password_verify($password, $account->getPassword())) {
                    if (1 == $account->getPrivilege()) {
                        $privilege = 'Administrator';
                    } else {
                        $privilege = 'Standard Account';
                   }
                    $_SESSION['username'] = 'Administrator';//$userName;
                    $_SESSION['privilege'] = 'Administrator';//$privilege;

                    header("Location: index.php");
                    exit();
               } else {
                    header("Location: index.php?action=loginError");
                    exit();
                }
            }
        }else{
            header("Location: index.php?action=loginError");
            exit();
        }
    }

    public function loginAction(){
        $template = 'login.html.twig';
        $argsArray = [
            'pageTitle' => 'Login'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function loginErrorAction(){

        $template = 'loginError.html.twig';
        $args = [
          'pageTitle' => 'Login Error'
        ];

        $html = $this->twig->render($template, $args);
        print $html;
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
}