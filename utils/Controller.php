<?php

abstract class Controller
{
    public function loadView($view, $dados = [])
    {
        if (file_exists(__DIR__ . "/../view" . $view . '.php')) {
            require_once(__DIR__ . "/../view/" . $view . '.php');
        }
        extract($dados);
    }
    
    public function loadModel($model, $dados = [])
    {
        if (file_exists(__DIR__ . "/../model/" . $model . '.php')) {
            require_once(__DIR__ . "/../model/" . $model . '.php');
        }
        extract($dados);
    }

    public function loadMV($view, $model, $dados = []){
        $this->loadModel($model, $dados);
        $this->loadView($view);
    }

    /* 
        o que um controller deve ter:
            - Requisição no banco de dadoss (CRUD)
            - Montar a view com os dados X
            - Carregar apenas a view X
            - Carregar apenas o model X
    */
}
