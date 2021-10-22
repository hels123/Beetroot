<?php

$login = $_POST['login'];
$pass = $_POST['password'];


class User
{
    private $login;
    private $pass;

    public function __construct(string $login, string $pass)
    {
        $this->login = $login;
        $this->pass = $pass;
    }

    public function login()
    {
        require_once '../connection/connectionSetting.php';
        $smtp = $pdo->prepare("SELECT * FROM users WHERE username=?");
        $smtp->execute([$this->login]);
        $result = $smtp->fetch();
        if(password_verify($this->pass, $result['password'])){
            session_start();
            $_SESSION['currentUser'] = $this->login;
            echo "Приятных покупок, $this->login!".PHP_EOL;
            require_once '../market/market.php';
        }else{
            echo "Ошибка входа. Логин или пароль неверен.";
        }
    }
    public function registration()
    {
        require_once '../connection/connectionSetting.php';
        $smtp = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $smtp->execute([$this->login]);
        $loginFromDB = $smtp->fetch();
        $hashPassword = password_hash($this->pass, PASSWORD_DEFAULT);

        if(!$loginFromDB){
            $smtp = $pdo->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
            $smtp->execute([$this->login, $hashPassword]);
            echo "Регистрация успешно завершена.";
        }else{
            echo "Невозможно создать пользователя, такой логин уже существует.";
        }
    }
}