<link rel="stylesheet" href="anecdoteStyle.css">
<?php include('header.php'); ?>


<div class="content-wrapper">
    <?php include('menu.php'); ?>

    <div class="main-content">
        <h2>Programmeerija Anekdoodid</h2>


        <!-- Здесь будет отображаться выбранный анекдот -->
        <div class="anecdote-content">
            <?php
            // Определяем, какой анекдот нужно показать
            if (isset($_GET['anecdote'])) {
                $anecdote_number = $_GET['anecdote'];
                $file_name = "anecdote{$anecdote_number}.php";
                if (file_exists($file_name)) {
                    include($file_name);
                } else {
                    echo "<p>Anekdooti ei leitud!</p>";
                }
            } else {
                echo "<p>Valige anekdoot menüüst.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
