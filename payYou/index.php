<?
require('config.php');

if($_COOKIE['login']!=NULL and $_COOKIE['pass']!=NULL){
    header('Location: lk.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if($login==NULL or $pass==NULL){
        $err = "<center><span style='color: red;'>Не все поля заполнены!</span></center>";
    }else{
        if(!preg_match("/^[a-zA-Z0-9]+$/",$login)){
            $err = '<center><span style="color: red;">В логине может быть только латиница и цифры!</span></center>';
        }else{
            $users = mysql_fetch_assoc(mysql_query("SELECT * FROM `b_users` WHERE `login`='".$login."' LIMIT 1"));
            
            if($users['pass'] == ''){
                $err = '<center><span style="color: red;">Пользователь не найден! Проверьте правильность ввода!</span></center>';
            }else{
                $pass = md5(base64_encode($pass));
                if($pass == $users['pass']){
                    if($users['ban'] == 1){
                        $err = '<center><span style="color: red;">Ваш аккаунт заблокирован!<br />Вы были заблокированы Администратором: '.$users['ban_kem'].'.<br />По причине: '.$users['ban_why'].'<br /></span></center>';
                    }else{
                        $date = date("Y-m-d H:i:s");
                        mysql_query("UPDATE `b_users` SET `data_last` = '".$date."',`ip_last` = '".$ip."' WHERE `b_users`.`login` = '".$login."';");
                        setcookie("login","$login");
                        setcookie("pass","$pass");
                        echo '<center><span style="color: green;">Успешно авторизован! Идёт перенаправление на главную страницу...</span></center>';
                        echo '<meta http-equiv="refresh" content="2; url=index.php">';
                    }
                }else{
                    $err = '<center><span style="color: red;">Пароль неправильный! Проверьте правильность ввода!</span></center>';
                }
            }
        }
    }
}

if($_GET['err'] != NULL){
    $rr = str_replace("/", "<br />", htmlspecialchars($_GET['err']));
    echo '<center><span style="color: red;">'.$rr.'</span></center>';
}

if($_GET['suc'] == 1){
    echo '<center><span style="color: green;">Вы успешно зарегестрировали свой аккаунт!<br />Теперь вы можете авторизоваться!</span></center>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Snowbank</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="shortcut icon" href="http://www.designgurus.in/payYou/assets/images/favicon.ico">

    <!--All css  are here-->

    <!--Bootstrap css here-->
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <!--Font-Awsome css here-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!--Owl-carousel css here-->
    <link rel="stylesheet" href="assets/plugins/owl/owl.carousel.css">
    <link rel="stylesheet" href="assets/plugins/owl/owl.theme.css">
    <link rel="stylesheet" href="assets/plugins/owl/owl.transitions.css">

    <!--Custon css here-->
    <link rel="stylesheet" href="assets/css/custom.css">

    <!--Scroll Animation - aos-master css here-->
    <link rel="stylesheet" href="assets/plugins/aos-master/aos.css"/>

    <!--Responsive css here-->
    <link rel="stylesheet" href="assets/css/responsive.css">

</head>
<body>
<div class="se-pre-con"></div>

<!-- NAVIGATION -->
<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header logoo">
            <button id="tog-btn" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#home-section"><img class="img-responsive" src="assets/images/logo.png"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right hidden-xs hidden-sm">
                <li class="gold"><a href="javascript:;"><button class="  btn btn-gradient outline-button" style="margin-bottom: 0px;" data-toggle="modal" data-target="#pop-register"><div style="background: #1C589B;transition: all 0.3s;">Register</div></button></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right visible-xs visible-sm">
                <li class="gold" style="border-top: 1px solid rgba(255,255,255,0.1)"><a href="javascript:;" data-toggle="modal" data-target="#pop-register">Register</a></li>
            </ul>
        </div>

    </div>
</nav>
<!-- NAVIGATION -->


<!-- HEADER -->
<section id="home-section" class="header-bg">
    <div class="gradient sectionP60 header-pad">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12 header-text sectionP60">
                            <h1 class="rL white">Your money is in safe</h1>
                            <p class="rL blue-L">Snowbank is a new school structure which will create a perfect vision on reality of transactions.</p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                            <div class="login-div centered" data-aos="fade-up" data-aos-duration="1000">
                                <div class="head">
                                    <h3 class="purple oR m0">LOGIN</h3>
                                    <p class="light oR">Enter your credentials to login.</p>
                                    <?=$err?>
                                </div>
                                <form method="POST">
                                    <div class="body">
                                        <div class="input-box">
                                            <input name="login" placeholder="Username" type="text" required>
                                            <span style="position: absolute"><i class="fa fa-user"></i></span>
                                        </div>
                                        <div class="input-box">
                                            <input name="pass" placeholder="Password" type="password" required>
                                            <span style="position: absolute"><i class="fa fa-key"></i></span>
                                        </div>
                                    </div>
                                    <div class="foot">
                                        <a href="javascript:;" class="forgot pull-left"><small>Forgot your password?</small></a>
                                        <button class="btn btn-gradient W100 pull-right" data-aos="zoom-in-up" data-aos-duration="800">Login!</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- HEADER -->

<!-- About -->
<section id="about-section" class="grey-bg" style="padding-bottom: 60px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="services-div margin-minus" data-aos="fade-up" data-aos-duration="1000">
                    <div class="col-md-3 col-sm-6 col-xs-12 text-center br service-hover">
                        <div class="service" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service-icon">
                                <i class="fa fa-puzzle-piece"></i>
                            </div>
                            <div class="service-desc">
                                <h4 class="blue oB">For Developers</h4>
                                <p class="light oR">We are glad to invite talented developers in our team, if you are ready to work for the future, not for the present!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 text-center br b0 service-hover">
                        <div class="service" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service-icon">
                                <i class="fa fa-paint-brush"></i>
                            </div>
                            <div class="service-desc">
                                <h4 class="blue oB">For Designers</h4>
                                <p class="light oR">We are glad to invite the best designers to our projects in online banking. Let`s create a beautiful future together!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 text-center br b0 service-hover">
                        <div class="service" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service-icon">
                                <i class="fa fa-hourglass-2"></i>
                            </div>
                            <div class="service-desc">
                                <h4 class="blue oB">Save Time</h4>
                                <p class="light oR">Time is priceless. We save your time, we keep your trust. In God we belive,in love we live? Trust in us, don`t waste your time! </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 text-center br service-hover">
                        <div class="service" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service-icon">
                                <i class="fa fa-dollar"></i>
                            </div>
                            <div class="service-desc">
                                <h4 class="blue oB">Save Money</h4>
                                <p class="light oR">Money is a matter of exchange in world trade. Don`t lose yourself in a chaos. Get more money, make your dream real!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 text-center">
                    <div id="wwa">
                        <div class="heading-text" data-aos="fade-up" data-aos-duration="1000">
                           <span class="gold-gradient-color">WHO WE ARE?</span>
                        </div>
                        <p class="light oR" data-aos="fade-up" data-aos-duration="1000">We are the team of leaders of a public opinion. We don`t like a system which was created to enslave people and forget about human qualities, only respecting for budget or social status. I am the one who will try to change it, let`s try to do something for us?</p>
                        <button class="btn btn-gradient outline-button mtb20" data-toggle="modal" data-target="#pop-about" data-aos="zoom-in-up" data-aos-duration="800"><div style="background: #f1f1f1;transition: all 0.3s"><span>Read More About Us</span></div></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About -->

<!-- Why Choose Us -->
<section id="why-us-section" class="sectionP60">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-5 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12">
                    <div class="responsive rsb"  data-aos="fade-right" data-aos-duration="1000">
                        <div class="heading-text">
                            <span class="blue-gradient-color">Why choose Snowbank?</span>
                        </div>
                        <p class="light oR">Unique system of transactions and cooperation with the main stock exchanges</p>
                        <br>
                        <p class="light oR">
                            Credit confidence will make your stay on a site cozy and comfortable. Leave care for us, do your own business. 
                        </p>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
                    <div class="video-embed pull-right" data-aos="fade-left" data-aos-duration="1000">
                        <div class="thumb">
                             <span><i class="gold-gradient-color fa fa-youtube-play"></i></span>
                        </div>
                        <iframe width="533" height="300" src="https://www.youtube.com/embed/2LeOH9AGJQM?rel=0" frameborder="0" allowfullscreen></iframe>
                        <!--<iframe src="https://player.vimeo.com/video/202406936?title=0&amp;byline=0&amp;portrait=0&amp;color=FDA10E&amp;autoplay=0" width="100%" height="300" autoplay="0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></iframe>-->
                    </div>
                   
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Choose Us -->

<!-- How We Work -->
<section id="how-section" class="sectionP60 grey-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-md-offset-0 col-sm-10 col-xs-12">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <div class="heading-text">
                            <span class="blue-gradient-color">How we work?</span>
                        </div>
                        <p class="light rL" style="font-size: 16px;">To get access to a site, you need to pay a fee, to deposit a credit confidence. We trust in your intentions, you trust in our impartiality.</p>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 p0 sectionP40">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="service2" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service2-icon">
                                <i class="gold-gradient-color fa fa-lightbulb-o"></i>
                            </div>
                            <div class="service2-desc">
                                <h4 class="blue oB">Think</h4>
                                <p class="light oR">Think how to make your life and life of our people better every day!</p>
                                <a href="javascript:;" class="gold" data-toggle="modal" data-target="#pop-read-more1">Read more...</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="service2" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service2-icon">
                                <i class="gold-gradient-color fa fa-pencil"></i>
                            </div>
                            <div class="service2-desc">
                                <h4 class="blue oB">Create</h4>
                                <p class="light oR">Create topics for discussion and let people judge how to help each other properly!</p>
                                <a href="javascript:;" class="gold" data-toggle="modal" data-target="#pop-read-more2">Read more...</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="service2" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service2-icon">
                                <i class="gold-gradient-color fa fa-money"></i>
                            </div>
                            <div class="service2-desc">
                                <h4 class="blue oB">Sell</h4>
                                <p class="light oR">Sell your capabilities and abilities more expensive then others!</p>
                                <a href="javascript:;" class="gold" data-toggle="modal" data-target="#pop-read-more3">Read more...</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="service2" data-aos="zoom-in" data-aos-duration="1000">
                            <div class="service2-icon">
                                <i class="gold-gradient-color fa fa-bullhorn"></i>
                            </div>
                            <div class="service2-desc">
                                <h4 class="blue oB">Earn</h4>
                                <p class="light oR">Earn money for your deals, do it clearly, make people trust you!</p>
                                <a href="javascript:;" class="gold" data-toggle="modal" data-target="#pop-read-more4">Read more...</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- How We Work -->

<!-- Number Counter -->
<section class="counter-bg">
    <div class="sectionP40 blue-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12">
                        <div class="responsive" data-aos="fade-right" data-aos-duration="1000">
                            <div class="heading-text">
                                <span class="gold-gradient-color">Little bit of stats.</span>
                            </div>
                            <p class="light2 oL" style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                            <button class="btn btn-gradient outline-button mtb20 hidden-sm hidden-xs"  data-aos="zoom-in-up" data-aos-duration="800"><div  style="background: #091D48;transition: all 0.3s">Join Now</div></button>
                        </div>
                    </div>
                    <div id="counter" class="col-md-5 col-sm-12 col-xs-12 pull-right resPad0">
                        <div class="col-md-6 col-sm-3 col-xs-6 br bb">
                            <div class="numbers text-center" data-aos="zoom-in" data-aos-duration="1000">
                                <span class="numscroller rB gold-gradient-color" data-min='0000' data-max='12456' data-delay='1' data-increment='25'>00</span>
                                <p class="white oR">Displays</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6 bb">
                            <div class="numbers text-center" data-aos="zoom-in" data-aos-duration="1000">
                                <span class="numscroller rB gold-gradient-color" data-min='0000' data-max='4567' data-delay='1' data-increment='25'>00</span>
                                <p class="white oR">Bank Lovers</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6 br">
                            <div class="numbers text-center" data-aos="zoom-in" data-aos-duration="1000">
                                <span class="numscroller rB gold-gradient-color" data-min='0000' data-max='4343' data-delay='1' data-increment='25'>00</span>
                                <p class="white oR">Followers</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="numbers text-center" data-aos="zoom-in" data-aos-duration="1000">
                                <span class="numscroller rB gold-gradient-color" data-min='0000' data-max='134' data-delay='1' data-increment='25'>00</span>
                                <p class="white oR">Haters</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Number Counter -->

<!-- What Can We Offer -->
<section id="offers-section" class="hiw-bg">
    <div class="sectionP60 grey-bg-o">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 text-center">
                        <div data-aos="fade-up" data-aos-duration="1000">
                            <div class="heading-text">
                                <span class="gold-gradient-color">What we can offer?</span>
                            </div>
                            <p class="light oR" style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                        </div>
                    </div>
                    <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12 p0 sectionP40">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div data-aos="fade-up" data-aos-duration="1000">
                                <img class="img-responsive centered" src="assets/images/laptop.png"/>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 col-xs-12 sectionP20 pull-right">
                            <div class="acordian gradient-accordian" data-aos="fade-up" data-aos-duration="1000">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 pull-right">
                                        <div class="acordian-desc res-txt-center">
                                            <i class="fa fa-heart-o"></i><span class="rM">Easy to Use</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="acordian-body res-txt-center">
                                            <span class="white rL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua, Ut enim ad minim.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="acordian active gradient-accordian" data-aos="fade-up" data-aos-duration="1000">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 pull-right">
                                        <div class="acordian-desc res-txt-center">
                                            <i class="fa fa-paint-brush"></i><span class="rM">Ultimate Interface</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="acordian-body res-txt-center">
                                            <span class="white rL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua, Ut enim ad minim.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="acordian gradient-accordian" data-aos="fade-up" data-aos-duration="1000">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 pull-right">
                                        <div class="acordian-desc res-txt-center">
                                            <i class="fa fa-rocket"></i><span class="rM">Crazy Fast</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="acordian-body res-txt-center">
                                            <span class="white rL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua, Ut enim ad minim.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="acordian gradient-accordian" data-aos="fade-up" data-aos-duration="1000">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 pull-right">
                                        <div class="acordian-desc res-txt-center">
                                            <i class="fa fa-pencil"></i><span class="rM">Easy to Edit</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="acordian-body res-txt-center">
                                            <span class="white rL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua, Ut enim ad minim.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- What Can We Offer -->

<!-- Our Best Features -->
<section id="features-section" class="sectionP60">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 text-center">
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <div class="heading-text">
                            <span class="gold-gradient-color">Our best features.</span>
                        </div>
                        <p class="light oR" style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                    </div>
                </div>
                <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12 p0 sectionP60">
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="img" data-aos="fade-right" data-aos-duration="1000">
                            <img class="img-responsive centered" src='assets/images/mobile.png'/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 pull-right">
                        <div class="features">
                            <ul class="ul-style">
                                <li class="oR light" data-aos="fade-left" data-aos-duration="1000"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></li>
                                <li class="oR light" data-aos="fade-left" data-aos-duration="1000"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></li>
                                <li class="oR light" data-aos="fade-left" data-aos-duration="1000"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></li>
                                <li class="oR light" data-aos="fade-left" data-aos-duration="1000"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></li>
                                <li class="oR light" data-aos="fade-left" data-aos-duration="1000"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></li>
                                <li class="oR light" data-aos="fade-left" data-aos-duration="1000"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Our Best Features -->

<!-- Meet Our Team -->
<section id="team-section" class="sectionP60 grey-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12 res-txt-center">
                    <div  data-aos="fade-right" data-aos-duration="1000">
                        <div class="heading-text">
                            <span class="blue-gradient-color">Meet our team.</span>
                        </div>
                        <p class="light oR" style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit,<br>sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 p0 sectionP40">
                    <div class="col-md-6 col-sm-12 col-xs-12 p0">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="team-member centered tm1" data-aos="fade-up" data-aos-duration="1000">
                                <div class="member-desc">
                                    <p class="white oR">Tom Cruise</p>
                                    <small class="gold-gradient-color">Brilliant Actor</small>
                                    <span class="white oL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                                    <div class="social-icons">
                                        <a href="javascript:;" target="_blank"><i class="fa fa-facebook-f white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa fa-twitter white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa  fa-youtube-play white"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="team-member centered tm2" data-aos="fade-up" data-aos-duration="1000">
                                <div class="member-desc">
                                    <p class="white oR">Adam Levine</p>
                                    <small class="gold-gradient-color">Singer & Songwriter</small>
                                    <span class="white oL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                                    <div class="social-icons">
                                        <a href="javascript:;" target="_blank"><i class="fa fa-facebook-f white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa fa-twitter white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa  fa-youtube-play white"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 p0">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="team-member centered tm3" data-aos="fade-up" data-aos-duration="1000">
                                <div class="member-desc">
                                    <p class="white oR">Adele</p>
                                    <small class="gold-gradient-color">Singer-Songwriter</small>
                                    <span class="white oL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                                    <div class="social-icons">
                                        <a href="javascript:;" target="_blank"><i class="fa fa-facebook-f white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa fa-twitter white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa  fa-youtube-play white"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="team-member centered tm4" data-aos="fade-up" data-aos-duration="1000">
                                <div class="member-desc">
                                    <p class="white oR">Bruno Mars</p>
                                    <small class="gold-gradient-color">My Favourite</small>
                                    <span class="white oL">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                                    <div class="social-icons">
                                        <a href="javascript:;" target="_blank"><i class="fa fa-facebook-f white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa fa-twitter white"></i></a>
                                        <a href="javascript:;" target="_blank"><i class="fa  fa-youtube-play white"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Meet Our Team -->

<!-- Testimonials -->
<section id="testimonial-section" class="hiw-bg">
    <div class="blue-bg sectionP60">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 text-center">
                        <div  data-aos="fade-up" data-aos-duration="1000">
                            <div class="heading-text">
                                <span class="gold">WHY DO PEOPLE L<i class="fa fa-heart-o"></i>VE US?</span>
                            </div>
                            <p class="white oR" style="font-size: 16px;">IT IS CLEAR THAT OUR FREEDOM IS IN OUR HANDS, JOIN OUR TEAM AND GET ALL THE FEATURES FIRST! </p>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 sectionP60" style="padding-bottom: 0">
                        <div id="testimonial">
                            <div class="item col-md-12">
                                <div class="testimonial">
                                    <div class="testi-text">
                                        <p class="white rL" >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porta libero id arcu vulputate.</p>
                                        <span class="gold-gradient-color rM">Adam Levine</span>
                                        <small class="light2 or">Singer</small>
                                    </div>
                                    <div class="testi-p-image">
                                        <div class="arrow-image"><img src="assets/images/testimonial/bottom-pic.png"></div>
                                        <div class="person-image"><div><img class="img-responsive" src="assets/images/testimonial/client-1.png"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item col-md-12">
                                <div class="testimonial">
                                    <div class="testi-text">
                                        <p class="white rL" >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porta libero id arcu vulputate.</p>
                                        <span class="gold-gradient-color rM">Tom Cruise</span>
                                        <small class="light2 or">Actor</small>
                                    </div>
                                    <div class="testi-p-image">
                                        <div class="arrow-image"><img src="assets/images/testimonial/bottom-pic.png"></div>
                                        <div class="person-image"><div><img class="img-responsive" src="assets/images/testimonial/client-2.png"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item col-md-12">
                                <div class="testimonial">
                                    <div class="testi-text">
                                        <p class="white rL" >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porta libero id arcu vulputate.</p>
                                        <span class="gold-gradient-color rM">Adele</span>
                                        <small class="light2 or">Singer</small>
                                    </div>
                                    <div class="testi-p-image">
                                        <div class="arrow-image"><img src="assets/images/testimonial/bottom-pic.png"></div>
                                        <div class="person-image"><div><img class="img-responsive" src="assets/images/testimonial/client-3.png"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item col-md-12">
                                <div class="testimonial">
                                    <div class="testi-text">
                                        <p class="white rL" >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porta libero id arcu vulputate.</p>
                                        <span class="gold-gradient-color rM">Adam Levine</span>
                                        <small class="light2 or">Singer</small>
                                    </div>
                                    <div class="testi-p-image">
                                        <div class="arrow-image"><img src="assets/images/testimonial/bottom-pic.png"></div>
                                        <div class="person-image"><div><img class="img-responsive" src="assets/images/testimonial/client-1.png"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item opacity col-md-12">
                                <div class="testimonial">
                                    <div class="testi-text">
                                        <p class="white rL" >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porta libero id arcu vulputate.</p>
                                        <span class="gold-gradient-color rM">Tom Cruise</span>
                                        <small class="light2 or">Actor</small>
                                    </div>
                                    <div class="testi-p-image">
                                        <div class="arrow-image"><img src="assets/images/testimonial/bottom-pic.png"></div>
                                        <div class="person-image"><div><img class="img-responsive" src="assets/images/testimonial/client-2.png"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item opacity col-md-12">
                                <div class="testimonial">
                                    <div class="testi-text">
                                        <p class="white rL" >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse porta libero id arcu vulputate.</p>
                                        <span class="gold-gradient-color rM">Adele</span>
                                        <small class="light2 or">Singer</small>
                                    </div>
                                    <div class="testi-p-image">
                                        <div class="arrow-image"><img src="assets/images/testimonial/bottom-pic.png"></div>
                                        <div class="person-image"><div><img class="img-responsive" src="assets/images/testimonial/client-3.png"></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonials -->


<!-- Pricing Table -->
<section id="pricing-table" class="sectionP60">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 text-center">
                    <div>
                        <div class="heading-text">
                            <span class="gold-gradient-color">Pricing Table.</span>
                        </div>
                        <p class="light oR" style="font-size: 16px;">Our capability is restricted. Make it wider. Easily.</p>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 sectionP60">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <div class='package'>
                              <div class='name'>Basic</div>
                              <div class='price'>$19</div>
                              <div class='trial'>Free 30 day trial</div>
                              <div class="lineDiv"></div>
                              <ul>
                                  <li>
                                      <strong>Unlimited</strong>
                                      team members
                                  </li>
                                  <li>
                                      <strong>Unlimited</strong>
                                      team playlists
                                  </li>
                                  <li>
                                      <strong>Unlimited</strong>
                                      public playlists
                                  </li>
                                  <li>
                                      Team analytics
                                  </li>
                                  <li>
                                      Send files
                                  </li>
                              </ul>
                              <button class="btn btn-gradient outline">Buy Now</button>
                          </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class='package brilliant'>
                                <div class='name'>Standard</div>
                                <div class='price'>$29</div>
                                <div class='trial'>Free 30 day trial</div>
                                <div class="lineDiv"></div>
                                <ul>
                                    <li>
                                        <strong>Unlimited</strong>
                                        team members
                                    </li>
                                    <li>
                                        <strong>Unlimited</strong>
                                        team playlists
                                    </li>
                                    <li>
                                        <strong>Unlimited</strong>
                                        public playlists
                                    </li>
                                    <li>
                                        Team analytics
                                    </li>
                                    <li>
                                        Send files
                                    </li>
                                </ul>
                                <button class="btn btn-white-outline">Buy Now</button>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <div class='package'>
                              <div class='name'>Premium</div>
                              <div class='price'>$59</div>
                              <div class='trial'>Free 30 day trial</div>
                              <div class="lineDiv"></div>
                              <ul>
                                  <li>
                                      <strong>Unlimited</strong>
                                      team members
                                  </li>
                                  <li>
                                      <strong>Unlimited</strong>
                                      team playlists
                                  </li>
                                  <li>
                                      <strong>Unlimited</strong>
                                      public playlists
                                  </li>
                                  <li>
                                      Team analytics
                                  </li>
                                  <li>
                                      Send files
                                  </li>
                              </ul>
                              <button class="btn btn-gradient outline">Buy Now</button>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Pricing Table -->


<!-- Google Map -->
<section>
    <div id="map-section" class="section">
        <div class="google-map" id="location" data-lat="48.8580362" data-lng="2.2933471" data-zoom="17" style="height: 330px"></div>
    </div>
</section>
<!-- Google Map -->

<!-- Wanna Buy Theme -->
<section class="gold-gradient-bg sectionP40">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-7 col-xs-12 res-txt-center">
                    <h2 class="white rL m0" style="margin-top: 6px;">Wanna join our team?</h2>
                </div>
                <div class="col-md-6 col-sm-5 col-xs-12 res-txt-center">
                    <button class="btn btn-white-outline pull-right res-float-none" style="margin: 0">Buy a place Now</button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Wanna Buy Theme -->

<!-- Footer Section -->
<footer id="contact-section" class="sectionP60 dark-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-7 col-sm-7 col-xs-12 pull-right resCont">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="heading-text">
                            <span class="gold-gradient-color">Get in touch.</span>
                        </div>
                    </div>
                    <form action="index.html">
                        <div class="col-md-12 col-sm-12 col-xs-12 p0">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-box">
                                    <input placeholder="Full Name" type="text" required>
                                    <span style="position: absolute"><i class="fa fa-user"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-box">
                                    <input placeholder="Email Address" type="text" required>
                                    <span style="position: absolute"><i class="fa fa-envelope-o"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 p0">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-box">
                                    <input placeholder="Mobile or Telephone" type="text" required>
                                    <span style="position: absolute"><i class="fa fa-phone"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-box">
                                    <input placeholder="Subject" type="text" required>
                                    <span style="position: absolute"><i class="fa fa-puzzle-piece"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 p0">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="input-box">
                                    <textarea placeholder="Type your message here..." name="" id="" cols="30" rows="5"></textarea>
                                    <span style="position: absolute"><i class="fa fa-comments"></i></span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-gradient outline-button pull-right mtb20"><div style="background: #0C1222;transition: all 0.3s">Send <tag class="hidden-xs">Message</tag></div></button>
                    </form>
                </div>
                <div class="col-md-4 col-sm-5 col-xs-12 border-right resCompany">
                    <div class="company-desc logoo">
                        <a href="#home-section"><img class="img-responsive" src="assets/images/logo.png"/></a>
                        <p class="light2 rl">My home is where my people live, but my people live in the whole world.</p>
                    </div>
                    <div class="meet-us">
                        <p class="gold rL">MEET US</p>
                        <span class="light2 oR">Locally in Kiev, actually everywhere. </span>
                    </div>
                    <div class="cont-us">
                        <p class="gold rL">Contact Us</p>
                        <div><a class="light2 g" href="javascript:;"><span class="oR">Mobile : +3 8073108 - 0887</span></a></div>
                        <div><a class="light2 g" href="javascript:;"><span class="oR">Email : sherlomse@gmail.com</span></a></div>
                        <div><a class="light2 g" href="javascript:;"><span class="oR">Telegram : @Charmam</span></a></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section -->

<!-- Copyright Section -->
<section class="sectionP20" style="background: #0b101d;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12">
                    <p class="light oR m0" style="opacity: .65">&copy; 2018, SnowBank.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Copyright Section -->

<!-- Scroll Back Top Button -->
<button onclick="topFunction()" id="myBtn" class="btn btn-gradient"><i class="visible-xs fa fa-arrow-up"></i><tag class="hidden-xs">Go to top</tag></button>
<!-- Scroll Back Top Button -->


<!-- Popups Are Here -->
<popups>

    <!-- Register Popup -->
    <div id="pop-register" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-aos="zoom-in-up" data-aos-duration="800">&times;</button>
                    <h3 class="modal-title  blue oR m0">REGISTER</h3>
                    <span class="light oR" style="font-size: 14px">Enter your informations to register.</span>
                </div>
                <form action="reg.php" method="POST">
                    <div class="modal-body">
                        <div class="input-box">
                            <input name="login" placeholder="Username" type="text" required>
                            <span style="position: absolute"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="input-box">
                            <input name="mail" placeholder="Email Address" type="email" required>
                            <span style="position: absolute"><i class="fa fa-envelope-o"></i></span>
                        </div>
                        <div class="input-box">
                            <input name="pass" placeholder="Login Key" type="text" required>
                            <span style="position: absolute"><i class="fa fa-key"></i></span>
                        </div>
                        <div class="input-box">
                            <input name="pass2" placeholder="Confirm Login Key" type="text" required>
                            <span style="position: absolute"><i class="fa fa-key"></i></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-gradient">Register</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- Register Popup -->

    <!-- About Us Popup -->
    <div id="pop-about" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-aos="zoom-in-up" data-aos-duration="800">&times;</button>
                    <h3 class="modal-title  gold-gradient-color oR m0">About Us</h3>
                </div>
                <div class="modal-body">
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- About Us Popup -->

    <!-- Think Popup -->
    <div id="pop-read-more1" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-aos="zoom-in-up" data-aos-duration="800">&times;</button>
                    <h3 class="modal-title  gold-gradient-color oR m0">Think</h3>
                </div>
                <div class="modal-body">
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Think Popup -->

    <!-- Create Popup -->
    <div id="pop-read-more2" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-aos="zoom-in-up" data-aos-duration="800">&times;</button>
                    <h3 class="modal-title  gold-gradient-color oR m0">Create</h3>
                </div>
                <div class="modal-body">
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Create Popup -->

    <!-- Sell Popup -->
    <div id="pop-read-more3" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-aos="zoom-in-up" data-aos-duration="800">&times;</button>
                    <h3 class="modal-title  gold-gradient-color oR m0">Sell</h3>
                </div>
                <div class="modal-body">
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Sell Popup -->

    <!-- Earn Popup -->
    <div id="pop-read-more4" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-aos="zoom-in-up" data-aos-duration="800">&times;</button>
                    <h3 class="modal-title  gold-gradient-color oR m0">Earn</h3>
                </div>
                <div class="modal-body">
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p class="light oR">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gradient" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Earn Popup -->

</popups>
<!-- Popups Are Here -->



<!-- All Javascripts -->

<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Bootstrap -->
<script type="text/javascript" src="assets/js/bootstrap.js"></script>

<!-- Nice Scroll -->
<script type="text/javascript" src="assets/plugins/niceScroll/niceScroll.min.js"></script>

<!-- Google Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmiJjq5DIg_K9fv6RE72OY__p9jz0YTMI"></script>
<script type="text/javascript" src="assets/plugins/map/map.js"></script>

<!-- Video Background -->
<script type="text/javascript" src="assets/plugins/videoBg/jquery.vide.js"></script>

<!-- Owl Carousel -->
<script type="text/javascript" src="assets/plugins/owl/owl.carousel.js"></script>

<!-- Number Counter -->
<script type="text/javascript" src="assets/plugins/numScroll/numscroller-1.0.js"></script>

<!-- Scroll Animations aos-master js -->
<script src="assets/plugins/aos-master/aos.js"></script>

<!-- Common -->
<script type="text/javascript" src="assets/js/common.js"></script>

<!-- All Javascripts -->
</body>
</html>
