<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBibNumberRequest;
use App\Http\Resources\BibNumberResource;
use App\Models\BibNumber as BibNumberModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BibNumber extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BibNumberResource::collection(BibNumberModel::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBibNumberRequest $request)
    {
        $code = 200;

        $path = $request->file("thumbnail")->store("public/bib_number_thumbnails");
        $thumbnail_path = "/".str_replace("public", "storage", $path);

        $data = array_merge(
            $request->only(["event_id", "file_name"]),
            ["thumbnail" => $thumbnail_path]
        );

        $bib_numbers = json_decode(str_replace("'", "\"", $request->get("bib_number")));

        try {
            foreach($bib_numbers as $bib_number){

                $data["bib_number"] = $bib_number;

                BibNumberModel::create($data);
            }

            $result = [];//BibNumberModel::all();

        } catch (\Exception $e){

            $code = 500;

            $result = [
                "message" => "Error when creating new data.",
                "errors" => [
                    "title"=> [
                        "Error when creating new data."
                    ]
                ]
            ];
        }

        return JsonResponse::fromJsonString(json_encode($result), $code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
