<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\PesanCustomControllerInterfaces;
use Illuminate\Http\Request;

class PesanCustomController extends Controller implements PesanCustomControllerInterfaces
{
    //
    public function index(Request $request)
    {
        // TODO: Implement index() method.
        return view('pesan-custom.index');
    }
}
