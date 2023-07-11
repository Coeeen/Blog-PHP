<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<?php
session_start();

// Sprawdzanie czy użytkownik jest zalogowany czy jest gościem
function pobierzZalogowanegoUzytkownika() {
    if (isset($_SESSION['login'])) {
        return $_SESSION['login'];
    } else {
        return 'gosc';
    }
}

$servername = 'szuflandia.pjwstk.edu.pl';
$username = 's24586';
$password = 'Kry.Jank';
$database = 's24586';

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die('Bląd polączenia: ' . $conn->connect_error);
}

// Funkcja do skrócenia tekstu
function skrocTekst($tekst, $dlugosc) {
    if (strlen($tekst) > $dlugosc) {
        $tekst = substr($tekst, 0, $dlugosc);
    }
    return $tekst . "...";
}

function wyswietlWpisy() {
    global $conn;

    $sql = "SELECT * FROM wpisy ORDER BY data_dodania DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $tytul = $row['tytul'];
            $tresc = $row['tresc'];
            $autor = $row['autor'];
            $data = $row['data_dodania'];

            $skrocony_tekst = skrocTekst($tresc, 10);

            ?>

            <div class='Posty'>

                <h2 class='PostyTytul'><?php echo $tytul; ?></h2>
                <p><?php echo $skrocony_tekst; ?></p>
                <p>Autor: <?php echo $autor; ?>, Data: <?php echo $data; ?></p>

                <a href='strona.php?id=<?php echo $id; ?>' class='PostyStrona'>Czytaj dalej</a>

                <form method='post' action=''>
                    <input type='hidden' name='wpis_id' value='<?php echo $id; ?>'>
                    <textarea name='komentarz' placeholder='Komentarz' class='PostyKomentarz'></textarea><br>
                    <input type='submit' value='Dodaj komentarz' class='PostyButton'>
                </form>
                <?php
                wyswietlKomentarze($id);
                ?>
            </div>
            <?php
        }
    } else {
        echo "Brak wpisów.";
    }
}

function wyswietlPelnyArtykul($id) {
    global $conn;

    $sql = "SELECT * FROM wpisy WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tytul = $row['tytul'];
        $tresc = $row['tresc'];
        $autor = $row['autor'];
        $data = $row['data_dodania'];
        ?>

        <h2><?php echo $tytul; ?></h2>

        <p>Autor: <?php echo $autor; ?>, Data: <?php echo $data; ?></p>

        <p><?php echo $tresc; ?></p>

        <form method='post' action=''>
            <input type='hidden' name='wpis_id' value='<?php echo $id; ?>'>
            <textarea name='komentarz' placeholder='Komentarz'></textarea><br>
            <input type='submit' value='Dodaj komentarz' class='DodanieButton'>
        </form>

        <?php
        wyswietlKomentarze($id);
    } else {
        echo "Artykul nie istnieje.";
    }
}

function dodajKomentarz($wpisId, $komentarz) {
    global $conn;
    $wpisId = intval($wpisId);
    $nick = pobierzZalogowanegoUzytkownika();

    // Escapowanie ciągu znaków komentarz przed umieszczeniem go w zapytaniu SQL
    $komentarz = $conn->real_escape_string($komentarz);

    $sql = "INSERT INTO komentarze (wpis_id, nick, komentarz) VALUES ($wpisId, '$nick', '$komentarz')";

    if ($conn->query($sql) === TRUE) {
        header("Location: Index.php");
        exit();
    } else {
        echo "Bląd podczas dodawania komentarza: " . $conn->error;
    }
}

function wyswietlKomentarze($wpisId) {
    global $conn;

    $wpisId = intval($wpisId);

    $sql = "SELECT * FROM komentarze WHERE wpis_id = $wpisId";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $nick = $row['nick'];
            $komentarz = $row['komentarz'];
            ?>

            <p class="komentarzyk"><?php echo $nick; ?>:</strong> <?php echo $komentarz; ?></p>

            <?php
        }
    } else {
        echo "Brak komentarzy.";
    }
}
?>

<div id='panel-administracyjny'>
    <a href='Rejestracja.php' class='Link'>Rejestracja</a>
    <a href='Zaloguj.php' class='Link'>Zaloguj</a>
    <a href='Admin.php' class='Link'>Admin</a>
    <a href='Dodanie.php' class='Link'>Dodanie</a>
    <a href='ZmianaHasla.php' class='Link'>Zmien Haslo</a>
    <a href='wyloguj.php' class='Link'>wyloguj</a>
</div>

<span>Witaj, <?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : 'nieznajomy'; ?></span>

<?php
wyswietlWpisy();

if (isset($_GET['id'])) {
    $wpisId = $_GET['id'];
    wyswietlPelnyArtykul($wpisId);
}

if (isset($_POST['komentarz'])) {
    $wpisId = $_POST['wpis_id'];
    $komentarz = $_POST['komentarz'];
    dodajKomentarz($wpisId, $komentarz);
}

if (isset($_GET['id'])) {
    $wpisId = $_GET['id'];
    wyswietlKomentarze($wpisId);
}

$conn->close();
?>

</body>
</html>