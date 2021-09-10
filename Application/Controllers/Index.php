<?php

namespace Application\Controllers;

use Core\Controller;

//TODO implement Controller, check how we use it
class Index extends Controller
{
    public function indexAction()
    {
        $this->returnError('Invalid path');
    }
}