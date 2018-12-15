<?
require('config.php');

if($_COOKIE['login']!=NULL and $_COOKIE['pass']!=NULL){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $_COOKIE['login'])){
        header('Location: logout.php');
        die();
    }else{
        $users = mysql_fetch_assoc(mysql_query("SELECT * FROM `b_users` WHERE `login`='".$_COOKIE['login']."' LIMIT 1"));
        
        if($users['pass'] == ''){
            header('Location: logout.php');
            die();
        }else{
            if($_COOKIE['pass'] == $users['pass']){
                if($users['ban'] == 1){
                    echo "<script>alert('Авторизуйтесь для получение информации!')</script>";
                    echo '<meta http-equiv="refresh" content="1; url=logout.php">';
                    die();
                }
            }else{
                header('Location: logout.php');
                die();
            }
        }
    }
}else{
    header('Location: index.php');
    die();
}

echo '<meta charset="UTF-8">';
echo 'Вы авторизовались как: '.$users['login']."<br />";
echo 'Ваша почта: '.$users['email']."<br />";
echo 'Ваша первая почта: '.$users['first_email']."<br />";
echo 'Код подтверждения операций: '.$users['code']."<br />";
echo 'Баланс: '.$users['money']."<br />";
echo 'Дата создания аккаунта: '.$users['data_reg']."<br />";
echo 'Последняя дата захода на аккаунт: '.$users['data_last']."<br />";
echo 'IP с которого был создан аккаунт: '.$users['ip_reg']."<br />";
echo 'Последний IP на аккаунте: '.$users['ip_last']."<br />";
echo '<a href="logout.php">Выйти</a>';
?>