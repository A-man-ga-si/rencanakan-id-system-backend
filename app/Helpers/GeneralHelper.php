<?php

use App\Models\CustomAhp;
use App\Models\CustomAhs;
use App\Models\CustomAhsItem;
use App\Models\CustomItemPrice;
use Riskihajar\Terbilang\Facades\Terbilang;
use App\Models\RabItem;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

if (!function_exists('determineCustomAhsItemName')) {
    function determineCustomAhsItemName(CustomAhsItem $customAhsItem)
    {
        if (!$customAhsItem) return 'Item AHS Dihapus';
        switch ($customAhsItem->custom_ahs_itemable_type) {
            case CustomAhp::class :
            case CustomAhs::class :
                return $customAhsItem->name;
            break;
            case CustomItemPrice::class :
                return $customAhsItem->customAhsItemable ? $customAhsItem->customAhsItemable->name : 'Item AHS Dihapus';
            break;
        }
    }
}

if (!function_exists('terbilang')) {
    function terbilang($number)
    {
        return Terbilang::make($number);
    }
}

if (!function_exists('numToRoman')) {
    function numToRoman($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return strtoupper($returnValue);
    }
}

if (!function_exists('numToAlphabet')){
    function numToAlphabet($num)
    {
        if ($num > 25) $num %= 26;
        $lettersRange = range('A', 'Z');

        return $lettersRange[$num];
    }
}

if (!function_exists('generateRandomOrderId')) {
    function generateRandomOrderId()
    {
        return strtoupper(Str::random(16));
    }
}

if (!function_exists('getUnitNameByHashedId')) {
    function getUnitNameByHashedId($hashedUnitId)
    {
        $unit = Unit::find(Hashids::decode($hashedUnitId)[0]);
        return $unit ? $unit->name : 'Tidak ada unit';
    }
}
