<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class RatingController extends Controller
{
    public function index(){
        return view('ratings.index');
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'icon' => 'image|mimes:jpg,jpeg,png'
        ];
        $message = [
            'name.required' => 'Nama rating harus terisi',
            'icon.image' => 'Ikon harus berupa gambar',
            'icon.mimes' => 'Ikon harus berbentuk .jpg/.jpeg/.png'
        ];
        $validation = Validator::make($request->all(), $rules, $message);

        if ($validation->fails()) {
            return response()->json([ $validation->getMessageBag()->toArray() ], 422);
        } else {
            DB::beginTransaction();
            try{
                if ($request->hasFile('icon')) {
                    $imagepath = public_path('rating/' . $request->current_icon);
                    if(File::exists($imagepath)) {
                        FIle::delete($imagepath);
                    }

                    $icon = $request->file('icon');
                    $path = 'icon-' . time() . '-' . Str::slug($request->name) . '.' . $icon->extension();
                    $icon->move('rating', $path);
                }

                Rating::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'name' => $request->name,
                        'icon' => $path,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
                DB::commit();
                return response()->json([ 'message' => 'Success post!' ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([ 'message' => 'Internal server error ' . $e->getMessage() ], 500);
            }
        }
    }

    public function datatables(){
        $data = Rating::select("*")->orderBy('id', 'desc')->get();
        return DataTables::of($data)
        ->editColumn('icon', function($row){
            return '<img src="'.asset("/rating/" . $row->icon).'" alt="" style="width:6.5em">';
            // return '<img src="'.asset("/rating/" . $row->icon).'" alt="" style="width:6.5em">';
        })
        ->addColumn('actions', function($row){
            return '
            <button type="button" class="btn btn-info btn-edit-icon btn-sm" data-id="' . $row->id . '" data-target="#ModalRating" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-icon btn-sm" data-id="' . $row->id . '">Hapus</button>
            ';
        })
        ->rawColumns(['name', 'icon', 'actions'])
        ->make(true);
    }

    public function get($id){
        $data = Rating::find($id);
        return response()->json([
            'data' => $data
        ]);
    }

    public function destroy($id){
        $data = Rating::findOrFail($id);
        if($data){
            $path = public_path('rating/') . $data->icon;
            if(File::exists($path)){
                File::delete($path);
            }

            $data->delete();
            return response()->json([
                'message' => 'data was deleted'
            ]);
        }
    }
}
