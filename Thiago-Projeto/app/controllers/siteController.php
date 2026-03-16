<?php

require_once "Controller.php";

class SiteController extends Controller
{
    public function somosNos()
    {
        $this->render("site/somosNos", ["titulo" => "QUEM SOMOS"]);
    }
}