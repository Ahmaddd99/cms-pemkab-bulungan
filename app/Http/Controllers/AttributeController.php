<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class AttributeController extends Controller
{
    public function index(){
        return view('attributes.index');
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'slug' => 'unique:attributs,slug' . $request->id
        ];
        $messages = [
            'name.required' => 'nama atribut harus terisi',
            // 'slug.required' => 'slug harus terisi',
            'slug.unique' => 'slug harus unik',
        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        if($validation->fails()){
            return response()->json([
                'status' => 422,
                'message' => $validation->getMessageBag()->toArray()
            ], 422);
        }else{
            DB::beginTransaction();
            try{
                $name = $request->name;
                Attribut::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'name' => $name,
                        'slug' => Str::slug($name . '-' . time()),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Success post data'
                ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal server error ' . $e
                ], 500);
            }
        }
    }

    public function datatables(){
        $data = Attribut::select("*")->orderBy('id', 'desc')->get();
        return DataTables::of($data)
        ->addColumn('actions', function($row){
            return '
            <button type="button" class="btn btn-info btn-edit-attribute btn-sm" data-id="'.$row->id.'" data-target="#ModalAttribute" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-attribute btn-sm" data-id="'.$row->id.'">Hapus</button>
            ';
        })
        ->rawColumns(['name', 'actions'])
        ->make(true);
    }

    public function getAttribute($id){
        $data = Attribut::where('id', $id)->first();
        return response()->json([
            'attribute' => $data
        ]);
    }

    public function destroy($id){
        $data = Attribut::where('id', $id)->first();
        if($data){
            $data->delete();
            return response()->json([
                'message' => 'Berhasil menghapus data'
            ]);
        }
    }
}
