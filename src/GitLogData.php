<?php
namespace Vender\Nemolf\GitLogUtils;

/**
 * GitLogData
 *
 * simple data container of git log
 * for easy calculation for langth/lines of commit log.
 */
class GitLogData
{
    public  $type = null;           // Type of the commit (commit, merge..)
    public  $author = null;         // Author of the souce
    public  $committer = null;      // Committer of this commit
    public  $date = null;           // date of this commit
    public  $comment = null;        // comment of this commit
    private $comment_lines = null;  // comment lines of the comment
    private $comment_length = null; // comment length of the commit.

    /**
     * constructer
     *
     * do nothing
     */
    public function __construct()
    {
    }

    /**
     * clear all properties as null
     * 
     */
    public function clear()
    {
        $this->type = null;
        $this->author = null;
        $this->committer = null;
        $this->date = null;
        $this->comment = null;
        $this->comment_lines = null;
        $this->comment_length = null;
    }

    /**
     * count commment lines
     *
     * if first calculation, store the result to $this->comment_lines
     */
    public function getCommentLines()
    {
        if ($this->comment_lines === null && is_array($this->comment)) {
            $this->comment_lines = count($this->comment);
        }

        return ($this->comment_lines === null)? 0: $this->comment_lines;
    }

    /**
     * count commment length
     *
     * if first calculation, store the result to $this->comment_length
     */
    public function getCommentLength()
    {
        if ($this->comment_length === null && is_array($this->comment)) {
            $this->comment_length = 0;
            foreach($this->comment as $line) {
                $this->comment_length += mb_strlen(trim($line));
            }
        }

        return ($this->comment_length === null)? 0: $this->comment_length;
    }

    /**
     * dump
     *
     * for debug
     */
    public function dump()
    {
        printf("Type %s\n", $this->type);
        printf("  Author(%s) Commiter(%s)\n", $this->author,$this->committer);
        printf("  comment line(%s) len(%s)\n",
               $this->getCommentLines(), $this->getCommentLength());
        printf("  %s\n", print_r($this->comment, true));
        printf("--\n");
    }

}
