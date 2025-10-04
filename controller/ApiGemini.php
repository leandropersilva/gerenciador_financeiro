<?php
require_once __DIR__ . "/../utils/Controller.php";

class ApiGemini extends Controller
{
    public function handler()
    {
        require_once __DIR__ . "/../model/ApiGemini/handler.php";

        $handler = new GeminiHandler(
            'AIzaSyCvHbeSVLsWydTxl6ALoXfL7bM7NLy17lA',
            'gemini-2.0-flash',
            'https://generativelanguage.googleapis.com/v1beta',
            40
        );

        $handler->handle();
    }
}