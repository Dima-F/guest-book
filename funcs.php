<?php

function debug($data) {
    echo '<pre>' . print_r($data, 1) . '</pre>';
}

function registration():bool {
    global $pdo;
    $login =  !empty($_POST['login']) ? trim($_POST['login']) : '';
    $pass =  !empty($_POST['pass']) ? trim($_POST['pass']) : '';

    if(empty($login) || empty($pass)) {
        $_SESSION['errors'] = 'Login or password required';
        return false;
    }

    $res = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $res->execute([$login]);
    if($res->fetchColumn()) {
        $_SESSION['errors'] = 'user already exists';
        return false;
    }

    $pass = password_hash($pass, PASSWORD_DEFAULT);

    $res = $pdo->prepare("INSERT INTO users (login, pass) VALUES (?,?)");
    if($res->execute([$login, $pass])) {
        $_SESSION['success'] = 'successfull registration';
        return true;
    } else {
        $_SESSION['errors'] = 'registration error';
        return false;
    }
}

function login(): bool {
    global $pdo;

    $login =  !empty($_POST['login']) ? trim($_POST['login']) : '';
    $pass =  !empty($_POST['pass']) ? trim($_POST['pass']) : '';

    if(empty($login) || empty($pass)) {
        $_SESSION['errors'] = 'Login or password required';
        return false;
    }

    $res = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $res->execute([$login]);
    if(!$user = $res->fetch()) {
        $_SESSION['errors'] = 'wrong password or login';
        return false;
    }

    if(!password_verify($pass, $user['pass'])) {
        $_SESSION['errors'] = 'wrong password or login';
        return false;
    } else {
        $_SESSION['success'] = 'successfull login';
        $_SESSION['user']['name'] = $user['login'];
        $_SESSION['user']['id'] = $user['id'];
        return true;
    }
}


function save_message(): bool {
    if(!isset($_SESSION['user']['name']))  {
        $_SESSION['errors'] = 'You neet to authorize';
        return false;
    }

    $message = !empty($_POST['message']) ? trim($_POST['message']) : '';
    if(empty($message)) {
        $_SESSION['errors'] = 'Enter a message';
        return false;
    }

    global $pdo;

    $res = $pdo->prepare('INSERT INTO messages(name, message) VALUES (?,?)');
    if($res->execute([$_SESSION['user']['name'], $message])) {
        $_SESSION['success']  = 'Message added';
        return true;
    } else {
        $_SESSION['errors'] = 'Database error';
    }

}

function get_messages(): array {
    global $pdo;
    $res = $pdo->query('SELECT * FROM messages');
    return $res->fetchAll();
}