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
$subst = "s/$repo_name/g";

// Replace all occurrences of drupal-module-template with the module name.
$iter = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($repo_root, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST,
    RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
);

$paths = array($repo_root);
foreach ($iter as $path_to_file => $dir) {
	// Skip this script file!
    if (is_file($path_to_file) && $path_to_file != __FILE__) {
		replace_in_file($str_needle, $repo_root, $path_to_file);
    }
}

// Rename the info file.
rename("drupal-module-template.info.yml", "$repo_name.info.yml");

// Replace the travis.yml with travis.module.yml
rename(".travis.module.yml", ".travis.yml");

// Replace the README.md with README.module.md
rename('README.module.md', 'README.md');

// Remove the call of replace-names.sh in composer and delete it.
replace_in_file('php -f testFileReplace.php' ,'' ,'./composer.json');
// Delete the script file.
//unlink('testFileReplace.php');