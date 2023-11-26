<?php

    include("./usuario.inc.php");
    include("./bd.inc.php");

    session_start();
    $userObject = "";
    if(isset($_SESSION["user"])){
        
        $array = explode("|",$_SESSION["object"]);
        $userObject = new Usuario((int)$array[0], $array[1], $array[3], $array[4], $array[5]);
    }else{
        header("Location: ./login.php");
        exit();
    }

    $user = $userObject->__get("user");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <div class="username">
            <div>
                <img src="<?php echo $userObject->__get("rutaimgsmall");?>" alt="">
            </div>
            <div>
                <?php echo $user?>
            </div>
        </div>
        <div id="logout">
            <a href="./login.php?logout=true">Cerrar Sesión</a>
        </div>
    </header>
    <main>
        <div>
            <img src="<?php echo $userObject->__get("rutaimgbig");?>" alt="">
        </div>
        <div>
            ID:<?php echo $userObject->__get("id");?><br>
            Usuario:<?php echo $userObject->__get("user");?>
        </div>
    </main>
</body>
</html>