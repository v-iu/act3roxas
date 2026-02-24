<?php
// app/controllers/AuthController.php

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        session_start();
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $errors = [];

            // basic validation
            if (empty($username) || strlen($username) < 3) {
                $errors[] = 'Username must be at least 3 characters.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email address is not valid.';
            }
            if (empty($password) || strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            }
            // check duplicate email
            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Email is already registered.';
            }

            if (count($errors) === 0) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                if ($this->userModel->create($username, $email, $hashed)) {
                    header('Location: /login');
                    exit;
                } else {
                    $errors[] = 'Registration failed due to a server error.';
                }
            }

            $data['error'] = implode('<br>', $errors);
            $this->view('auth/register', $data);
            return;
        }
        $this->view('auth/register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $errors = [];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }
            if (empty($password)) {
                $errors[] = 'Password cannot be empty.';
            }

            if (count($errors) === 0) {
                $user = $this->userModel->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: /dashboard');
                    exit;
                } else {
                    $errors[] = 'Invalid email/password combination.';
                }
            }

            $data['error'] = implode('<br>', $errors);
            $this->view('auth/login', $data);
            return;
        }
        $this->view('auth/login');
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}
