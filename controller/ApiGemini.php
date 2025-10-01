<?php
require_once __DIR__ . "/../utils/Controller.php";

class ApiGemini extends Controller
{
    public function handler()
    {
        require_once __DIR__ . "/../model/ApiGemini.php/handler.php";

        $handler = new GeminiHandler(
            null,
            'gemini-pro',
            'https://generativelanguage.googleapis.com/v1beta',
            30
        );

        $handler->handle();
    }
}
