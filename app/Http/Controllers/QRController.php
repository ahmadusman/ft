<?php

namespace App\Http\Controllers;
use App\Restorant;

use Illuminate\Http\Request;

class QRController extends Controller
{
    public function index(){
        return view('qrsaas.qrgen')->with('data', json_encode(['url'=>env('APP_URL')]));
     }
}
