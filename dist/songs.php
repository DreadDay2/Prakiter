<?php
    session_start();
    require_once 'admin/config.php';
    $connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno() > 1){
        echo "Some issue:".mysqli_connect_error();
    }
    $query_prompt = "SELECT * FROM songs";
    $query = mysqli_query($connection, $query_prompt);
    $validToken = false;

    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        $tokenQuery = "SELECT * FROM users WHERE token = '$token'";
        $tokenResult = mysqli_query($connection, $tokenQuery);
    
        if ($tokenResult && mysqli_num_rows($tokenResult) > 0) {
            $validToken = true;
        } else {
            $validToken = false;
        }
    }


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRAKTIKER - UTWORY</title>
    <link href="./style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="bg-gray-800 justify-center items-center flex flex-col size-full overflow-x-hidden">
    <header class="relative md:p-[30px] p-[15px] w-screen flex flex-row">
        <div class="absolute inset-0 blur-xl rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
        <div class="absolute inset-0 bg-gray-600"></div>
        <section class="relative md:w-[35vw]">
            <h1 class="text-2xl  font-bold text-white sm:text-3xl">
                <a href="index.html" class="hover:text-emerald-400">PRAKTIKER PROJECT</a>
            </h1>
        </section>
        <section class="relative w-[40vw] flex md:flex-row flex-col items-end md:justify-end md:items-center sm:w-[60vw]">
            <button id="menuButton" class="text-white text-2xl md:hidden">
                <i class="fa-solid fa-bars text-[17px]"></i>
            </button>
            <ul id="menuList" class="relative hidden text-[14px] md:text-[17px] font-bold md:inline text-center text-white">
                <a href="index.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-house"></i> STRONA GŁÓWNA</li></a>
                <?php
                    $adminTokenQuery = "SELECT * FROM users WHERE token = '$token' AND isAdmin = 1 AND login = '" . $_SESSION['ULogin'] . "'";
                    $adminTokenResult = mysqli_query($connection, $adminTokenQuery);
    
                    if ($adminTokenResult && mysqli_num_rows($adminTokenResult) > 0) {
                            $adminToken = true;
                    } else {
                            $adminToken = false;
                    }

                    if ($adminToken) {
                        echo '<a href="admin/admin.php"><li class="md:inline md:ml-[2vw] hover:text-emerald-400">
                            <i class="fa-solid fa-user"></i> ' . htmlspecialchars($_SESSION['ULogin']) . '
                        </li></a>';
                    } else {
                        echo '<a href="#"><li class="md:inline md:ml-[2vw] hover:text-emerald-400">
                            <i class="fa-solid fa-user"></i> ' . htmlspecialchars($_SESSION['ULogin']) . '
                        </li></a>';
                    }

                    mysqli_close($connection);
                ?>
                <a href="support.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-circle-info"></i> POMOC</li></a>
                <a style="float: right;"><form action="phpFunc/logout.php" method="POST"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-right-to-bracket"></i>
                    <input type="submit" style="cursor: pointer;" name="logout" value="WYLOGUJ">
                </form></li></a>
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
        <section class="md:w-[47.5vw] w-[67.5vw] flex flex-col items-center justify-center mb-[2vh]">
            <h2 class="font-bold md:text-2xl text-[19px] mb-[3%] text-center">Przeglądaj utwory Romana Maciejewskiego</h2>
            <?php
            while($result = mysqli_fetch_assoc($query)){
                $song_id = $result['id'];
                $song_performer = $result['performer'];
                $song_title = $result['title'];
                $song_cover_link = $result['cover_link'];
                $song_link = $result['link'];
                echo '
                <div class="even:bg-gray-600 w-full box-border md:mb-[1vw] mb-[2vw] md:mt-[1vw] mt-[2vw]">
                    <div class="mb-4">
                        <img src="'.$song_cover_link.'" class="md:w-[40%] md:h-[40%] block mx-auto md:p-[10px]" alt="Okładka albumu">
                    </div>
                    <div class="p-[10px] text-center">
                        <h3 class="text-xl font-bold">'.$song_title.'</h3>
                    </div>
                    <div class="p-[10px]">
                        <p class="text-center w-auto">'.$song_performer.'</p>
                    </div>
                    <div class="p-[10px]">
                        <audio controls class="block mx-auto md:w-[40%] w-[80%]">
                            <source src="'.$song_link.'">
                            Twoja przeglądarka nie obsługuje typu pliku
                        </audio>
                    </div>
                    <div class="p-[10px] text-center">';
                if ($validToken == false) {
                    echo 'ZALOGUJ SIĘ, aby pobrać utwór';
                } else {
                    echo '
                        <a href="downloadSong.php?song_id='.$song_id.'" class="hover:text-emerald-400 font-bold">
                            <button class="outline-3 outline-solid outline-offset-2 font-bold text-emerald-400 outline-white p-[6px] w-[30vw] mb-[2vh] md:w-[15vw] hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">POBIERZ</button>
                        </a>
                    ';
                }
                echo '
                        </div>
                    </div>
                ';
            }
            ?>
        </section>
    </main>
    <footer class="relative bg-gray-600 w-[100vw] text-white p-[20px] flex flex-col justify-center items-center md:flex-row">
        <p id="copyrights" class="relative font-bold md:w-[50vw] w-[75vw] flex justify-center items-center text-center"></p>
    </footer>
    <script>
        function updateClock(){
            const current_date = new Date();
            var year = current_date.getFullYear();
            document.getElementById("copyrights").innerHTML = `<img src="img/Herb_Leszno.png" class="lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><img src="img/logo-Leszno.png" class="ml-[1vw] lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><span class="md:ml-[1vw] ml-[3vw]">&copy; ${year} PRAKTIKER PROJECT</span><span class="md:ml-[1vw] ml-[3vw]"><a href="policy.html" class="hover:text-emerald-400">Polityka prywatności</a></span>`;
        }
        updateClock();
    </script>
</body>
</html>
