<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
        $this->data['script']           = 'home.script.index';
        return $this->renderTo('home.index');
    }
}
