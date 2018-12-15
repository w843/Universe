<?
if($_COOKIE['login']!=NULL and $_COOKIE['pass']!=NULL){
    setcookie("login","",time()-1);
    setcookie("pass","",time()-1);
    header('Location: index.php');
}else{
    header('Location: index.php');
}
?>