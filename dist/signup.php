<?php
session_start()
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRAKTIKER - ZAREJESTRUJ</title>
    <link href="./style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="bg-gray-800 justify-center items-center flex flex-col size-full overflow-x-hidden">
    <header class="relative md:p-[30px] p-[15px] w-screen flex flex-row">
        <div class="absolute inset-0 blur-xl rounded-lg bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
        <div class="absolute inset-0 bg-gray-600"></div>
        <section class="relative md:w-[35vw]">
            <h1 class="text-2xl font-bold text-white sm:text-3xl">
                <a href="index.html" class="hover:text-emerald-400">PRAKTIKER PROJECT</a>
            </h1>
        </section>
        <section class="relative w-[40vw] flex md:flex-row flex-col items-end md:justify-end md:items-center sm:w-[60vw]">
            <button id="menuButton" class="text-white text-2xl md:hidden">
                <i class="fa-solid fa-bars text-[17px]"></i>
            </button>
            <ul id="menuList" class="relative hidden text-[14px] md:text-[17px] font-bold md:inline text-center text-white">
                <a href="index.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-house"></i> STRONA GŁÓWNA</li></a>
                <a href="login.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-user"></i> LOGOWANIE</li></a>
                <a href="support.html"><li class="md:inline md:ml-[2vw] hover:text-emerald-400"><i class="fa-solid fa-circle-info"></i> POMOC</li></a>
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
        <section class="md:w-[47.5vw] w-[67.5vw] flex flex-col items-center justify-center mb-[6vh]">
            <h2 class="font-bold md:text-2xl text-[19px] mb-[3%] text-center">Utwórz konto aby uzyskać dostęp do utworów Romana Maciejewskiego</h2>
            <form method="post" action="phpFunc/signup.php" class="flex flex-col box-border">
                <label for="login" class="text-">
                    <span class="font-bold"><i class="fa-solid fa-user"></i>&nbsp;LOGIN:</span><br>
                    <input type="text" id="login" name="login" placeholder="Wpisz login" required
                    class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[20vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-emerald-400"
                    >
                </label>
                <label for="password">
                    <span class="font-bold"><i class="fa-solid fa-lock"></i>&nbsp;HASŁO:</span><br>
                    <input type="password" id="password" name="password" placeholder="Wpisz hasło" required
                        class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[20vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-emerald-400"
                    >
                </label>
                <label for="retype_password">
                    <span class="font-bold"><i class="fa-solid fa-lock"></i>&nbsp;POWTÓRZ HASŁO:</span><br>
                    <input type="password" id="retype_password" name="retype_password" placeholder="Wpisz hasło ponownie" required
                        class="outline-3 outline-solid outline-offset-2 font-bold mt-[3%] p-[5px] w-[40vw] md:w-[20vw] focus:outline-emerald-400 focus:bg-gray-800 hover:outline-emerald-400"
                    >
                </label>
                <input type="submit" value="ZAREJESTRUJ"
                    class="bg-emerald-400 text-white outline-3 outline-solid outline-white p-[6px] border-none font-bold drop-shadow-xl mt-[1vh]
                           hover:bg-gray-800 hover:outline-emerald-400 hover:text-white
                    ">
                <label style="color: red">
                    <?php
                        if(isset($_SESSION['error'])){
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        }
                    ?>
                </label>
            </form>
        </section>
    </main>
    <footer class="bg-gray-600 w-[100vw] text-white p-[20px] flex flex-col justify-center items-center md:flex-row">
        <p id="copyrights" class="font-bold md:w-[50vw] w-[75vw] flex justify-center items-center text-center"></p>
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