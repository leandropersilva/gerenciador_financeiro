<?php
abstract class Controller
{

    private function verificarArquivo($arquivo, $pasta)
    {
        return file_exists(__DIR__ . "/../{$pasta}/{$arquivo}.php");
    }

    private function loadView($view, $dados = [])
    {
        if (is_array($dados)) extract($dados);

        require_once(__DIR__ . "/../utils/html_components/top.html");
        require_once(__DIR__ . "/../view/" . $view . '.php');
        require_once(__DIR__ . "/../utils/html_components/bottom.html");
    }

    private function loadModel($model, $params = [])
    {
        require_once(__DIR__ . "/../model/" . $model . '.php');

        $model_obj = new $model();
        $dados = $model_obj->getAll($params);
        return $dados;
    }

    protected function getModelParams()
    {
        return [];
    }

    public function loadMV($view, $model)
    {
        if (!$this->verificarArquivo($model, 'model')) exit('Model não encontrado');
        if (!$this->verificarArquivo($view, 'view')) exit('View não encontrado');
        $params = $this->getModelParams();
        $this->loadView($view, $this->loadModel($model, $params));
    }
}
