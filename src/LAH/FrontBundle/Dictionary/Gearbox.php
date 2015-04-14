<?php
namespace LAH\FrontBundle\Dictionary;

class Gearbox
{
    const AUTOMATIC     = 'automatic';
    const MANUAL        = 'manual';
    const SEMIAUTOMATIC = 'semiautomatic';

    public static $choicesArray = [
        self::AUTOMATIC     => 'transmission.automatic',
        self::MANUAL        => 'transmission.manual',
        self::SEMIAUTOMATIC => 'transmission.semiautomatic',
    ];
}
