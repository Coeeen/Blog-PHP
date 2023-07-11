<?php


// Sprawdzenie, czy formularz rejestracji zostal przeslany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = $_POST['login'];
    $haslo = $_POST['haslo'];
    $email = $_POST['email'];
    $bledy = [];



    // // Sprawdzenie, czy podane dane są poprawne nie jest jednak potrzebne poniewaz mam required w inpucie
    // if (empty($login) || empty($haslo) || empty($email)) {
    //     $bledy[] = "Wszystkie pola są wymagane.";
    // }



    // Jeśli nie ma żadnych blędów, można utworzyć konto w bazie danych
    if (empty($bledy)) {

        $servername = 'szuflandia.pjwstk.edu.pl';
        $username = 's24586';
        $password = 'Kry.Jank';
        $database = 's24586';
        
     
        $conn = new mysqli($servername, $username, $password, $database);
        

        if ($conn->connect_error) {
            die('Bląd polączenia: ' . $conn->connect_error);
        }

        // Sprawdzenie, czy użytkownik o podanym loginie już istnieje
        $query = "SELECT * FROM uzytkownicy WHERE login = '$login'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $bledy[] = "Użytkownik o podanym loginie już istnieje.";
        } 
         //Jesli nie ma takiego uzytkownika
        else {

            // Dodanie nowego użytkownika do bazy danych
            $query = "INSERT INTO uzytkownicy (login, haslo, email) VALUES ('$login', '$haslo', '$email')";

            //Jesli sie udalo przypisuje potwiedzenie jesli nie doposuje blad do logow
            if ($conn->query($query) === TRUE) {
                $potwierdzenie = "Konto zostalo utworzone.";
            } else {
                $bledy[] = "Bląd podczas tworzenia konta: " . $conn->error;
            }
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css"> 
    <title>Rejestracja</title>
</head>
<body>

<h1 class="DodanieHeader">Rejestracja</h1>

     <div class="DodaniePostu">

     <!-- Info o tym ze sie udalo -->
    <?php if (isset($potwierdzenie)): ?>
        <p style="color: green;"><?php echo $potwierdzenie; ?></p>

        <!-- Sprawdza czy sa jakies bledy i je wypisuje  Wypisanie bledow -->
    <?php elseif (!empty($bledy)): ?>
        <p style="color: red;"><?php echo $bledy; ?>
    <?php endif; ?>
     
    <form method="post" action="">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required class="edycjaKomentarza"><br>

        <label for="haslo">Haslo:</label>
        <input type="password" id="haslo" name="haslo" required class="edycjaKomentarza"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required class="edycjaKomentarza"><br>

        <input type="submit" value="Zarejestruj" class='RejestracjaButton'>
       
    </form>
    <a href='Index.php'>Wracaj</a>
     </div>
</body>
</html>