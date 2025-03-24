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

    $query_prompt = "SELECT COUNT(id) AS amount_of_users FROM users";
    $query = mysqli_query($connection, $query_prompt);
    $query_prompt_2 = "SELECT COUNT(id) AS amount_of_tracks FROM songs";
    $query_2 = mysqli_query($connection, $query_prompt_2);
    $query_prompt_3 = "SELECT SUM(downloads) AS amount_of_downloads FROM songs";
    $query_3 = mysqli_query($connection, $query_prompt_3);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRAKTIKER - ADMIN</title>
    <link href="../style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body class="bg-gray-800 justify-center items-center flex flex-col size-full overflow-x-hidden">
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
                <?php
                    $adminTokenQuery = "SELECT * FROM users WHERE token = '$token' AND isAdmin = 1 AND login = '" . $_SESSION['ULogin'] . "'";
                    $adminTokenResult = mysqli_query($connection, $adminTokenQuery);
    
                    if ($adminTokenResult && mysqli_num_rows($adminTokenResult) > 0) {
                            $adminToken = true;
                    } else {
                            $adminToken = false;
                    }

                    if ($adminToken) {
                        echo '<a href="../admin/admin.php"><li class="md:inline md:ml-[2vw] hover:text-emerald-400">
                            <i class="fa-solid fa-user"></i> ' . htmlspecialchars($_SESSION['ULogin']) . '
                        </li></a>';
                    } else {
                        echo '<a href="#"><li class="md:inline md:ml-[2vw] hover:text-emerald-400">
                            <i class="fa-solid fa-user"></i> ' . htmlspecialchars($_SESSION['ULogin']) . '
                        </li></a>';
                    }

                    mysqli_close($connection);
                ?>
                <a href="../support.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-circle-info"></i> POMOC</li></a>
                <a style="float: right;"><form action="../phpFunc/logout.php" method="POST"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-right-to-bracket"></i>
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
        <section class="md:w-[47.5vw] w-[67.5vw] flex flex-col items-center justify-center mb-[3vh]">
            <h2 class="font-bold md:text-2xl text-[19px] md:mb-[1vh] mb-[3vh] text-center">Witaj w panelu statystyk!</h2>
            <?php
                $result = mysqli_fetch_assoc($query);
                $amount_of_users = $result['amount_of_users'];
                echo '<div class="flex flex-col md:flex-row md:mb-[3vh]">';
                echo '<div class="relative rounded-lg lg:mb-[0.5vh] mb-[3vh] w-auto">';
                echo '<div class="absolute inset-0 blur-[6px] rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400 w-full"></div>';
                echo '<div class="absolute inset-0 bg-gray-600 w-auto"></div>';
                echo '<p class="relative font-bold md:text-2xl text-[17px] p-[15px] text-center">Liczba userów: '.$amount_of_users.'</p>';
                echo '</div>';
            ?>
            <?php
                $result_2 = mysqli_fetch_assoc($query_2);
                $amount_of_tracks = $result_2['amount_of_tracks'];
                echo '<div class="relative rounded-lg lg:mb-[0.5vh] mb-[3vh] w-auto md:ml-[2vw]">';
                echo '<div class="absolute inset-0 blur-[6px] rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400 w-full"></div>';
                echo '<div class="absolute inset-0 bg-gray-600 w-auto"></div>';
                echo '<p class="relative font-bold md:text-2xl text-[17px] p-[15px] text-center">Liczba utworów: '.$amount_of_tracks.'</p>';
                echo '</div>';
            ?>
            <?php
                $result_3 = mysqli_fetch_assoc($query_3);
                $amount_of_downloads = $result_3['amount_of_downloads'];
                echo '<div class="relative rounded-lg lg:mb-[0.5vh] mb-[3vh] w-auto md:ml-[2vw]">';
                echo '<div class="absolute inset-0 blur-[6px] rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400 w-full"></div>';
                echo '<div class="absolute inset-0 bg-gray-600 w-auto"></div>';
                echo '<p class="relative font-bold md:text-2xl text-[17px] p-[15px] text-center">Liczba pobrań: '.$amount_of_downloads.'</p>';
                echo '</div>';
                echo '</div>';
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                fetch('statistics_data.php')
                    .then(response => response.json())
                    .then(data => {
                        if (!Array.isArray(data)) {
                            console.error("Błąd: Odpowiedź nie jest tablicą", data);
                            return;
                        }

                        google.charts.load('current', { packages: ['corechart', 'bar'] });
                        google.charts.setOnLoadCallback(() => drawChart(data));
                    })
                    .catch(error => console.error("Błąd ładowania danych:", error));

                function drawChart(chartData) {
                    var container = document.getElementById('chart');
                    if (!container) {
                        console.error("Błąd: Nie znaleziono elementu #chart");
                        return;
                    }

                    var data = google.visualization.arrayToDataTable(chartData);

                    var options = {
                        title: 'Liczba pobrań piosenek',
                        hAxis: { title: 'Liczba pobrań', minValue: 0 },
                        vAxis: { title: 'Piosenka' },
                        bars: 'horizontal',
                        legend: { position: "none" }
                    };

                    var chart = new google.visualization.BarChart(container);
                    chart.draw(data, options);
                }
            });
            </script>
            <div id="chart" style="width: 100%; height: 500px;"></div>
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