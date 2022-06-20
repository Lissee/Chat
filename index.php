<?php
date_default_timezone_set("Asia/Vladivostok");

$filePath = __DIR__ . '/messages.json';
$messages = json_decode(file_get_contents($filePath), true);
$isAuthorized = false;
$users = [
    "Liss_see" => "1234",
    "Sasha" => "1111",
    "Egor" => "3333",
    "angry" => "6666"
];

function jsonToMessage(string $login, string $date, string $message): string
{
    return '
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">' . $login . '</h5>
                <h6 class="card-subtitle mb-2 text-muted">' . $date . '</h6>
                <p class="card-text">' . $message . '</p>
            </div>
        </div>';
}

if (!key_exists($_GET["login"], $users) || $users[$_GET["login"]] != $_GET["password"]) {
    $login = 'Do not authorized';
} else {
    $login = $_GET["login"];
    $isAuthorized = true;
}

if ($isAuthorized && isset($_POST["message"])) {
    $messages[] = [
        "date" => date("d.m.Y H:i"),
        "login" => $login,
        "message" => htmlspecialchars($_POST["message"], ENT_QUOTES)
    ];
    file_put_contents($filePath, json_encode($messages));
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Style/Style.css" type="text/css">
    <title>Chat</title>

</head>
<body>

<div class="container-lg forms">
    <form method="GET">
        <div class="row">
            <div class="mb-3 col">
                <label for="login" class="form-label">Login</label>
                <input name="login" placeholder="Login" type="text" class="form-control" id="login">
            </div>
            <div class="mb-3 col">
                <label for="password" class="form-label">Password</label>
                <input name="password" placeholder="Password" type="password" class="form-control" id="password">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" id="logIn">Sing in</button>
    </form>
    <?php
    echo $login;
    ?>
</div>

<div class="container-lg">
    <div class="messages" id="messages">
        <?php
        foreach ($messages as $message) {
            echo jsonToMessage((string)$message["login"], (string)$message["date"], (string)$message["message"]);
        }
        ?>
    </div>
</div>

<?php
if ($isAuthorized) {
    echo '
    <div class="container-lg forms">
    <form method="POST">
            <div class="mb-3">
                <label for="login" class="form-label">Message</label>
                <input name="message" placeholder="Message" type="text" class="form-control" id="message">
            </div>
        <button type="submit" class="btn btn-primary" id="send">Enter</button>
    </form>
</div>';
}
?>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="Script/JS.js"></script>

</body>
</html>