<?php

declare(strict_types=1);

namespace App\Dictionary;

use Sunrise\Http\Router\LanguageInterface;

// TODO: A number of languages like this is rarely used in a real project;
//       they are all listed here to demonstrate out-of-the-box transliteration.
//       We recommend keeping only those that you will actually use.
enum LanguageCode: string implements LanguageInterface
{
    case Afrikaans = 'af';
    case Albanian = 'sq';
    case Arabic = 'ar';
    case Armenian = 'hy';
    case Bengali = 'bn';
    case Bosnian = 'bs';
    case Burmese = 'my';
    case Catalan = 'ca';
    case Chinese = 'zh';
    case Croatian = 'hr';
    case Czech = 'cs';
    case Danish = 'da';
    case Dutch = 'nl';
    case English = 'en';
    case Esperanto = 'eo';
    case Estonian = 'et';
    case Filipino = 'tl';
    case Finnish = 'fi';
    case French = 'fr';
    case German = 'de';
    case Greek = 'el';
    case Gujarati = 'gu';
    case Hindi = 'hi';
    case Hungarian = 'hu';
    case Icelandic = 'is';
    case Italian = 'it';
    case Japanese = 'ja';
    case Javanese = 'jw';
    case Kannada = 'kn';
    case Khmer = 'km';
    case Korean = 'ko';
    case Latin = 'la';
    case Latvian = 'lv';
    case Macedonian = 'mk';
    case Malayalam = 'ml';
    case Marathi = 'mr';
    case Nepali = 'ne';
    case Norwegian = 'no';
    case Polish = 'pl';
    case Portuguese = 'pt';
    case Punjabi = 'pa';
    case Romanian = 'ro';
    case Russian = 'ru';
    case Serbian = 'sr';
    case Sinhala = 'si';
    case Slovak = 'sk';
    case Slovenian = 'sl';
    case Spanish = 'es';
    case Sundanese = 'su';
    case Swahili = 'sw';
    case Tamil = 'ta';
    case Telugu = 'te';
    case Thai = 'th';
    case Turkish = 'tr';
    case Ukrainian = 'uk';
    case Urdu = 'ur';
    case Vietnamese = 'vi';
    case Welsh = 'cy';
    case Zulu = 'zu';

    public function getCode(): string
    {
        return $this->value;
    }
}
