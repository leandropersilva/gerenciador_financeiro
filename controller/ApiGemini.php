<?php
require_once __DIR__ . "/../utils/Controller.php";

class ApiGemini extends Controller
{
    public function handler()
    {
        require_once __DIR__ . "/../public/apiGemini/handler.php";

        $handler = new GeminiHandler(
            '',
            'gemini-2.0-flash',
            'https://generativelanguage.googleapis.com/v1beta',
            40
        );
        $handler->handle();
    }
}