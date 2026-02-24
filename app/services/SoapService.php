<?php
// app/services/SoapService.php

require_once __DIR__ . '/../models/User.php';

class SoapService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // simple greeting
    public function hello($name)
    {
        return "Hello, $name!";
    }

    // return user info by id
    public function getUser($id)
    {
        $user = $this->userModel->findById($id);
        if (!$user) {
            throw new SoapFault('Client', 'User not found');
        }
        return $user;
    }
}
