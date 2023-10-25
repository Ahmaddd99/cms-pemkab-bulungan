<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(){
        return view('agendas.index');
    }

    public function store(Request $request){
        //
    }

    public function show($id){
        //
    }

    public function destroy($id){
        //
    }
}
