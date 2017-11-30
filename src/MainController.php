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

    public function aboutConfirmAction(){
        $template = 'aboutConfirm.html.twig';
        $argsArray = [
            'pageTitle' => 'About'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function siteMapAction(){
        $template = 'siteMap.html.twig';
        $argsArray = [
            'pageTitle' => 'Site Map'
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