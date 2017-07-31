<?php

namespace core\logSystem;

use core\logSystem\logger;

class logHTMLAdv extends logger
{
    public function writeLog()
    {
        $str = '<div>'.'['.$_SERVER['REMOTE_ADDR'].' => '.$_COOKIE['user'].'] <b>'.date('Y-m-d H:i:s\: ', time()).'</b>'.$this->returnRunerFunc().'<br>'.preg_replace('/[ \\t]+/', ' ', $this->context).'<hr><br></div>';

        if ($f = $this->checkfile()) {
            if ($this->way) {
                $str = str_replace('<body>', '<body>'.$str, $this->content);
            }

            fwrite($f, stripslashes($str));
            fclose($f);
        }
    }

    public function fileCleaner()
    {
        $vdata = (string) $this->templ !== '' ? file_get_contents($this->templ) : '';
        $f = fopen($this->file, 'w');
        fwrite($f, $vdata);
        fclose($f);
    }

    protected function checkfile()
    {
        if (!file_exists($this->file)) {
            return false;
        }

        if (filesize($this->file) / 1024 > $this->maxsize || !filesize($this->file)) {
            $this->fileCleaner();
        }

        if ($this->way) {
            $this->content = file_get_contents($this->file);
        }

        return fopen($this->file, ($this->way) ? 'w' : 'a');
    }
}
