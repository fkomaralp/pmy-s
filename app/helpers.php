<?php

function slug($string){
    return \Illuminate\Support\Str::slug($string, "-");
}
