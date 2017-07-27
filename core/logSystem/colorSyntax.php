<?php

//set to constructor language to colored, and line of code;
//if you have poor paint syntax, then modify syntax pattern!
namespace core\logSystem;

class colorSyntax
{
    protected $lang;
    protected $context;
    private $syntaxPatern = [
    'sql' => 'SELECT|WHERE|FROM|UPDATE|DELETE|INSERT INTO|SET|VALUES|AND|OR|ORDER BY|GROUP BY|ALTER TABLE|IN|CREATE TABLE|DISTINCT|ASC|DESC|LIKE|HAVING|DROP TABLE|USE|BETWEEN|EXEC|WITH',
  ];

    public function __construct($lang, $context)
    {
        $this->lang = $lang;
        $this->context = $context;
    }

    public function clrSyntax()
    {
        return  stripslashes(preg_replace("/\s*(".$this->syntaxPatern[$this->lang].")[\s.]{1}/i", "<span class = '{$this->lang}'> $1 </span>", $this->context));
    }
}
