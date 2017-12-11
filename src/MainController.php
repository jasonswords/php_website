<?php

namespace Itb;

class MainController
{
    private $twig;
    private $logger;

    public function __construct($twig, $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
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
        $this->logger->info('Database has been created by '.$_SESSION['username']);
        include_once __DIR__ . '/../setup_Scripts/setupDatabase.php';
    }

    public function deleteDatabaseAction(){
        $this->logger->info('Database has been deleted by '.$_SESSION['username']);
        include_once __DIR__ . '/../setup_Scripts/dropTables.php';
    }
}