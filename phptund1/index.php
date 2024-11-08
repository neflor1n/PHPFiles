<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <title>PHP tunnitööd</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <header>
        <h1>
            PHP tunnitööd 
        </h1>
    </header>
    <?php
        include('nav.php');
    ?>
    
    <section>
        <?php
            if(isset($_GET['leht'])) {
                include('content/'.$_GET["leht"]);
            }
            else {
                include('kodu.php');
            }
            
        ?>
    </section>

    <br>
    <br>

    <?php
        include('footer.php');
    ?>
</body>

</html>