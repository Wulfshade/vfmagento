<?php
if(!isset($argv[1])) {
  exit('Must pass a version as argument');
}
$version = $argv[1];
$buildPath = '/tmp/vfmagento-'.$version;
passthru("rsync -avr --exclude='.idea' --exclude='.gitignore' --exclude='.travis.yml' --exclude='README.md' --exclude='vendor' --exclude='phpunit.*' --exclude='vf-build.php' --exclude='.git' --exclude='*Test.php' --delete-after . $buildPath");
passthru("rm -rf $buildPath/app/code/local/Elite/vendor/vehiclefits/library/tests");
passthru("rm -rf $buildPath/app/code/local/Elite/vendor/joshribakoff/php-csv-utils/tests");
passthru("cd $buildPath/app/code/local/Elite; composer install");
