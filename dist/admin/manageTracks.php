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

    $query_prompt = "SELECT * FROM songs";
    $query = mysqli_query($connection, $query_prompt);
    mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRAKTIKER - ZARZĄDZANIE UTWORAMI</title>
    <link href="../style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="bg-gray-800 justify-center items-center flex flex-col size-full overflow-x-hidden">
    <?php
        if(isset($_SESSION['deleted_song'])){
            echo '
                <section id="announcement" class="absolute p-6 bg-gray-900 text-white rounded-xl shadow-xl flex flex-col items-center justify-center">
                    <p class="font-bold text-2xl">UTWÓR ZOSTAŁ USUNIĘTY</p>
                    <button id="button" onclick="delDiv()" class="outline-3 outline-solid outline-offset-2 font-bold p-[6px] w-[30vw] md:w-[15vw] mt-[3vh] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">OK</button>
                </section>
            ';
            unset($_SESSION['deleted_song']);
        }
    ?>
    <script>
        function delDiv() {
            fetch('deletingTrack.php')
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
            <h2 class="font-bold md:text-2xl text-[19px] md:mb-[2%] mb-[3%] text-center">Witaj w panelu zarządzania utworami!</h2>
            <div class="overflow-x-auto">
    <div class="w-full border border-gray-300 rounded-lg">
        <div class="flex flex-col md:grid grid-cols-5 bg-gray-800 p-3 text-white font-bold text-center">
            <div class="p-4">LP.</div>
            <div class="p-4">Wykonawca</div>
            <div class="p-4">Tytuł</div>
            <div class="p-4">Usuń</div>
            <div class="p-4">Edytuj</div>
        </div>
        <?php
            function generateTableRow($lp, $song_performer, $song_title, $song_cover_link, $song_link, $song_id) {
                return '
                    <div class="flex flex-col md:grid grid-cols-5 odd:bg-gray-600 even:bg-gray-500 text-center p-3 border-gray-400">
                        <div class="p-2">'.$lp.'</div>
                        <div class="p-2">'.htmlspecialchars($song_performer).'</div>
                        <div class="p-2">'.htmlspecialchars($song_title).'</div>
                        <div class="p-2">
                            <a href="deletingTrack.php?id='.htmlspecialchars($song_id).'">
                                <button class="outline-3 outline-solid outline-offset-2 font-bold p-4 text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white py-2 rounded-md w-full md:w-auto">USUŃ</button>
                            </a>
                        </div>
                        <div class="p-2">
                            <button onclick="editSong(\''.$song_id.'\', \''.$song_performer.'\', \''.$song_title.'\', \''.$song_cover_link.'\', \'' . $song_link . '\')"
                                class="outline-3 outline-solid outline-offset-2 font-bold p-4 text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white py-2 rounded-md w-full md:w-auto">EDYTUJ</button>
                        </div>
                    </div>
                ';
            }
            $lp = 1;
            while($result = mysqli_fetch_assoc($query)){
                $song_performer = $result['performer'];
                $song_title = $result['title'];
                $song_cover_link = $result['cover_link'];
                $song_link = $result['link'];
                echo generateTableRow($lp, $song_performer, $song_title, $song_cover_link, $song_link, $result['id']);
                $lp++;
            }
            echo "</div>";
            echo "</section>";
            ?>
        <section id="edit_form" class="absolute hidden p-6 bg-gray-900 text-white rounded-xl shadow-xl">
            <div id="close_form" class ="w-full text-right">
                <button onclick="hideForm()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <script>
                function hideForm(){
                    const edit_form = document.getElementById('edit_form');
                    edit_form.classList.add('hidden');
                }
            </script>
            <form action="editingTrack.php" method="post">
                <input type="hidden" id="song_id" name="song_id">
                <label for="new_performer">
                        <span class="font-bold"><i class="fa-solid fa-user"></i>&nbsp;NOWY WYKONAWCA:</span><br>
                        <input type="text" id="new_performer" name="new_performer" placeholder="Nowy wykonawca"
                            class="outline-none border-2 border-gray-700 rounded-md font-bold mt-2 mb-[2vh] p-2 w-full focus:border-emerald-400 focus:bg-gray-800 hover:border-emerald-400 transition-all"
                        >
                </label>
                <label for="new_title">
                        <span class="font-bold"><i class="fa-solid fa-envelope"></i>&nbsp;NOWY TYTUŁ:</span><br>
                        <input type="text" id="new_title" name="new_title" placeholder="Nowy tytuł"
                            class="outline-none border-2 border-gray-700 rounded-md font-bold mt-2 mb-[2vh] p-2 w-full focus:border-emerald-400 focus:bg-gray-800 hover:border-emerald-400 transition-all"
                        >
                </label>
                <label for="new_cover_link">
                        <span class="font-bold"><i class="fa-solid fa-envelope"></i>&nbsp;NOWA OKŁADKA:</span><br>
                        <input type="text" id="new_cover_link" name="new_cover_link" placeholder="Nowa okładka"
                            class="outline-none border-2 border-gray-700 rounded-md font-bold mt-2 mb-[2vh] p-2 w-full focus:border-emerald-400 focus:bg-gray-800 hover:border-emerald-400 transition-all"
                        >
                </label>
                <label for="new_link">
                        <span class="font-bold"><i class="fa-solid fa-envelope"></i>&nbsp;NOWA ŚCIEŻKA PLIKU:</span><br>
                        <input type="text" id="new_link" name="new_link" placeholder="Nowa ścieżka pliku"
                            class="outline-none border-2 border-gray-700 rounded-md font-bold mt-2 mb-[2vh] p-2 w-full focus:border-emerald-400 focus:bg-gray-800 hover:border-emerald-400 transition-all"
                        >
                </label>
                <button type="submit" class="outline-3 outline-solid outline-offset-2 font-bold p-[4px] w-[25vw] md:w-[15vw] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">ZAPISZ ZMIANY</button>
            </form>
        </section>
        <a href="admin.php">
            <button class="outline-3 outline-solid outline-offset-2 font-bold p-[6px] w-[30vw] md:w-[15vw] mb-[1vh] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">GŁÓWNY PANEL</button>
        </a>
        <script>
            function editSong(id, performer, title, cover_link, link) {
                // Pobranie formularza i wypełnienie go danymi
                const form = document.getElementById('edit_form');
                form.classList.remove('hidden'); // Pokaż formularz

                // Ustawianie wartości w formularzu
                document.getElementById('song_id').value = id;
                document.getElementById('new_performer').value = performer;
                document.getElementById('new_title').value = title;
                document.getElementById('new_cover_link').value = cover_link;
                document.getElementById("new_link").value = link;
                // Przewinięcie do formularza
                form.scrollIntoView({ behavior: 'smooth' });
            }
        </script>
    </main>
    <footer class="bg-gray-600 w-[100vw] text-white p-[20px] flex flex-col justify-center items-center md:flex-row">
        <p id="copyrights" class="font-bold md:w-[50vw] w-[75vw] flex justify-center items-center text-center"></p>
    </footer>
    <script>
        function updateClock(){
            const current_date = new Date();
            var year = current_date.getFullYear();
            var date = current_date.toLocaleString("pl-PL", {dateStyle: "long"});
            document.getElementById("copyrights").innerHTML = `<img src="../img/Herb_Leszno.png" class="lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><img src="../img/logo-Leszno.png" class="ml-[1vw] lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><span class="md:ml-[1vw] ml-[3vw]">&copy; ${year} PRAKTIKER PROJECT</span><span class="md:ml-[1vw] ml-[3vw]"><a href="../policy.html" class="hover:text-emerald-400">Polityka prywatności</a></span>`;
        }
        updateClock();
    </script>
</body>
</html>