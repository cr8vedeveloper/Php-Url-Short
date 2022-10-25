<?php

require_once "config.php";

function getUniqueRandomString($length) : string {

    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = "";
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    if (!randomStringIsUnique($randomString)) {
        return getUniqueRandomString($length);
    }
    return $randomString;
}

function goToUrl($key) {

    $conn = getConnection();
    $sql = "SELECT * FROM link WHERE key = :key LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':key', $key);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($row['key']) && $row['key'] === $key) {

        $sql = "UPDATE link Set last_access = :last_access, hits = hits + 1 WHERE key = :key";
        $stmt = $conn->prepare($sql);
        $last_access = date('Y-m-d H:i:s');
        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':last_access', $last_access);
        $stmt->execute();
        header("Location: " . $row['url'], true, 302);
        exit(); // https://stackoverflow.com/questions/768431/how-do-i-make-a-redirect-in-php
    }

}

function urlAlreadyShortened($url) : bool {

    $conn = getConnection();
    $sql = "SELECT key, url FROM link WHERE url = :url";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':url', $url);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['url'] === $url) {
            header("Location: " . BASE_URL . "/?slug=" . $row['key']);
            exit();
        }
    }
    return false;
}

function urlIsCorrect($url) : bool {

    $startsWithHttp = preg_match("/^(https?:\/\/)(\S*)$/m", trim($url));
    return $startsWithHttp === 1;
}

function randomStringIsUnique($key) : bool {

    $conn = getConnection();
    $sql = "SELECT key FROM link WHERE key = :key";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':key', $key);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['key'] === $key) {
            return false;
        }
    }
    return true;
}

function slugMeetsRequirements($slug) : bool {

    return strlen(trim($slug, "/")) >= SLUG_LEN;
}

function getConnection() : PDO {

    try {
        $conn = new PDO("sqlite:" . __DIR__ . "/data.sqlite");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;

    } catch (PDOException $e) {
        throw new RuntimeException("Can not connect.");
    }
}

function addUrlToDatabase($u) : string {

    $key = getUniqueRandomString(SLUG_LEN);
    $url = substr($u, 0, 2048);
    $created_at = date('Y-m-d H:i:s');

    $conn = getConnection();
    $sql = "INSERT INTO link (key, url, created_at, lifetime, last_access, hits) 
            VALUES (:key, :url, :created_at, null, null, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':key', $key);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->execute();

    return $key;
}