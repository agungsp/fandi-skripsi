<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AprioriHelper;
use App\Models\Result;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class TesterController extends Controller
{
    public function index()
    {
        $data = collect([
            ['apel', 'anggur', 'jeruk'],
            ['mangga', 'semangka'],
            ['jeruk', 'pepaya', 'pir', 'mangga'],
            ['alpukat'],
            ['alpukat', 'jeruk']
        ]);

        dd(
            $this->containsArray(['mangga', 'jeruk', 'pir'], $data[1])
        );
    }

    private function containsArray(array $searchArray, array $haystack)
    {
        $finded = 0;
        foreach ($searchArray as $item) {
            if (in_array($item, $haystack)) $finded++;
        }
        return $finded == count($searchArray);
    }

    private function get(Type $var = null)
    {
        # code...
    }

    private function createRulesText($antecedent, $consequent)
    {
        $arr_antecedent = explode(', ', $antecedent);
        $arr_consequent = explode(', ', $consequent);

        $str_antecedent = '';
        for ($i = 0; $i < count($arr_antecedent); $i++) {
            $str_antecedent .= Transaction::where('itemset_code', $arr_antecedent[$i])->first()->item;

            if ($i < (count($arr_antecedent) - 2)) {
                $str_antecedent .= ', ';
            }
            else if ($i == (count($arr_antecedent) - 2)) {
                $str_antecedent .= ' dan ';
            }
        }

        $str_consequent = '';
        for ($i = 0; $i < count($arr_consequent); $i++) {
            $str_consequent .= Transaction::where('itemset_code', $arr_consequent[$i])->first()->item;

            if ($i < (count($arr_consequent) - 2)) {
                $str_consequent .= ', ';
            }
            else if ($i == (count($arr_consequent) - 2)) {
                $str_consequent .= ' dan ';
            }
        }

        return [$str_antecedent, $str_consequent];
    }
}
