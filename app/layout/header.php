<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/bootstrap.css" />
    <link rel="stylesheet" href="/public/css/main.css" />
    <script src="/public/js/bootstrap.js"></script>
    <title>Сделаем лучше вместе</title>
</head>
<body>
<div class="container" id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-light pl-0 pr-0">
        <a class="navbar-brand" href="/">
            <img src="/public/logo/logo.png" width="30" height="30" class="d-inline-block align-top mr-2" alt="logo">
            Сделаем вместе!
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if (Session::getInstance()->isLoggedIn()): ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/account">Личный кабинет</a>
                </li>
            </ul>
            <a href="/logout" class="my-2 my-lg-0">Выйти</a>
        </div>
        <?php else: ?>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
                <a href="/login" class="my-2 my-lg-0">Войти</a>
            </div>
        <?php endif; ?>
    </nav>
