<?
require('config.php');

if($_COOKIE['login']!=NULL and $_COOKIE['pass']!=NULL){
    header('Location: index.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];
    $mail = $_POST['mail'];
    
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if($login==NULL or $pass==NULL or $pass2==NULL or $mail==NULL){
        header('Location: index.php?err=Не все поля заполнены!');
    }else{
        if(!preg_match("/^[a-zA-Z0-9]+$/",$login)){
            header('Location: index.php?err=В логине может быть только латиница и цифры!');
        }else{
            if($pass != $pass2){
                header('Location: index.php?err=Пароли не совподают! Проверьте правильность ввода!');
            }else{
                if(!preg_match("/^[a-zA-Z0-9]+$/",$pass)){
                    header('Location: index.php?err=В пароле может быть только латиница и цифры!');
                }else{
                    if(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/",$mail)){
                        header('Location: index.php?err=Введите настоящий e-mail!');
                    }else{
                        $Login_log = @mysql_query("SELECT * FROM `b_users` WHERE `login`= '".$login."'");
                        if(mysql_num_rows($Login_log) > 0){
                            header('Location: index.php?err=Такой пользователь уже сущетсвует!');
                        }else{
                            if(strlen($login)>=4 and strlen($login)<=15 and strlen($pass)>=6 and strlen($pass)<=15){
                                if(strlen($mail)>=6 and strlen($mail)<=30){
                                    $pass = md5(base64_encode($pass));
                                    $date = date("Y-m-d H:i:s");
                                    mysql_query("INSERT INTO `b_users` SET `login`='".$login."', `pass`='".$pass."', `email`='".$mail."', `first_email`='".$mail."', `data_reg`='".$date."', `ip_reg`='".$ip."';");
                                    header('Location: index.php?suc=1');
                                }else{
                                    header('Location: index.php?err=Введите настоящий e-mail! Больше 5 и меньше 30 символов!');
                                }
                            }else{
                                header('Location: index.php?err=Логин должен быть больше 3 и меньше 16 символов!/Пароль должен быть больше 5 и меньше 16 символов!');
                            }
                        }
                    }
                }
            }
        }
    }
}
?>