<?php

class O
{
    public function suicide()
    {
        unset($this);
    }
}

$o = new O();

print_r($o);

$o->suicide();

print_r($o);