<?php
$dir = '../../../assets/img/cases';
$images = scandir($dir);

unset($images[0]);
unset($images[1]);

file_put_contents('../../prefs/cases.txt', $images);
