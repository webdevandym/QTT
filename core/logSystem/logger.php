<?php

//@file path to log file
//@context string to add in log
//@way false-> add to bottom , true -> add to top of file
//@maxsize max file size in KB
//@levelclass deep of scan runer class
//@levelmethod deep of scan runer method

namespace core\logSystem;

abstract class logger
{
    protected $levelclass;
    protected $levelmethod;
    protected $file;
    protected $way;
    protected $templ;
    protected $context;
    protected $maxsize;
    protected $content;

    public function __construct($file, $context, $way, $templ, $timeZone, $maxsize = 50, $levelclass = 1, $levelmethod = 1)
    {
        $this->file = $file;
        $this->way = $way;
        $this->templ = $templ;
        $this->context = $context;
        $this->maxsize = $maxsize;
        $this->levelclass = $levelclass;
        $this->levelmethod = $levelmethod;
        date_default_timezone_set($timeZone ? $timeZone : 'UTC');
    }

    abstract public function writeLog();

    abstract protected function checkfile();

    protected function returnRunerFunc()
    {
        return 'Class -> <i>'.str_replace('\\', '\/', $this::treeFinder($this->levelclass, 'class')).' </i>:: method -> <i>'.$this::treeFinder($this->levelmethod, 'function').'()</i>';
    }

    protected static function treeFinder($level, $obj)
    {
        for ($i = $level + 4; $i !== 0; --$i) {
            if (isset(debug_backtrace()[$i])) {
                $objInTree = debug_backtrace()[$i][$obj];
            }

            if (isset($objInTree)) {
                return $objInTree;
            }
        }

        return 'fail';
    }
}
