<?php
require_once __DIR__ . "/../utils/Controller.php";

class Home extends Controller
{
    public function dashboard()
    {
        $this->loadMV('/home/dashboard', 'dashboard');
    }
}