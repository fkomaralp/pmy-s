<?php

namespace App\Http\Controllers;

use App\Models\BibNumber;
use App\Models\Event;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventPreviewController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($event_id, $bib_number)
    {
        $event = Event::with(["photos.bib_numbers" => function($query) use($bib_number){
            $query->where("bib_number", $bib_number);
        }])->find($event_id);
//            $query->where("bib_number", $bib_number);
//        }])->where("id", $event_id)->first();
//        $bib_number_obj = DB::table("bib_numbers")->where("bib_number", $bib_number)->get();
        $bib_number_obj = $event->photos->filter(function ($item) { return $item->bib_numbers->count() > 0;});

        $priceses = Price::all();

        if($bib_number_obj->count() === 0){
            return redirect()->route("frontend.index");
        }

        return view('frontend.event.preview', ["bib_number_obj" => $bib_number_obj, "bib_number" => $bib_number, "priceses" => $priceses, "event" => $event]);
    }
}
