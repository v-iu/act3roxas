<?php
// app/controllers/HomeController.php

class HomeController extends Controller
{
    private $userModel;

    public function __construct()
    {
        session_start();
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $data['user'] = $this->userModel->findById($_SESSION['user_id']);
        $this->view('home/dashboard', $data);
    }

    public function users()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $data['users'] = $this->userModel->getAll();
        $this->view('home/userlist', $data);
    }
}
