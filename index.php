<?php
ob_start();
$directory = getcwd();
$step = $_GET["step"];
//echo $directory. "/install/index.php";
if (file_exists($directory . "/install/index.php") && $step != 1) {
    //$step!=1;

    $step = 2;
}
if (!file_exists($directory . "/install/index.php") && $step != 1) {
    $step = 3;
}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Setup File</title>
        <link rel="stylesheet" href="css/install.css" type="text/css" />
        <link rel="stylesheet" href="css/buttons.css" type="text/css" />
    </head>
    <body id="wp-core-ui">
        <h1 id="logo"><a href="http://www.ladybirdweb.com/">Ladybird</a></h1>

        <?php
        switch ($step) {
            case 1:
                rrmdir($directory . "/install");
                ?>
                <p>All right, sparky! You&#8217;ve made it through the installation. </p>
                <?php
                break;
            case 2:
                header("Location: install/index.php");
                exit;

            case 3:
                ?>
                <p>Product already installed </p>
                <?php
                break;
        }
        ?>
    </body>
</html>