<?php

include ('src/manifest.php');

$zip = new ZipArchive();
$fileName = $manifest['key'] . '-v' . $manifest['version'] . '.zip';

$newFileName = './../' . $fileName;

//if (!copy('src/manifest.php', 'manifest.tpl.php')) {
//    echo "failed to copy $file...\n";
//}

exec('rm -f manifest.tpl.php');
exec('ln -s src/manifest.php manifest.tpl.php');

$zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$rootPath = realpath(__DIR__) . '/src';
// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    // Skip directories (they would be added automatically)
    if (!$file->isDir()) {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        if ('zipper.php' == $file->getFilename()) {
            continue;
        }
        if ('.sucode' == $file->getFilename()) {
            continue;
        }
        if ('zip' == $file->getExtension()) {
            continue;
        }
        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();
echo date('H:i:s', time()) . ' Created zip file  : ' . $fileName . "\n";
