<?php
namespace Dende\FrontBundle\Dictionary;

class Fuel
{
    const DIESEL = "diesel";
    const LPG    = "lpg";
    const BIODIESEL = "biodiesel";
    const PETROL = "petrol";

    public static $choicesArray = [
        self::DIESEL    => "fuel.diesel",
        self::LPG       => "fuel.lpg",
        self::BIODIESEL => "fuel.biodiesel",
        self::PETROL    => "fuel.petrol",
    ];
}
