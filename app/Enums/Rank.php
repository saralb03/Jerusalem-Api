<?php

namespace App\Enums;

enum Rank: string
{
    case ALM = 'אל"מ';
    case ALUF = 'אלוף';
    case AATZ = 'אע"צ';
    case TUR = 'טור';
    case SAL = 'סא"ל';
    case SAGM = 'סג"מ';
    case SAGAN = 'סגן';
    case SAMAL = 'סמל';
    case SMR = 'סמ"ר';
    case SEREN = 'סרן';
    case KAAV = 'קא"ב';
    case KAMAM = 'קא"מ';
    case KAMA = 'קמ"א';
    case KAPAR = 'קר"פ';
    case RAL = 'רא"ל';
    case RAVAT = 'רב"ט';
    case RANAG = 'רנ"ג';
    case RESAV = 'רס"ב';
    case RASAL = 'רס"ל';
    case RASAM = 'רס"מ';
    case RESEN = 'רס"נ';
    case RESER = 'רס"ר';
    case TAAL = 'תא"ל';

    public static function fromHebrew($hebrew): ?string
    {
        return match ($hebrew) {
            'אל"מ', 'אלם', 'אל"ם', 'אלמ' => self::ALM->value,
            'אלף', 'אלוף' => self::ALUF->value,
            'אע"ץ', 'אע"צ', 'אעצ', 'אעב', 'אע"ב' => self::AATZ->value,
            'טוראי', 'טור' => self::TUR->value,
            'סא"ל', 'סאל' => self::SAL->value,
            'סג"מ', 'סגם' => self::SAGM->value,
            'סגן' => self::SAGAN->value,
            'סמל' => self::SAMAL->value,
            'סמ"ר', 'סמר' => self::SMR->value,
            'סרן' => self::SEREN->value,
            'קא"ב', 'קאב' => self::KAAV->value,
            'קא"מ' => self::KAMAM->value,
            'קמ"א', 'קמא' => self::KAMA->value,
            'קר"פ', 'קרף' => self::KAPAR->value,
            'רא"ל', 'ראל' => self::RAL->value,
            'רב"ט', 'רבט' => self::RAVAT->value,
            'רנ"ג', 'רנג' => self::RANAG->value,
            'רס"ב', 'רסב' => self::RESAV->value,
            'רס"ל', 'רסל' => self::RASAL->value,
            'רס"מ', 'רסם' => self::RASAM->value,
            'רס"נ', 'רסן' => self::RESEN->value,
            'רס"ר', 'רסר' => self::RESER->value,
            'תא"ל', 'תאל' => self::TAAL->value,
            default => null,
        };
    }
}
