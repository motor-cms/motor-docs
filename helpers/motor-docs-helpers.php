<?php

/**
 * Simple method to crawl all potential packages and paths for documentation files
 * Not great but works
 *
 * @return array
 */
function getAllDocumentationFiles()
{
    // Get local and scoped view paths from all packages
    $app      = app();
    $paths    = $app['view']->getFinder()->getPaths();
    $hints    = $app['view']->getFinder()->getHints();

    // Add local paths to hint paths
    $hints['local'] = $paths;

    $fileList = [];


    foreach ($hints as $package => $paths) {
        foreach ($paths as $path) {
            $path  = str_replace('/views', '/documentation', $path);
            if (is_dir($path)) {
                $files = scandir($path);
                foreach ($files as $file) {
                    if (substr($file, -3) == '.md') {
                        // Exclude files with _ at the beginning of the filename
                        if (strrpos($file, '.md') && substr($file, 0, 1) !== '_') {
                            $fileList[] = realpath($path.'/'.$file);
                        }
                    }
                }
            }
        }
    }
    return $fileList;
}

/**
 * Find the requested page replace links with the correct route
 *
 * @param $file
 * @return mixed|string
 */
function documentation($file)
{
    // Get local and scoped view paths from all packages
    $app   = app();
    $paths = $app['view']->getFinder()->getPaths();
    $hints = $app['view']->getFinder()->getHints();

    // Check if we're dealing with a package
    $split = explode('::', $file);

    // get route
    $route = config('motor-docs.route', 'documentation');

    if (count($split) > 1) {
        $file = str_replace('.', '/', $split[1]);
        foreach ($hints as $package => $paths) {
            if ($package == $split[0]) {
                foreach ($paths as $path) {
                    $path = str_replace('/views', '/documentation', $path);
                    if (file_exists($path.'/'.$file.'.md')) {
                        return str_replace('{{route}}', $route, file_get_contents($path.'/'.$file.'.md'));
                    }
                }
            }
        }
    } else {
        $file = str_replace('.', '/', $file);
        foreach ($paths as $path) {
            $path = str_replace('/views', '/documentation', $path);
            if (file_exists($path.'/'.$file.'.md')) {
                return str_replace('{{route}}', $route, file_get_contents($path.'/'.$file.'.md'));
            }
        }
    }

    return '';
}
