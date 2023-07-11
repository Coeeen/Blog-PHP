<?php
session_start();


$uprawnienia_admin = true; 

// Pobranie nazwy autora jesli nie jest autorem jest gosc
if (isset($_SESSION['zalogowany'])) {
    $autor = $_SESSION['login'];
} else {
    $autor = "Gość";
}

// Obsługa formularza dodawania wpisów
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];
    $bledy = [];

    // Przykładowe sprawdzenie walidacji dla tytułu (wymagane pole)
    if (empty($tytul)) {
        $bledy[] = "Tytuł jest wymagany.";
    }


    // Jeśli nie ma błędów, dodawanie wpisu do bazy danych
    if (empty($bledy)) {

        // Dodawanie wpisu do bazy danych
        // szuflandia.pjwstk.edu.pl


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

        // Pobranie aktualnej daty
        $data_dodania = date('Y-m-d H:i:s');

        //Zapytanie do bazy danych
        $query = "INSERT INTO wpisy (tytul, tresc, autor, data_dodania) VALUES ('$tytul', '$tresc', '$autor', '$data_dodania')";

        if ($conn->query($query) === TRUE) {
            $potwierdzenie = "Wpis został dodany.";
        } else {
            $bledy[] = "Błąd podczas dodawania wpisu: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodawanie wpisów</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <h1 class="DodanieHeader">Dodawanie wpisów</h1>

    <?php if ($uprawnienia_admin || isset($_SESSION['zalogowany'])): ?>

        <?php if (isset($potwierdzenie)): ?>
            <p style="color: green;"><?php echo $potwierdzenie; ?></p>

        <?php elseif (!empty($bledy)): ?>
            <p style="color: red;"><?php echo $bledy; ?></p>
        <?php endif; ?>

        <div class="DodaniePostu">
        <form method="POST" action="">

            <label for="tytul"> <span>Tytuł:</span></label><br>
            <input type="text" id="tytul" name="tytul" class="DodanieInput" required>
            <br>
            <label for="tresc"><span>Treść:</span></label><br>
            <textarea id="tresc" name="tresc" required class="TrescInput"></textarea><br>

            <?php if (isset($_SESSION['zalogowany'])): ?>

                <label for="autor">Autor:</label>
                <input type="text" id="autor" class="ZmianaKomentarza"  name="autor" value="<?php echo $autor; ?>" required readonly>
                
            <?php endif; ?>

            <input type="submit" value="Dodaj wpis" class="edycjaKomentarza"><br>
            <a href='Index.php'>Wracaj</a>

        </form>
            </div>
    <?php else: ?>
        <p>Nie masz uprawnień do dodawania wpisów.</p>
    <?php endif; ?>
</body>
</html>