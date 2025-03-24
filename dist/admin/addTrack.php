<?php
    session_start();
    require_once 'config.php';
    $connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno() > 1){
        echo "Some issue:".mysqli_connect_error();
    }

    $validToken = false;
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        $tokenQuery = "SELECT * FROM users WHERE token = '$token' AND isAdmin = 1";
        $tokenResult = mysqli_query($connection, $tokenQuery);
    
        if ($tokenResult && mysqli_num_rows($tokenResult) > 0) {
            $validToken = true;
        } else {
            header('Location: ../index.html');
            exit();
        }
    } else {
        header('Location: ../index.html');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sprawdzenie, czy plik został przesłany
        if (isset($_FILES['song']) && $_FILES['song']['error'] === UPLOAD_ERR_OK) {
            // Ustawienia folderu docelowego
            $uploadDir = '../audio/';
            
            // Pobranie informacji o pliku
            $fileName = basename($_FILES['song']['name']);
            $targetFile = $uploadDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Dozwolone typy plików (np. MP3, WAV)
            $allowedTypes = ['mp3', 'wav', 'flac', 'ogg'];

            // Sprawdzenie typu pliku
            if (in_array($fileType, $allowedTypes)) {
                // Przesyłanie pliku
                if (move_uploaded_file($_FILES['song']['tmp_name'], $targetFile)) {
                    $uploadDir = 'audio/';
                    $link = $uploadDir . $fileName;
                    $performer = $_POST['performer'];
                    $title = $_POST['title'];

                    // Inicjalizacja zmiennej cover_link przed warunkiem
                    $cover_link = '';

                    // Obsługa przesyłania okładki
                    if (isset($_FILES['cover_link']) && $_FILES['cover_link']['error'] === UPLOAD_ERR_OK) {
                        $coverDir = '../img/covers/';
                        $coverName = basename($_FILES['cover_link']['name']);
                        $coverFile = $coverDir . $coverName;
                        $coverType = strtolower(pathinfo($coverFile, PATHINFO_EXTENSION));

                        // Dozwolone typy plików graficznych (okładka)
                        $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];

                        if (in_array($coverType, $allowedImageTypes)) {
                            if (move_uploaded_file($_FILES['cover_link']['tmp_name'], $coverFile)) {
                                // Przypisanie pełnej ścieżki do okładki w formacie tekstowym
                                $coverDir = 'img/covers/';
                                $coverFile = $coverDir . $coverName;
                                $cover_link = "'" . $coverFile . "'";
                            } else {
                                echo "Błąd podczas przesyłania okładki.";
                            }
                        } else {
                            echo "Nieprawidłowy format okładki. Dozwolone typy: " . implode(', ', $allowedImageTypes);
                        }
                    }

                    // Tworzenie zapytania SQL
                    $query_prompt = "INSERT INTO songs (performer, title, cover_link, link) VALUES ('$performer', '$title', $cover_link, '$link')";
                    $query = mysqli_query($connection, $query_prompt);
                    $_SESSION['uploaded'] = true;

                    if (!$query) {
                        echo "Błąd podczas zapisu do bazy: " . mysqli_error($connection);
                    }
                } else {
                    echo "Błąd podczas przesyłania pliku.";
                }
            } else {
                echo "Nieprawidłowy format pliku. Dozwolone typy: " . implode(', ', $allowedTypes);
            }
        } else {
            echo "Nie wybrano pliku lub wystąpił błąd podczas przesyłania.";
        }
    }
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRAKTIKER - DODAWANIE UTWORÓW</title>
    <link href="../style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="bg-gray-800 justify-center items-center flex flex-col size-full overflow-x-hidden">
    <script>
        function delDiv() {
            fetch('addTrack.php')
                .then(() => {
                    const announcement = document.getElementById('announcement');
                    const button = document.getElementById('button');
                    if (announcement) {
                        announcement.style.display = 'none';
                        button.style.display = 'none';
                    }
                });
        }
    </script>
    <?php
    if(isset($_SESSION['uploaded'])){
        echo '
        <div id="announcement" class="absolute bg-gray-900 text-white font-bold p-10 flex flex-col items-center justify-center"><span class="text-2xl">UTWÓR ZOSTAŁ DODANY</span>
            <button id="button" onclick="delDiv()" class="outline-3 outline-solid outline-offset-2 font-bold p-[4px] w-[25vw] md:w-[15vw] mt-[2vh] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">OK</button>
        </div>
        ';
    }
    ?>
    <header class="relative md:p-[30px] p-[15px] w-screen flex flex-row">
        <div class="absolute inset-0 blur-xl rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
        <div class="absolute inset-0 bg-gray-600"></div>
        <section class="relative md:w-[35vw]">
            <h1 class="text-2xl font-bold text-white sm:text-3xl">
                <a href="../index.html" class="hover:text-emerald-400">PRAKTIKER PROJECT</a>
            </h1>
        </section>
        <section class="relative w-[40vw] flex md:flex-row flex-col items-end md:justify-end md:items-center sm:w-[60vw]">
            <button id="menuButton" class="text-white text-2xl md:hidden">
                <i class="fa-solid fa-bars text-[17px]"></i>
            </button>
            <ul id="menuList" class="relative hidden text-[14px] md:text-[17px] font-bold md:inline text-center text-white">
                <a href="../index.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-house"></i> STRONA GŁÓWNA</li></a>
                <a href="../login.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-user"></i> LOGOWANIE</li></a>
                <a href="../support.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-circle-info"></i> POMOC</li></a>
            </ul>
            <script>
                const menuButton = document.getElementById('menuButton');
                const menuList = document.getElementById('menuList');
                menuButton.addEventListener('click', () => {
                    menuList.classList.toggle('hidden');
                })

                document.addEventListener('click', (event) => {
                    if (!menuButton.contains(event.target) && !menuList.contains(event.target)){
                        menuList.classList.add('hidden');
                    }
                })
            </script>
        </section>
    </header>
    <main class="w-[95vw] h-auto p-[30px] bg-gray-700 text-white flex flex-col items-center justify-center md:max-xl:flex">
        <section class="md:w-[47.5vw] w-[67.5vw] flex flex-col items-center justify-center mb-[3vh]">
            <h2 class="font-bold md:text-2xl text-[19px] md:mb-[1vh] mb-[3vh] text-center">Witaj w panelu dodawaniu utworów!</h2>
            <form action="addTrack.php" method="post" enctype="multipart/form-data" class="flex flex-col box-border">
                <label for="song">
                    <span class="font-bold"><i class="fa-solid fa-upload"></i>&nbsp;WYBIERZ UTWÓR:</span><br>
                    <input type="file" name="song" id="song" accept=".mp3, .wav, .flac, .ogg" required
                        class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[25vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-white hover:text-emerald-400 transition-all"
                    >
                </label>
                <label for="performer">
                    <span class="font-bold"><i class="fa-solid fa-user"></i>&nbsp;WYKONAWCA:</span><br>
                    <input type="text" id="performer" name="performer" placeholder="Wpisz wykonawce" required
                        class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[25vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-emerald-400 transition-all"
                    >
                </label>
                <label for="title">
                    <span class="font-bold"><i class="fa-solid fa-envelope"></i>&nbsp;TYTUŁ:</span><br>
                    <input type="text" id="title" name="title" placeholder="Wpisz tytuł" required
                        class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[25vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-emerald-400 transition-all"
                    >
                </label>
                <label for="cover_link">
                    <span class="font-bold"><i class="fa-solid fa-upload"></i>&nbsp;OKŁADKA UTWORU:</span><br>
                    <input type="file" name="cover_link" id="cover_link" accept=".png, .jpeg, .jpg" required
                        class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[25vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-white hover:text-emerald-400 transition-all"
                    >
                </label>
                <input type="submit" value="PRZEŚLIJ" class="outline-3 outline-solid outline-offset-2 font-bold p-[4px] w-[40vw] md:w-[25vw] mt-[2vh] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">
            </form>
        </section>
        <a href="admin.php">
            <button class="outline-3 outline-solid outline-offset-2 font-bold p-[6px] w-[30vw] md:w-[15vw] mb-[1vh] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">GŁÓWNY PANEL</button>
        </a>
    </main>
    <footer class="bg-gray-600 w-[100vw] text-white p-[20px] flex flex-col justify-center items-center md:flex-row">
        <p id="copyrights" class="font-bold md:w-[50vw] w-[75vw] flex justify-center items-center text-center"></p>
    </footer>
    <script>
        function updateClock(){
            const current_date = new Date();
            var year = current_date.getFullYear();
            document.getElementById("copyrights").innerHTML = `<img src="../img/Herb_Leszno.png" class="lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><img src="../img/logo-Leszno.png" class="ml-[1vw] lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><span class="md:ml-[1vw] ml-[3vw]">&copy; ${year} PRAKTIKER PROJECT</span><span class="md:ml-[1vw] ml-[3vw]"><a href="../policy.html" class="hover:text-emerald-400">Polityka prywatności</a></span>`;
        }
        updateClock();
    </script>
</body>
</html>