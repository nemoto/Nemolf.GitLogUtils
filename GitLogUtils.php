<?php
namespace Vender\Nemolf\GitLogUtils;
require_once('GitLogData.php');

/**
 * GitLogUtils
 *
 * parse git log
 *
 * @param $git_path  git repositiry path 
 * @param $after     from date : ie. '2015-06-30T23:59:59+0700'
 * @param $before    to date   : ie. '2015-06-30T23:59:59+0700'
 *
 * @return array of parsed git log.
 */
class GitLogUtils
{
    function git_log_parser($git_path, $after, $before)
    {
        $stdout = array();
        chdir($git_path);
        exec("git co master 2> /dev/null");
        exec("git pull 2> /dev/null");
        exec(sprintf('git log --after="%s" --before="%s" --format=full 2> /dev/null',
                     $after, $before
             ),
             $stdout
        );
        $logs = array();
        $current_log = new GitLogData();
        foreach($stdout as $each){
            if(strpos($each, 'commit')===0){
                if($current_log->type !== null){
                    array_push($logs, $current_log);
                    unset($current_log);
                    $current_log = new GitLogData();
                }
            }
            else if(strpos($each, 'Author:')===0){
                // get Author
                preg_match('/<(.*)>/', substr($each, strlen('Author:')),
                           $matches);
                $current_log->author = trim($matches[1]);
            }
            else if(strpos($each, 'Merge:')===0){
                // set type=Merge if the commit is 'merge source'
                $current_log->type = 'Merge';
            }
            else if(strpos($each, 'Commit')===0){
                // get committer
                preg_match('/<(.*)>/', substr($each, strlen('Commit:')),
                           $matches);
                $current_log->committer = trim($matches[1]);
            }
            else{
                if (!$each) continue;
                $current_log->comment[]  = trim($each);
            }
        }
        return $logs;
    }
}
