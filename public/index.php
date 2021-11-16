<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

use Illuminate\Container\Container;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

class Log
{
    protected File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }
}

class File
{
    protected Sys $sys;

    public function __construct(Sys $sys)
    {
        $this->sys = $sys;
    }
}

class Sys
{

}

function dump($vars, $label = '', $return = false): ?string
{
    if (ini_get('html_errors')) {
        $content = "<pre>\n";
        if ($label != '') {
            $content .= "<strong>{
    $label} :</strong>\n";
        }
        $content .= htmlspecialchars(print_r($vars, true));
        $content .= "\n</pre>\n";
    } else {
        $content = $label . " :\n" . print_r($vars, true);
    }
    if ($return) { return $content; }
    echo $content;
    return null;
}

//var_dump(phpinfo());

$container = new Container();
$obj = $container->make(Log::class);
dump($obj);