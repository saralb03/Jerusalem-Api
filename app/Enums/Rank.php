<?php

namespace App\Enums;

enum Rank: string
{
    case ALM = 'אל"מ';
    case ALUF = 'אלוף';
    case ALEF = 'אלף';
    case AATZ = 'אע"צ';
    case TAURAI = 'טור';
    case SAL = 'סא"ל';
    case SEGAM = 'סג"מ';
    case SAGAN = 'סגן';
    case SAMAL = 'סמל';
    case SAMAR = 'סמ"ר';
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


    // public static function fromHebrew(string $hebrew): ?self
    // {
    //     return match ($hebrew) {
    //         'אלמ','אלם' => self::ALM->value,
    //         'אעב' => self::AYB,
    //         'אעץ' => self::AYC,
    //         'טוראי' => self::TORAI,
    //         'סאל' => self::SAL,
    //         'סגמ', 'סגם' => self::SAGM,
    //         'סגן' => self::SAGAN,
    //         'סמל' => self::SAMAL,
    //         'סמר' => self::SMR,
    //         'סדר' => self::SADER,
    //         'קאב' => self::KAAV,
    //         'קמא' => self::KMA,
    //         'רביט' => self::RBIT,
    //         'רג' => self::RG,
    //         'רסב' => self::RASB,
    //         'רסל' => self::RASIL,
    //         'רסם' => self::RASM,
    //         'רסן' => self::RSN,
    //         'רסר' => self::RSR,
    //         'תאל' => self::TAAL,
    //         'אלף' => self::ALEF,
    //         'אב' => self::AV,
    //         'עדי' => self::ADI,
    //         'תור' => self::TAUR,
    //         default => null,
    //     };
    // }
}
