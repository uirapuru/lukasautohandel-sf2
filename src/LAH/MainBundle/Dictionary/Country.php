<?php
namespace LAH\MainBundle\Dictionary;

class Country
{
    const POLAND  = 'poland';
    const GERMANY = 'germany';

    public static $choicesArray = [
        self::POLAND    => 'country.poland',
        self::GERMANY   => 'country.germany',
    ];
}
