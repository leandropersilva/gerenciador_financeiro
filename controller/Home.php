<?php
require_once __DIR__ . "/../utils/Controller.php";

class Home extends Controller
{
    protected function getModelParams()
    {
        return ['admin' => 1];
    }

    public function dashboard()
    {
        $this->loadMV('/home/dashboard', 'dashboard', ['admin'=>1]);
    }
}