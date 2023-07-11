<?php
session_start();

// Sprawdzenie, czy użytkownik jest już zalogowany jesli tak wraca na Index
if (isset($_SESSION['zalogowany'])) {
    $login = $_SESSION['login'];
    header('Location: Index.php'); 
    exit();
}

// Sprawdzenie, czy formularz został przesłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

 
    $servername = 'szuflandia.pjwstk.edu.pl';
$username = 's24586';
$password = 'Kry.Jank';
$database = 's24586';

    
    // Tworzenie połączenia
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Sprawdzenie połączenia
    if ($conn->connect_error) {
        die('Błąd połączenia: ' . $conn->connect_error);
    }

    //Zapytanie ktore wypisze nam uzytkownikow o takim loginie i hasle
    $query = "SELECT * FROM uzytkownicy WHERE login = '$login' AND haslo = '$haslo'";

    $result = $conn->query($query);

    // Poprawne dane logowania jesli znajduja sie konkretie 1 osobnik,tworzy sie sesja dla tego użytkownika
    if ($result->num_rows === 1) {
        $_SESSION['zalogowany'] = true;
        $_SESSION['login'] = $login;
        header('Location: Index.php');
        setcookie('username', $login, time() + (86400 * 30), '/');
        exit();

    } else {

        // Błędne dane logowania
        $blad = "Błędne dane logowania. Spróbuj ponownie.";
        echo " <a href='Index.php'>Wracaj</a>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css"> 
    <title>Logowanie</title>
</head>
<body>
    <h1>Logowanie</h1>
    
    <!-- Wyświetlanie komunikatu o błędzie jesli taki sie znajduje-->
    <?php if (isset($blad)): ?>
        <p style="color: red;">
        <?php echo $blad; ?>
    </p>
    
        <!-- Formularz logowania -->
    <?php else: ?>
        <form method="POST" action="">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" required>
            <br>
            <label for="haslo">Hasło:</label>
            <input type="password" id="haslo" name="haslo" required>
            <br>
            <input type="submit" value="Zaloguj" class='RejestracjaButton'><br>
            <a href='Index.php'>Wróć</a>
        </form>
    
    <?php endif; ?>
</body>
</html>
