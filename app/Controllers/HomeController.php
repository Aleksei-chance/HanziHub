<?php

namespace App\Controllers;

class HomeController
{
    public function index(): string
    {
        return "Welcome to the Home Page!";
    }

    public function about(): string
    {
        return "About HanziHub";
    }
}
