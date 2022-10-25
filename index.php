<?php
       ini_set("display_errors",1);
       error_reporting(E_ALL);
require_once "functions.php";
require_once "config.php";

$slug = "";

        if (isset($_GET['GoToURL'])) {
    // var_dump($_SERVER['REQUEST_URI']);
   //  $slug = substr(trim($_SERVER['REQUEST_URI'], "/"), 0, 6);
    $slug = ltrim($_GET['GoToURL'], DELETE_URL);
    // var_dump($slug);
    goToUrl($slug);
}
if (isset($_POST['url']) && isset($_POST['password']) && $_POST['password'] === PASSWORD && urlIsCorrect($_POST['url']) && !urlAlreadyShortened($_POST['url'])) {
    $slug = addUrlToDatabase($_POST['url']);
    header("Location: " . BASE_URL . "/?slug=" . $slug);
    exit();
}

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="generator" content="Hugo 0.80.0">
    <title>URL shortener</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <meta name="theme-color" content="#563d7c">

    <link href="styles.css" rel="stylesheet">
</head>
<body>
         <input type="text" id="currencyVal" value="123" /> <button class="btn" onclick="copyvalue()">boo!</button><br />
        
<form class="form-shorten-url" action="index.php" method="post">
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal"><a href="https://link.that.ee" class="text-reset">URL shortener</a></h1>
        <p>Shorten urls. Github repo can be found here.</a></p>
    </div>
    <div class="form-label-group">
        <input type="text" id="url"  name="url" class="form-control" placeholder="URL to shorten" required autofocus readonly>
        <label for="url">URL to shorten</label>
        <small id="urlHelp" class="form-text text-muted">Needs to begin with https:// or http://</small>

    </div>
    <div class="form-label-group mb-3">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <label for="password">Password</label>
        <small id="passwordHelp" class="form-text text-muted">
            If you want to use it then you need password. If you don't have password then probably
            it would be good to set up your own. See the git repo.
        </small>
    </div>
    <div class="form-label-group mb-3">
        <?php
        if (isset($_GET['slug'])) {
            echo "Just created: <code>" . BASE_URL . "/php-url-shortener-main/?GoToURL=$slug</code>";
        }
        ?>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>

</form>
    <script type="text/javascript">

        let currencyval = document.getElementById("currencyVal");
        let currencyCopy = document.getElementById("url");
                var myStr = 'quick_brown_fox';
        var newStr = myStr.replace(/_/g, "-");
        
        function copyvalue() {

            currencyCopy.value = newStr; //currencyval.value;
            console.log(currencyCopy.value);
        }
    </script>
</body>
</html>
