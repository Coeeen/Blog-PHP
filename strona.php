<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>


<?php

// Połączenie z bazą danych 
$servername = 'szuflandia.pjwstk.edu.pl';
$username = 's24586';
$password = 'Kry.Jank';
$database = 's24586';



$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die('Błąd połączenia: ' . $conn->connect_error);
}


// Funkcja do wyświetlania pełnego artykułu
function wyswietlPelnyArtykul($id) {

    global $conn;
    $sql = "SELECT * FROM wpisy WHERE id = $id";
    $result = $conn->query($sql);

    //Jeśli sa jakies Artykuly i wypisanie za pomocą fetch_assoc(); po kolei tytulu tresci do zmiennych
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tytul = $row['tytul'];
        $tresc = $row['tresc'];
        $autor = $row['autor'];
        $data = $row['data_dodania'];
?>
        <!-- Wypisanie Postu -->
       <div class="DodaniePostu">
        <h2 class='DodanieHeader'><?php echo $tytul; ?></h2>
        <p>Autor: <?php echo $autor; ?>, Data: <?php echo $data; ?></p>
        <p class="TrescPostu"><?php echo $tresc; ?></p>
        <a href="Index.php">Wracaj</a>
    </div>
    

<?php
    } else {
        echo "Artykuł nie istnieje.";
    }
}

// Sprawdzenie, czy przekazano identyfikator artykułu jesli tak jest opcja wyswietlania pelnego artykuly
if (isset($_GET['id'])) {
    $wpisId = $_GET['id'];
    wyswietlPelnyArtykul($wpisId);
} else {
    echo "Brak artykułu do wyświetlenia.";
}

// Zamknięcie połączenia z bazą danych
$conn->close();

?>
</body>
</html>