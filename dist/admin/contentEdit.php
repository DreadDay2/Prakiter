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
    
    $query_prompt = "SELECT * FROM users";
    $query = mysqli_query($connection, $query_prompt);
    mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRAKTIKER - ZARZĄDZANIE TREŚCIĄ</title>
    <link href="../style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
</head>
<body class="bg-gray-800 justify-center items-center flex flex-col size-full overflow-x-hidden">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
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
            <h2 class="font-bold md:text-2xl text-[19px] md:mb-[1%] mb-[3%] text-center">Witaj w panelu edycji strony głównej!</h2>
            <div id="toolbar-container" class="bg-gray-300">
                <span class="ql-formats">
                    <select class="ql-font"></select>
                    <select class="ql-size"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <button class="ql-strike"></button>
                </span>
                <span class="ql-formats">
                    <select class="ql-color"></select>
                    <select class="ql-background"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-script" value="sub"></button>
                    <button class="ql-script" value="super"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-header" value="1"></button>
                    <button class="ql-header" value="2"></button>
                    <button class="ql-blockquote"></button>
                    <button class="ql-code-block"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                    <button class="ql-indent" value="-1"></button>
                    <button class="ql-indent" value="+1"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-direction" value="rtl"></button>
                    <select class="ql-align"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-link"></button>
                    <button class="ql-image"></button>
                    <button class="ql-video"></button>
                    <button class="ql-formula"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-clean"></button>
                </span>
            </div>
            <div id="editor" class="text-white w-full"></div>
            <button onclick="saveContent()" class="outline-3 outline-solid outline-offset-2 font-bold p-[4px] w-[25vw] md:w-[15vw] mt-[2vh] text-emerald-400 outline-white hover:bg-gray-800 hover:outline-emerald-400 hover:text-white">ZAPISZ</button>
            <script>
                const quill = new Quill('#editor', {
                    modules: {
                    syntax: true,
                    toolbar: '#toolbar-container',
                    },
                    theme: 'snow',
                });

                fetch('getContent.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        quill.root.innerHTML = data.content;
                    }
                });

                function saveContent(){
                    const content = quill.root.innerHTML;

                    fetch('saveContent.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({content})
                    })
                    .then(response => response.text())
                    .then(data => alert('Zapisano!'))
                    .catch(error => console.error('Issue: ', error));
                }
            </script>
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
            var date = current_date.toLocaleString("pl-PL", {dateStyle: "long"});
            document.getElementById("copyrights").innerHTML = `<img src="../img/Herb_Leszno.png" class="lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><img src="../img/logo-Leszno.png" class="ml-[1vw] lg:w-[5%] lg:h-[5%] w-[10%] h-[10%]"><span class="md:ml-[1vw] ml-[3vw]">&copy; ${year} PRAKTIKER PROJECT</span><span class="md:ml-[1vw] ml-[3vw]"><a href="../policy.html" class="hover:text-emerald-400">Polityka prywatności</a></span>`;
        }
        updateClock();
    </script>
</body>
</html>