<?php
    include("./usuario.inc.php");
    include("./bd.inc.php");

    $bd = new Connection("root", "", "mysql:host=localhost;dbname=profile");
    $test = $bd->newPDO();
    if(!$test){
        echo "Error conectando a la base de datos";
    }

    session_start();
    if(isset($_SESSION["user"])){
        header("Location: ./index.php");
        exit();
    }

    $errUser = "";
    $errPass = "";
    $errImg = "";
    $err = "";
    $user = "";
    $pass = "";
    $flag = true;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $rutaimg = "";
        if($_POST["user"] == ""){
            $errUser = "*Este campo no puede estar vacío";
            $flag = false;
        }else{
            $user = $_POST["user"];
        }
        if($_POST["password"] == ""){
            $errPass = "*Este campo no puede estar vacío";
            $flag = false;
        }else{
            $pass = $_POST["password"];
        }
        if(!isset($_FILES["imagen"])){
            $errImg = "*Debe proporcionar una imágen para continuar";
            $flag = false;
        }
        if(isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] != null){
            $errImg = "*Ha habido un error al subir la imágen";
            $flag = false;
        }
        if(isset($_FILES["imagen"]) && ($_FILES["imagen"]["type"] != "image/jpeg" && $_FILES["imagen"]["type"] != "image/png")){
            $errImg = "*El archivo debe ser una imagen";
            $flag = false;
        }else if(isset($_FILES["imagen"])){
            $resolution = getimagesize($_FILES["imagen"]["tmp_name"]);
            if($resolution[0] > 360 || $resolution[1] > 480){
                $errImg = "La resolución de la imágen es mayor a 360x480";
                $flag = false;
            }
        }
        
        if($flag){
            $statement = "SELECT usuario FROM usuario WHERE usuario LIKE '". $user ."';";
            $result = $bd->statement($statement);
            $img = "";
            try{
                if(($usuario = $result->fetch(PDO::FETCH_ASSOC)) != null){
                    $errUser = "El usuario ya existe";
                }else{
                    if(!file_exists("/img/users/".$user)){
                        mkdir("./img/users/".$user, 0777, true);
                    }
                    $passHash = password_hash($pass, PASSWORD_DEFAULT);
                    $rutaimg = "./img/users/". $user ."/". $user.".png";
                    $rutaimgbig = "./img/users/". $user ."/". $user."Big.png";
                    $rutaimgsmall = "./img/users/". $user ."/". $user."Small.png";
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaimg);
                    $img = imagecreatefrompng($rutaimg);

                    $big = imagescale($img, 360, 480, IMG_BILINEAR_FIXED);
                    $small = imagescale($img, 72, 96, IMG_BILINEAR_FIXED);
                    imagepng($big, $rutaimgbig);
                    imagepng($small, $rutaimgsmall);
                    $statement = "INSERT INTO usuario (usuario, password, rutaimg, rutaimgsmall) VALUES ('". $user ."', '". $passHash ."', '". $rutaimgbig ."', '". $rutaimgsmall ."');";
                    $bd->statement($statement);

                    header("Location: ./login.php");
                    exit();
                }
            }catch(Throwable $e){
                $err = "Ha habido un error al enviar el formulario";
            }

        }
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Registro</h1>
    <div>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div>
                <label for="user">Usuario: </label>
                <input type="text" name="user" id="user" value="<?php echo $user;?>"> <?php echo $errUser;?>
            </div>
            <div>
                <label for="password">Contraseña: </label>
                <input type="password" name="password" id="password" value="<?php echo $pass;?>"> <?php echo $errPass;?>
            </div>
            <div>
                <input type="file" name="imagen" id="imagen"> <?php echo $errImg;?>
            </div>
            <div><input type="submit" value="Enviar"></div>
            <?php echo $err;?>
        </form>
    </div>
    <div>
        <a href="./login.php">Log in</a>
    </div>
</body>
</html>