<!DOCTYPE html>
<html>
<head>
    <title>Usuwanie i edycja komentarzy</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1 class="DodanieHeader">Usuwanie i edycja komentarzy</h1>

    <?php
    //podpiecie do bazy
    $servername = 'szuflandia.pjwstk.edu.pl';
    $username = 's24586';
    $password = 'Kry.Jank';
    $database = 's24586';
    

    $conn = new mysqli($servername, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die('Błąd połączenia: ' . $conn->connect_error);
    }


    //Pobiera id komentarza z formularza który chcemy usunać
    if (isset($_POST['usun_komentarz'])) {
        $komentarz_id = $_POST['komentarz_id'];

        //Zapytanie do bazy danych
        $query = "DELETE FROM komentarze WHERE id = '$komentarz_id'";

        //Wypisanie komunikatu
        if ($conn->query($query) === TRUE) {
            echo '<p style="color: green;">Komentarz został usunięty.</p>';
        } else {
            echo '<p style="color: red;">Błąd podczas usuwania komentarza: ' . $conn->error . '</p>';
        }
    }

    //Działa na podobnej zasadzie co usuniecie tylko zastepuje tresc 
    if (isset($_POST['edytuj_komentarz'])) {

        $komentarz_id = $_POST['komentarz_id'];
        $nowa_tresc = $_POST['nowa_tresc'];

        //Zamiast delete jest updtate
        $query = "UPDATE komentarze SET komentarz = '$nowa_tresc' WHERE id = '$komentarz_id'";
        
        if ($conn->query($query) === TRUE) {
            echo '<p style="color: green;">Komentarz został zaktualizowany.</p>';
        } else {
            echo '<p style="color: red;">Błąd podczas aktualizacji komentarza: ' . $conn->error . '</p>';
        }
    }
    //Wypisanie wszystkich komentarzy
    $query = "SELECT * FROM komentarze";
    $result = $conn->query($query);
    ?>

    <!-- Jeśli są jakies komentarze -->
    <?php if ($result->num_rows > 0): ?>

        <div class="DodaniePostu">
            <ul>
                <!-- fetch_assoc() przypisuje wynik jako tablice asocjacyjna dzieki czemu potem mozna wyciagnac id i komentarz -->
                <?php 
                //petla while!
                while ($row = $result->fetch_assoc()): ?>

                    <?php

                    //Wyciagniecie
                    $komentarz_id = $row['id'];
                    $tresc = $row['komentarz'];
                    ?>
                    <li>
                        <?php echo $tresc; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="komentarz_id" value="<?php echo $komentarz_id; ?>">
                            <input type="submit" name="usun_komentarz" value="Usuń" class="edycjaKomentarza">
                            <input type="text" name="nowa_tresc" placeholder="Nowa treść" class="ZmianaKomentarza">
                            <input type="submit" name="edytuj_komentarz" value="Edytuj" class="edycjaKomentarza">
                        </form>
                    </li>

                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Jeśli nie ma  -->
    <?php else: ?>
        <p>Brak komentarzy.</p>
    <?php endif; ?>


    <?php
    $conn->close();
    ?>

    <a href="Index.php">Powrót na stronę główną</a>
</body>
</html>
