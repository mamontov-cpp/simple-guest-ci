<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для хедера страницы */
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?=$title?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/assets/css/main.css" />
</head>
<body>
<a class="return" href="/">
    <header id="header" class="alt">
        <div class="inner">
            <h1><?=$mainTitle?></h1>
            <p><?=$auxTitle?></p>
        </div>
    </header>
</a>

<div id="wrapper">