<?php
// app/controllers/ApiHomeController.php

class ApiHomeController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    private function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // expecting ?user_id= or header token in future
    public function dashboard()
    {
        // simple access by query param for demo
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            $this->json(['success' => false, 'error' => 'user_id required'], 400);
        }
        $user = $this->userModel->findById($userId);
        if (!$user) {
            $this->json(['success' => false, 'error' => 'not found'], 404);
        }
        $this->json(['success' => true, 'user' => $user]);
    }

    public function users()
    {
        $users = $this->userModel->getAll();
        $this->json(['success' => true, 'users' => $users]);
    }
}
