<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany, jeśli nie jest wraca na Zaloguj.php i kończy wykonywanie kodu
if (!isset($_SESSION['zalogowany'])) {
    header('Location: Zaloguj.php');
    exit();
}

// Obsługa formularza zmiany hasła użytkownika
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Odbieranie danych z formularza
    $login = $_POST['login'];
    $nowe_haslo = $_POST['nowe_haslo'];
    $powtorz_haslo = $_POST['powtorz_haslo'];

    // Logi z błędami
    $bledy = [];

    // Sprawdzenie zgodności haseł i zapisywanie błędów do logów
    if ($nowe_haslo !== $powtorz_haslo) {
        $bledy[] = "Hasła nie są identyczne.";
    }

    // Jeśli nie ma błędów, aktualizowanie hasła w bazie danych
    if (empty($bledy)) {
        // Aktualizacja hasła w bazie danych
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

        // Jeśli dane są poprawne, nie hashować ponownie hasła
        $hashed_haslo = $nowe_haslo;

        // Zapytanie, gdzie aktualizuje hasło użytkownika o danym loginie
        $query = "UPDATE uzytkownicy SET haslo='$hashed_haslo' WHERE login='$login'";

        // Zapytanie udało się w bazie danych
        if ($conn->query($query) === TRUE) {
            $potwierdzenie = "Hasło użytkownika zostało zmienione.";
        } else {
            $bledy[] = "Błąd podczas zmiany hasła: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css"> 
    <title>Zmiana hasła</title>
</head>
<body>
    <div class="DodaniePostu">
        <h1 class="DodanieHeader">Zmiana hasła</h1>

        <!-- jesli wszystko sie uda wypisuje sie potwiedzenie z kolorem zielonym -->
        <?php if (isset($potwierdzenie)): ?>
            <p style="color: green;"><?php echo $potwierdzenie; ?></p>

            <!-- Jeśli pojawi się błąd pojawi sie powiadomie i wypisanie logow -->
        <?php elseif (!empty($bledy)): ?>
            <p style="color: red;"><?php echo $bledy; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="login">Login użytkownika:</label>
            <input type="text" id="login" name="login" required class="edycjaKomentarza">
            <br>
            <label for="nowe_haslo">Nowe hasło:</label>
            <input type="password" id="nowe_haslo" name="nowe_haslo" required class="edycjaKomentarza">
            <br>
            <label for="powtorz_haslo">Powtórz hasło:</label>
            <input type="password" id="powtorz_haslo" name="powtorz_haslo" required class="edycjaKomentarza">
            <br>
            <input type="submit" value="Zmień hasło" class="edycjaKomentarza">
        </form>

        <a href='Index.php'>Wróć</a>
    </div>
</body>
</html>
