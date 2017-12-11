<?php

namespace Itb;

class VisitorController{

    private $twig;
    private $logger;

    public function __construct($twig, $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function visitorAction(){
        $visitorRepository = new VisitorRepository();
        $visitors = $visitorRepository->getAllVisitors();

        if(empty($visitors)){
            $heading = 'No Visitors Data Available';
        }else{
            $heading = 'Visitors Table';
        }
        $template = 'visitor.html.twig';
        $args = [
            'pageTitle' => 'Visitor',
            'heading' => $heading,
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

    public function processVisitorUpdateAction(){
        $id = filter_input(INPUT_POST, 'id');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $secondName = filter_input(INPUT_POST, 'secondName');
        $country = filter_input(INPUT_POST, 'country');
        $email = filter_input(INPUT_POST, 'email');
        $visitorRepository = new VisitorRepository();
        $visitorRepository->updateVisitorTable($id, $firstName, $secondName, $country, $email);
        header("Location: index.php?action=viewVisitor&id=<? $id >");
        exit();
    }

    public function deleteVisitorAction($id){
        $visitorRepository = new VisitorRepository();
        $name = $visitorRepository->getNameById($id);
        $this->logger->info('UserName: '.$_SESSION['username'].' has deleted '.$name.' from the visitors table');
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
        $visitorRepository->insertVisitor($v);

        $id = $visitorRepository->getOneByName($firstName);

        header("Location: index.php?action=aboutConfirm&id=<? $id >");
        exit();
    }
}