<?php
require_once './vendor/autoload.php';
require_once './PageChecker.php';

$data = [
    'en.wikipedia.org' => [
        'Gone with the Wind'
    ],
    'loremipsum.io' => [
        'lorum ipsum',
        'lorem ipsum dolor sit amet'
    ]
];
$pageChecker = new PageChecker($data);
$pageChecker->init();
