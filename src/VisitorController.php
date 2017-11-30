<?php

namespace Itb;

class VisitorController{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function visitorAction(){
        $visitorRepository = new VisitorRepository();
        $visitors = $visitorRepository->getAllVisitors();
        $template = 'visitor.html.twig';
        $args = [
            'pageTitle' => 'Visitor',
            'visitors' => $visitors
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function editVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $visitor = $visitorRepository->getOneById($id);
        $template = 'editVisitor.html.twig';
        $args = [
            'pageTitle' => 'Edit Visitor',
            'visitor' => $visitor
        ];
        $html = $this->twig->render($template, $args);
        print $html;
    }

    public function processVisitorUpdateAction($id, $firstName, $secondName, $country, $email){
        $visitorRepository = new VisitorRepository();
        $visitorRepository->updateVisitorTable($id, $firstName, $secondName, $country, $email);
        header("Location: index.php?action=visitor");
        exit();
    }

    public function deleteVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $visitorRepository->deleteOneVisitor($id);
        header("Location: index.php?action=visitor");
        exit();
    }

    public function createVisitorAction(){
        $template = 'createVisitor.html.twig';
        $argsArray = [
            'pageTitle' => 'Create Account'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function viewVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $visitor = $visitorRepository->getOneById($id);
        $template = 'viewSingleVisitor.html.twig';
        $args =[
            'pageTitle' => 'View Visitor',
            'visitor' => $visitor
        ];
        $html = $this->twig->render($template, $args);
        print $html;
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

        header("Location: index.php?action=aboutConfirm");
        exit();
    }
}