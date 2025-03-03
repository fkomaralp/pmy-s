<?php

namespace App\Http\Controllers;

use App\Jobs\SendImagesToUserJob;
use App\Models\BibNumber;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cart = Cart::with(["event_price.price", "event"])->where("token", $request->cookie("token"))->get()->groupBy("event_id");
        $order_number = mt_rand(1000000000, 9999999999);

        $bib_number_thumbnails = [];
        foreach($cart as $items){
            foreach($items as $item){
                $bib_numbers = BibNumber::where("bib_number", $item->bib_number)->get();
                foreach($bib_numbers as $bib_number){

                    if($bib_number->photo){
                    if($bib_number->photo->event->id === $item->event_id){
                        $bib_number_thumbnails[] = $bib_number->photo;
                    }
                    }
                }
            }
        }

        $total_price = 0;

        foreach($cart as $item){
            foreach($item as $i) {
                $total_price += (float)$i->event_price->price->price;
            }
        }

        return view("frontend.cart.index", compact("cart", "order_number",  "total_price", "bib_number_thumbnails"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
