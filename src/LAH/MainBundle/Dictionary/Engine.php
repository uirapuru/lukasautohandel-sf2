<?php
namespace LAH\MainBundle\Dictionary;

class Engine
{
    const DIESEL = 'diesel';
    const PETROL = 'petrol';

    public static $choicesArray = [
        self::DIESEL    => 'engine.diesel',
        self::PETROL    => 'engine.petrol',
    ];
}
