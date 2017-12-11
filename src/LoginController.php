<?php


namespace Itb;




class LoginController
{
    private $twig;
    private $logger;

    public function __construct($twig, $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function processLoginAction()
    {
        $userName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $staffRepository = new StaffRepository();

        if(!empty($userName)) {
            $account = $staffRepository->getOneByUserName($userName);

            if (null == $account) {
                $this->logger->info('Failed Login Attempt using user name '.$userName);
                header("Location: index.php?action=loginError");
                exit();
            } else {
                if (password_verify($password, $account->getPassword())) {
                    if (1 == $account->getPrivilege()) {
                        $privilege = 'Administrator';
                    } else if(0 == $account->getPrivilege()) {
                        $privilege = 'Staff Account';
                   }
                    $_SESSION['username'] = $userName;
                    $_SESSION['privilege'] = $privilege;

                    $this->logger->info($_SESSION['username'].' with privilege level '.$_SESSION['privilege'].' has just logged in');

                    header("Location: index.php");
                    exit();
               } else {
                    $this->logger->info('Incorrect password used by '.$userName. ' attempting to log in');
                    header("Location: index.php?action=loginError");
                    exit();
                }
            }
        }else{
            $this->logger->error('Empty user name used to try log in');
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
        $this->logger->info($_SESSION['username'].' with privilege level '.$_SESSION['privilege'].' has just logged out');
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