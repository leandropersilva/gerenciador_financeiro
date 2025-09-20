<?php
abstract class Controller
{

    private function verificarArquivo($arquivo, $pasta)
    {
        return file_exists(__DIR__ . "/../{$pasta}/{$arquivo}.php");
    }

    private function loadView($view, $dados = [])
    {
        extract($dados);
        require_once(__DIR__ . "/../view/" . $view . '.php');
    }

    private function loadModel($model, $params = []) {
        require_once(__DIR__ . "/../model/" . $model . '.php');

        $model_obj = new $model();
        $dados = $model_obj->getData();

        return $dados;
    }

    public function loadMV($view, $model, $dados = [])
    {
        if (!$this->verificarArquivo($model, 'model')) exit('Model não encontrado');
        if (!$this->verificarArquivo($view, 'view')) exit('View não encontrado');
        $this->loadModel($model, $dados);
        $this->loadView($view);
    }
}
