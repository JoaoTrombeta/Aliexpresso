<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aliexpresso</title>
    <link rel="icon" href="./assets/img/icon_site.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Martian+Mono:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/router.css">
  
</head>
<body>
    <header>
    <?php
        include_once('view/resources/header.php');
    ?>
    </header>
    <main>

    <?php

    if(isset($_GET['modal'])){
        if($_GET['modal'] == "sim"){
            $_SESSION['modal'] = $_GET['modal'];
        }elseif($_GET['modal'] == "nao" && $_GET['page'] != ""){
            $_SESSION['modal'] = 'nao';
            header("location: index.php?page=".$_GET['page']);
        }
    }

    if(isset($_GET['page'])){
        include_once('view/pages/'.$_GET['page'].'.php');
    }else{
        include_once('view/pages/home.php');
    }

    ?>
    </main>
    <footer>
        <?php
            include_once('view/resources/footer.php');
        ?>
    </footer>
</body>
</html>