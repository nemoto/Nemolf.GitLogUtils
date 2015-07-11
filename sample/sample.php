<?php
require('vendor/autoload.php');
use vendor\Nemolf\GitLogUtils as utils;

// Aura.Autoloader
$loader = new \Aura\Autoload\Loader();
$loader->register();
$loader->addPrefix('vendor\Nemolf\GitLogUtils', 'vendor/nemolf/gitlogutils');

// settings to get log.
$git_path = "/PATH/TO/YOUR/REPOSITORY";
$after = '2015-06-30T00:00:00+0700';
$before= '2015-07-10T23:59:59+0700';

// create parser
$parser = new utils\GitLogUtils;
// get / parse log
$ret = $parser->git_log_parser($git_path, $after, $before);
// aggreate data by commetter
print_r(sortByCommitter($ret));



function sortByCommitter(array $gitLogData) 
{
    $template = array('times'        => null,
                      'max_lines'    => null,
                      'max_length'   => null,
                      'total_lines'  => null,
                      'total_length' => null,
    );
    $data = array();

    foreach($gitLogData as $log) {
        if (!array_key_exists($log->committer, $data)) {
            $data[$log->committer] = $template;
        }

        $data[$log->committer]['times']++;
        if ($log->getCommentLines() > $data[$log->committer]['max_lines']) {
            $data[$log->committer]['max_lines'] = $log->getCommentLines();
        }
        if ($log->getCommentLength() > $data[$log->committer]['max_length']) {
            $data[$log->committer]['max_length'] = $log->getCommentLength();
        }
        $data[$log->committer]['total_lines'] += $log->getCommentLines();
        $data[$log->committer]['total_length'] += $log->getCommentLength();
    }
    return $data;
}
