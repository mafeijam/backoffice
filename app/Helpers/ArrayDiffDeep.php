<?php

namespace App\Helpers;

class ArrayDiffDeep
{
    public function diff($arr1, $arr2)
    {
        if ($arr1 === $arr2) return [];

        if ($this->notArray($arr1, $arr2)) return $arr2; // updated

        $arrayKeys1 = $this->toArrayKeys($arr1);

        $deleted = array_reduce($arrayKeys1, function ($acc, $key) use ($arr2) {
            return array_key_exists($key, $arr2) ? $acc : $acc += [$key => null]; // deleted
        }, []);

        $arrayKeys2 = $this->toArrayKeys($arr2);

        return array_reduce($arrayKeys2, function ($acc, $key) use ($arr1, $arr2) {
            if (array_key_exists($key, $arr1)) {
                $diff = $this->diff($arr1[$key], $arr2[$key]);
                return $this->noDiff($diff) ? $acc : $acc += [$key => $diff]; // updated
            }

            return $acc += [$key => $arr2[$key]]; // added
        }, $deleted);
    }

    protected function notArray($arr1, $arr2)
    {
        return !is_array($arr1) || !is_array($arr2);
    }

    protected function toArrayKeys($arr)
    {
        return is_array($arr) ? array_keys($arr) : [$arr];
    }

    protected function noDiff($diff)
    {
        return is_array($diff) && count($diff) === 0;
    }
}
