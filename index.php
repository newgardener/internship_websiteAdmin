<?php

$ip = $_SERVER['REMOTE_ADDR'];
$referer = $_SERVER['HTTP_REFERER'];
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    // 로그인 페이지
    case '/':
        require __DIR__ .'/app/login.html';
        break;
    // 뉴스 관리 페이지
    case '/news/write':
        require __DIR__ .'/app/add-news.html';
        break;
    case '/news/update':
        require __DIR__ . '/app/update-news.html';
        break;
    
    // 팝업 관리 페이지
    case '/popup/write':
        require __DIR__ . '/app/add-popup.html';
        break;

}
