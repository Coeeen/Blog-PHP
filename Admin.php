<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['zalogowany'])) {
    header('Location: Zaloguj.php');
    exit();
}

// Tablica z listą administratorów
$lista_adminow = array('coeen', 'admin2', 'admin3');

// Sprawdzenie, czy nazwa użytkownika jest na liście administratorów
if (isset($_SESSION['login']) && in_array($_SESSION['login'], $lista_adminow)) {
    $uprawnienia_admin = true;
    $_SESSION['zalogowany_jako_admin'] = true; 
} else {
    $uprawnienia_admin = false;
    $_SESSION['zalogowany_jako_admin'] = false; 
}
$uprawnienia_wlasciciel = true; 



?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1 class='DodanieHeader'>Panel administracyjny</h1>

    <?php if ($uprawnienia_admin): ?>
        <ul>
            <?php if ($uprawnienia_admin): ?>

                <li><a href="Dodanie.php">Dodaj wpis</a></li>
                <li><a href="UsuwanieKomentarzy.php" >Zarządzaj komentarzami</a></li>
                <li><a href='Index.php'>Wracaj</a></li>

            <?php endif; ?>

            <?php if ($uprawnienia_wlasciciel): ?>

                <li><a href="Edycja.php">Edytuj lub usuń wpisy</a></li>

            <?php endif; ?>
        </ul>

    <?php else: ?>
    <?php if 
        (isset($_SESSION['login']) && !in_array($_SESSION['login'], $lista_adminow)): ?>
            <p>Nie masz uprawnień administratora. Wracaj do siebie!</p>
            <a href='Index.php'>Wracaj</a>

        <?php else: ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
