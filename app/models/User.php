<?php
// app/models/User.php

class User extends Model
{
    public function create($username, $email, $password)
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT id, username, email, created_at FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query('SELECT id, username, email, created_at FROM users ORDER BY id DESC');
        return $stmt->fetchAll();
    }
}
