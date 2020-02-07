<?php
namespace App\Widgets;

use App\Widgets\Contract\ContractWidget;
use App\Model\Station;
use Illuminate\Http\Request;

class SearchWidget implements ContractWidget
{

    public function execute(){

        $request = new Request();
       

        $data = Station::where(['published' => 1])->get();
        return view('Widgets::search', [
            'stations' => $data
        ]);
    }
}