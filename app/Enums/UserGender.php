<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserGender extends Enum
{
    const Male = 0;
    const FeMale = 1;
    const Other = 2;
}
