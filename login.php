<?php

    include("./usuario.inc.php");
    include("./bd.inc.php");

    $bd = new Connection("root", "", "mysql:host=localhost;dbname=profile");
    $test = $bd->newPDO();
    if(!$test){
        echo "Error conectando a la base de datos";
    }

    session_start();
    if(isset($_GET["logout"])){
        session_destroy();
        header("Location: ./login.php");
        exit();
    }
    if(isset($_SESSION["user"])){
        header("Location: ./index.php");
        exit();
    }

    
    $errUser = "";
    $errPass = "";
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

        
        if($flag){
            $statement = "SELECT * FROM usuario WHERE usuario LIKE '". $user ."';";
            $result = $bd->statement($statement);
            try{
                if(($usuario = $result->fetch(PDO::FETCH_NUM)) != null){
                    if(password_verify($_POST["password"], $usuario[2])){
                        $userObject = new Usuario($usuario[0], $usuario[1], $usuario[2], $usuario[3], $usuario[4]);
                        $_SESSION["user"] = $user;
                        $_SESSION["object"] = $userObject->__toString();
                        header("Location: ./index.php");
                        exit();
                    }else{
                        $err = "El usuario y la contraseña no coinciden";
                    }



                }else{
                    $errUser = "El usuario introducido no existe";
                }
            }catch(Throwable $e){
                $err = "Ha habido un error al comprobar los datos";
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
    <h1>Login</h1>
    <div>
        <form action="#" method="POST">
            <div>
                <label for="user">Usuario: </label>
                <input type="text" name="user" id="user" value="<?php echo $user;?>"> <?php echo $errUser;?>
            </div>
            <div>
                <label for="password">Contraseña: </label>
                <input type="password" name="password" id="password" value="<?php echo $pass;?>"> <?php echo $errPass;?>
            </div>
            <div><input type="submit" value="Enviar"></div>
            <?php echo $err;?>
        </form>
    </div>
    <div>
        <a href="./register.php">Registrarse</a>
    </div>
</body>
</html>