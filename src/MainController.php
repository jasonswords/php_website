<?php

namespace Itb;

class MainController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function indexAction(){

        $template = 'home.html.twig';
        $argsArray = [
            'pageTitle' => 'Home'
        ];

        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function galleryAction(){
        $dirName = 'images/';
        $images = glob($dirName."*.*");

        $template = 'gallery.html.twig';
        $argsArray = [
            'pageTitle' => 'Gallery',
            'images'  => $images
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function aboutAction(){
        $template = 'about.html.twig';
        $argsArray = [
            'pageTitle' => 'About'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function aboutConfirmAction($id){
        $visitorRepository = new VisitorRepository();
        $visitor = $visitorRepository->getOneById($id);

        $template = 'aboutConfirm.html.twig';
        $argsArray = [
            'pageTitle' => 'About',
            'visitor' => $visitor
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function errorAction(){
        $template = 'error.html.twig';
        $argsArray = [
            'pageTitle' => 'Product Error'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function setupDatabaseAction()
    {
        if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
            include_once __DIR__ . '/../setup_Scripts/setupDatabase.php';
        }
        else{
            $this->indexAction();
        }
    }

    public function deleteDatabaseAction(){
        if (isset($_SESSION['privilege']) && $_SESSION['privilege'] == 'Administrator') {
        include_once __DIR__ . '/../setup_Scripts/dropTables.php';
        }
        else{
            $this->indexAction();
        }
    }
}