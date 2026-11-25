<?php
// Force clear Acorn view cache

$cache_dir = '/home/user/Local Sites/fds/app/wp-content/cache/acorn';
$storage_dir = '/home/user/Local Sites/fds/app/wp-content/cache/acorn/framework/views';

if (is_dir($storage_dir)) {
    $files = scandir($storage_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $storage_dir . '/' . $file;
            if (is_file($path)) {
                unlink($path);
            } elseif (is_dir($path)) {
                system("rm -rf $path");
            }
        }
    }
    echo "✓ Acorn view cache cleared\n";
} else {
    echo "⚠ Acorn cache dir not found\n";
}

// Force touch functions.php to reload Acorn bootstrap
touch('/home/user/Local Sites/fds/app/public/fds-theme/functions.php');
echo "✓ Touched functions.php to trigger Acorn reload\n";
?>
