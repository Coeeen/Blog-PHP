<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany, jeśli nie jest, zostanie przekierowany do login.php
if (!isset($_SESSION['zalogowany'])) {
    header('Location: login.php');
    exit();
}

// Obsługa usuwania wpisów
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sprawdzenie, czy jest kliknięty przycisk "Usuń wpis"
    if (isset($_POST['usun_wpis'])) {
        // Pobranie ID wpisu do usunięcia
        $wpis_id = $_POST['wpis_id'];

        // Usunięcie wpisu z bazy danych
        $servername = 'szuflandia.pjwstk.edu.pl';
$username = 's24586';
$password = 'Kry.Jank';
$database = 's24586';


        $conn = new mysqli($servername, $username, $password, $database);

        // Sprawdzenie połączenia
        if ($conn->connect_error) {
            die('Błąd połączenia: ' . $conn->connect_error);
        }

        $query = "DELETE FROM wpisy WHERE id = $wpis_id";

        if ($conn->query($query) === TRUE) {
            $potwierdzenie = "Wpis został usunięty.";
        } else {
            $bledy[] = "Błąd podczas usuwania wpisu: " . $conn->error;
        }

        $conn->close();
    }
    // Sprawdzenie, czy jest kliknięty przycisk "Edytuj wpis"
    elseif (isset($_POST['edytuj_wpis'])) {
        // Pobranie danych edytowanego wpisu
        $wpis_id = $_POST['wpis_id'];
        $nowy_tytul = $_POST['nowy_tytul'];

        // Aktualizacja wpisu w bazie danych
        $servername = 'szuflandia.pjwstk.edu.pl';
$username = 's24586';
$password = 'Kry.Jank';
$database = 's24586';


        $conn = new mysqli($servername, $username, $password, $database, $port);

        // Sprawdzenie połączenia
        if ($conn->connect_error) {
            die('Błąd połączenia: ' . $conn->connect_error);
        }

        $query = "UPDATE wpisy SET tytul = '$nowy_tytul' WHERE id = $wpis_id";

        if ($conn->query($query) === TRUE) {
            $potwierdzenie = "Wpis został zaktualizowany.";
        } else {
            $bledy[] = "Błąd podczas aktualizacji wpisu: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css"> 
    <title>Usuwanie i edycja wpisów</title>
</head>
<body>
    <h1 class="DodanieHeader">Usuwanie i edycja wpisów</h1>

    <?php if (isset($potwierdzenie)): ?>
        <p style="color: green;"><?php echo $potwierdzenie; ?></p>
    <?php elseif (!empty($bledy)): ?>
        <p style="color: red;"><?php echo implode("<br>", $bledy); ?></p>
    <?php endif; ?>

    <ul>
        <?php
        // Wyświetlenie listy wpisów z bazy danych
        $servername = 'szuflandia.pjwstk.edu.pl';
        $username = 's24586';
        $password = 'Kry.Jank';
        $database = 's24586';
        

        $conn = new mysqli($servername, $username, $password, $database, $port);

        // Sprawdzenie połączenia
        if ($conn->connect_error) {
            die('Błąd połączenia: ' . $conn->connect_error);
        }

        $query = "SELECT * FROM wpisy";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $wpis_id = $row['id'];
                $tytul = $row['tytul'];
                
                ?>
                <li>
                    <?php echo $tytul; ?>
                    <form method="POST" action="">
                        <input type="hidden" name="wpis_id" value="<?php echo $wpis_id; ?>">
                        <input type="submit" name="usun_wpis" value="Usuń" class="edycjaKomentarza">
                    </form>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="wpis_id" value="<?php echo $wpis_id; ?>">
                        <input type="text" name="nowy_tytul" class="ZmianaKomentarza" placeholder="Nowy tytuł">
                        <!-- Dodaj więcej pól do edycji wpisu -->
                        <input type="submit" name="edytuj_wpis" class="edycjaKomentarza" value="Edytuj">
                    </form>
                </li>
                <?php
            }
        } else {
            echo '<li>Brak wpisów.</li>';
        }

        $conn->close();
        ?>
    </ul>
    <a href="Index.php">Powrót na stronę główną</a>
</body>
</html>
