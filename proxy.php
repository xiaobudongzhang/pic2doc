<?php
if (file_exists(__DIR__ . '/vendor/autoload.php'))
{
    include __DIR__ . '/vendor/autoload.php';
}

$url = @$_POST['url'];
$req = Requests::get($url);

if (isset($req->body))
{
    echo $req->body;
}

exit;