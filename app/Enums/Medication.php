<?php

declare(strict_types=1);

namespace App\Enums;

enum Medication: string
{
    // قلب و فشار خون
    case Amlodipine = 'amlodipine';
    case Losartan = 'losartan';
    case Valsartan = 'valsartan';
    case Candesartan = 'candesartan';
    case Irbesartan = 'irbesartan';
    case Telmisartan = 'telmisartan';
    case Enalapril = 'enalapril';
    case Lisinopril = 'lisinopril';
    case Captopril = 'captopril';
    case Ramipril = 'ramipril';
    case Metoprolol = 'metoprolol';
    case Atenolol = 'atenolol';
    case Bisoprolol = 'bisoprolol';
    case Carvedilol = 'carvedilol';
    case Propranolol = 'propranolol';
    case Furosemide = 'furosemide';
    case Hydrochlorothiazide = 'hydrochlorothiazide';
    case Spironolactone = 'spironolactone';
    case Nitroglycerin = 'nitroglycerin';
    case IsosorbideMononitrate = 'isosorbide_mononitrate';
    case Digoxin = 'digoxin';

    // دیابت
    case Metformin = 'metformin';
    case Glimepiride = 'glimepiride';
    case Gliclazide = 'gliclazide';
    case Glibenclamide = 'glibenclamide';
    case Sitagliptin = 'sitagliptin';
    case Linagliptin = 'linagliptin';
    case Saxagliptin = 'saxagliptin';
    case Empagliflozin = 'empagliflozin';
    case Dapagliflozin = 'dapagliflozin';
    case Canagliflozin = 'canagliflozin';
    case Pioglitazone = 'pioglitazone';
    case Acarbose = 'acarbose';
    case Insulin = 'insulin';

    // چربی خون
    case Atorvastatin = 'atorvastatin';
    case Rosuvastatin = 'rosuvastatin';
    case Simvastatin = 'simvastatin';
    case Pravastatin = 'pravastatin';
    case Ezetimibe = 'ezetimibe';
    case Fenofibrate = 'fenofibrate';

    // داروهای ضدانعقاد و ضدپلاکت
    case Aspirin = 'aspirin';
    case Clopidogrel = 'clopidogrel';
    case Warfarin = 'warfarin';
    case Rivaroxaban = 'rivaroxaban';
    case Apixaban = 'apixaban';
    case Dabigatran = 'dabigatran';

    // تیروئید
    case Levothyroxine = 'levothyroxine';
    case Methimazole = 'methimazole';
    case Propylthiouracil = 'propylthiouracil';

    // آسم و بیماری‌های تنفسی
    case Salbutamol = 'salbutamol';
    case Budesonide = 'budesonide';
    case Formoterol = 'formoterol';
    case Salmeterol = 'salmeterol';
    case Fluticasone = 'fluticasone';
    case Beclometasone = 'beclometasone';
    case Montelukast = 'montelukast';
    case Ipratropium = 'ipratropium';
    case Tiotropium = 'tiotropium';
    case Theophylline = 'theophylline';

    // بیماری‌های گوارشی
    case Omeprazole = 'omeprazole';
    case Pantoprazole = 'pantoprazole';
    case Esomeprazole = 'esomeprazole';
    case Lansoprazole = 'lansoprazole';
    case Famotidine = 'famotidine';
    case Domperidone = 'domperidone';
    case Metoclopramide = 'metoclopramide';

    // داروهای ضدالتهاب و درد
    case Acetaminophen = 'acetaminophen';
    case Ibuprofen = 'ibuprofen';
    case Naproxen = 'naproxen';
    case Diclofenac = 'diclofenac';

    // داروهای مرتبط با کلیه و الکترولیت
    case CalciumCarbonate = 'calcium_carbonate';
    case CalciumAcetate = 'calcium_acetate';
    case SodiumBicarbonate = 'sodium_bicarbonate';

    // داروهای روان‌پزشکی رایج
    case Sertraline = 'sertraline';
    case Fluoxetine = 'fluoxetine';
    case Escitalopram = 'escitalopram';
    case Citalopram = 'citalopram';
    case Venlafaxine = 'venlafaxine';
    case Duloxetine = 'duloxetine';

    public function label(): string
    {
        return match ($this) {
            self::Amlodipine => 'آملودیپین',
            self::Losartan => 'لوزارتان',
            self::Valsartan => 'والسارتان',
            self::Candesartan => 'کاندسارتان',
            self::Irbesartan => 'ایربسارتان',
            self::Telmisartan => 'تلمیسارتان',
            self::Enalapril => 'انالاپریل',
            self::Lisinopril => 'لیزینوپریل',
            self::Captopril => 'کاپتوپریل',
            self::Ramipril => 'رامیپریل',
            self::Metoprolol => 'متوپرولول',
            self::Atenolol => 'آتنولول',
            self::Bisoprolol => 'بیزوپرولول',
            self::Carvedilol => 'کارودیلول',
            self::Propranolol => 'پروپرانولول',
            self::Furosemide => 'فوروزماید',
            self::Hydrochlorothiazide => 'هیدروکلروتیازید',
            self::Spironolactone => 'اسپیرونولاکتون',
            self::Nitroglycerin => 'نیتروگلیسیرین',
            self::IsosorbideMononitrate => 'ایزوسورباید مونونیترات',
            self::Digoxin => 'دیگوکسین',

            self::Metformin => 'متفورمین',
            self::Glimepiride => 'گلیمپیرید',
            self::Gliclazide => 'گلیکلازید',
            self::Glibenclamide => 'گلیبنکلامید',
            self::Sitagliptin => 'سیتاگلیپتین',
            self::Linagliptin => 'لیناگلیپتین',
            self::Saxagliptin => 'ساکساگلیپتین',
            self::Empagliflozin => 'امپاگلیفلوزین',
            self::Dapagliflozin => 'داپاگلیفلوزین',
            self::Canagliflozin => 'کاناگلیفلوزین',
            self::Pioglitazone => 'پی‌اوگلیتازون',
            self::Acarbose => 'آکاربوز',
            self::Insulin => 'انسولین',

            self::Atorvastatin => 'آتورواستاتین',
            self::Rosuvastatin => 'روزوواستاتین',
            self::Simvastatin => 'سیمواستاتین',
            self::Pravastatin => 'پراواستاتین',
            self::Ezetimibe => 'ازتیمایب',
            self::Fenofibrate => 'فنوفیبرات',

            self::Aspirin => 'آسپرین',
            self::Clopidogrel => 'کلوپیدوگرل',
            self::Warfarin => 'وارفارین',
            self::Rivaroxaban => 'ریواروکسابان',
            self::Apixaban => 'آپیکسابان',
            self::Dabigatran => 'دابیگاتران',

            self::Levothyroxine => 'لووتیروکسین',
            self::Methimazole => 'متی‌مازول',
            self::Propylthiouracil => 'پروپیل‌تیواوراسیل',

            self::Salbutamol => 'سالبوتامول',
            self::Budesonide => 'بودزوناید',
            self::Formoterol => 'فورموترول',
            self::Salmeterol => 'سالمترول',
            self::Fluticasone => 'فلوتیکازون',
            self::Beclometasone => 'بکلومتازون',
            self::Montelukast => 'مونته‌لوکاست',
            self::Ipratropium => 'ایپراتروپیوم',
            self::Tiotropium => 'تیوتروپیوم',
            self::Theophylline => 'تئوفیلین',

            self::Omeprazole => 'امپرازول',
            self::Pantoprazole => 'پنتوپرازول',
            self::Esomeprazole => 'ازومپرازول',
            self::Lansoprazole => 'لانسوپرازول',
            self::Famotidine => 'فاموتیدین',
            self::Domperidone => 'دومپریدون',
            self::Metoclopramide => 'متوکلوپرامید',

            self::Acetaminophen => 'استامینوفن',
            self::Ibuprofen => 'ایبوپروفن',
            self::Naproxen => 'ناپروکسن',
            self::Diclofenac => 'دیکلوفناک',

            self::CalciumCarbonate => 'کربنات کلسیم',
            self::CalciumAcetate => 'استات کلسیم',
            self::SodiumBicarbonate => 'بی‌کربنات سدیم',

            self::Sertraline => 'سرترالین',
            self::Fluoxetine => 'فلوکستین',
            self::Escitalopram => 'اس‌سیتالوپرام',
            self::Citalopram => 'سیتالوپرام',
            self::Venlafaxine => 'ونلافاکسین',
            self::Duloxetine => 'دولوکستین',
        };
    }
}
