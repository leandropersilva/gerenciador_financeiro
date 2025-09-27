<?php
require_once __DIR__ . "/../utils/Model.php";

class Dashboard extends Model
{
     public function getData($params = [])
    {
        $dados = 123;
        return $this->validarDados($dados, 'nada encontrado');
    }
}
