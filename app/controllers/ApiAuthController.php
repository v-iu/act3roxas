<?php
// app/controllers/ApiAuthController.php

class ApiAuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        // no session for APIs, rely on tokens if needed later
        $this->userModel = $this->model('User');
    }

    private function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $username = trim($input['username'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $errors = [];

        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email address is not valid.';
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Email is already registered.';
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        if ($this->userModel->create($username, $email, $hashed)) {
            $this->json(['success' => true, 'message' => 'Registration successful']);
        } else {
            $this->json(['success' => false, 'errors' => ['Registration failed']], 500);
        }
    }

    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (empty($password)) {
            $errors[] = 'Password cannot be empty.';
        }
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // for simplicity, return user id as token
            $this->json(['success' => true, 'user' => ['id' => $user['id'], 'username' => $user['username'], 'email' => $user['email']]]);
        } else {
            $this->json(['success' => false, 'errors' => ['Invalid email/password.']], 401);
        }
    }
}
