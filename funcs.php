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