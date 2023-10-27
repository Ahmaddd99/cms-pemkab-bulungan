<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(){
        return view('agendas.index');
    }

    public function store(Request $request)
    {
    	$rules 	= [
    		'tanggal' => 'required',
    		'waktu'	=> 'required',
    		'judul'	=> 'required',
    		'keterangan' => 'required'
    	];
    	$messages = [
    		'tanggal.required' => 'Tanggal tidak boleh kosong',
    		'waktu.required' => 'Waktu tidak boleh kosong',
    		'judul.required' => 'Judul tidak boleh kosong',
    		'keterangan.required' => 'Keterangan tidak boleh kosong'
    	];

    	$validateData = $request->validate($rules, $messages);

    	if($validateData) {
            // $validateData['waktu'] = Carbon::parse($request->waktu)->format("H:i:s");
            $waktu = $request->waktu;
            $waktukedatabase = $waktu . ':00';
            $validateData['waktu'] = $waktukedatabase;
    		$validateData['lokasi'] = $request->lokasi;
    		Agenda::create($validateData);
    	}

    	return redirect()->route('agenda.index')->withSuccess('Berhasil tambah agenda!');

    }


    public function edit(Agenda $agenda)
    {
    	$data = [
    		'agenda' => $agenda
    	];
    	return view('agendas.edit', $data);
    }


    public function update(Request $request, Agenda $agenda)
    {
    	$rules 	= [
    		'tanggal' => 'required',
    		'waktu'	=> 'required',
    		'judul'	=> 'required',
    		'keterangan' => 'required'
    	];
    	$messages = [
    		'tanggal.required' => 'Tanggal tidak boleh kosong',
    		'waktu.required' => 'Waktu tidak boleh kosong',
    		'judul.required' => 'Judul tidak boleh kosong',
    		'keterangan.required' => 'Keterangan tidak boleh kosong'
    	];

    	$validateData = $request->validate($rules, $messages);

    	if($validateData) {
            $waktu = $request->waktu;
            $validateData['waktu'] = date_format($waktu, "H:i:s");
    		$validateData['lokasi'] = $request->lokasi;
    		Agenda::where('id', $agenda->id)->update($validateData);
    	}

    	return redirect()->route('agenda.index')->withSuccess('Berhasil edit agenda!');
    }

    public function destroy(Agenda $agenda)
    {
    	Agenda::destroy($agenda->id);

    	return redirect()->route('agenda.index')->withSuccess('Berhasil hapus data agenda.');
    }

    public function getAgenda(){
        return Agenda::all();
    }
}
