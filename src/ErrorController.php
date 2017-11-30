<?php

namespace Itb;

class ErrorController{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function errorAction(){
        $template = 'errorPage.html.twig';
        $argsArray = [
            'pageTitle' => 'Error'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function productErrorAction(){
        $template = 'productError.html.twig';
        $argsArray = [
            'pageTitle' => 'Product Error'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

    public function errorPageAction(){
        $template = 'errorPage.html.twig';
        $argsArray = [
            'pageTitle' => 'Error Page'
        ];
        $html = $this->twig->render($template, $argsArray);
        print $html;
    }

}