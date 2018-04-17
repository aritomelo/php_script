<?php
/*
 * Replace a string within file.
 */
function replace_in_file($needle, $replace, $path_to_file) {
  $file_contents = file_get_contents($path_to_file);
  $file_contents = str_replace($needle, $replace, $file_contents);
  file_put_contents($path_to_file, $file_contents);
}

$repo_root = getcwd();
$repo_name = basename($repo_root);
$needle = "drupal-module-template";

// Replace all occurrences of drupal-module-template with the module name.
$iter = new RecursiveIteratorIterator(
  new RecursiveDirectoryIterator($repo_root, RecursiveDirectoryIterator::SKIP_DOTS),
  RecursiveIteratorIterator::SELF_FIRST,
  RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
);

echo PHP_EOL . "Replace all occurrences of \"drupal-module-template\" with the $repo_name!" . PHP_EOL;
foreach ($iter as $path_to_file => $dir) {
  // Skip this script file!
  if (is_file($path_to_file) && $path_to_file != __FILE__) {
    replace_in_file($needle, $repo_name, $path_to_file);
  }
}

// Rename the info file.
echo PHP_EOL . 'Rename the info file!' . PHP_EOL;
rename("drupal-module-template.info.yml", "$repo_name.info.yml");

// Replace the travis.yml with travis.module.yml
echo PHP_EOL . 'Replace "travis.module.yml" with .travis.yml!' . PHP_EOL;
rename(".travis.module.yml", ".travis.yml");

// Rename the README.md with README.module.md
echo PHP_EOL . 'Rename "README.module.md" to README.md!' . PHP_EOL;
rename('README.module.md', 'README.md');

// Remove the call of replace-names.sh in composer and delete it.
echo PHP_EOL . 'Update composer file!' . PHP_EOL;
replace_in_file('php -f testFileReplace.php' ,'' ,'./composer.json');
// Delete the script file.
echo PHP_EOL . 'Remove the script file!' . PHP_EOL;
// unlink('testFileReplace.php');