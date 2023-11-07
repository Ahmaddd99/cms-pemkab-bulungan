<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;

class FeatureController extends Controller
{
    public function index(){
        return view('features.index');
    }

    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
            'order' => 'required',
            'published' => 'required',
        ];
        $messages = [
            'title.required' => 'title harus terisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Tipe file harus .jpg/.jpeg/.png',
            'order.required' => 'order harus terisi',
            'published.required' => 'published harus terisi'
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
                $featureData = [
                    'title' => $request->title,
                    'order' => $request->order,
                    'published' => $request->published
                ];

                if($request->hasFile('image')){
                    $imagepath = public_path('feature/' . $request->current_image_feature);
                    if(File::exists($imagepath)) {
                        FIle::delete($imagepath);
                    }
                    $imgFeature = $request->file('image');
                    $namaFile = 'feature-' . time() . '_' . Str::slug($request->title)  .  ' . '  .  $imgFeature->extension();
                    $tujuanUpload = 'feature';
                    $imgFeature->move($tujuanUpload, $namaFile);
                    $featureData['image'] = $namaFile;
                }

                Feature::updateOrCreate(['id' => $request->id], $featureData);
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'success commit data'
                ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal server error ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function datatables(){
        $data = Feature::select("*")->orderBy('id', 'desc')->get();
        return DataTables::of($data)
        ->editColumn('image', function($row){
            return '<img src="'.asset("/feature/" . $row->image).'" alt="" style="width:6.5em">';
        })
        ->editColumn('published', function($row){
            if($row->published == 1){
                return '<div class="btn-sm" style="background-color:#435EBE;color:white">Publish</div>';
            }else{
                return '<div class="btn-secondary btn-sm">Tidak Dipublish</div>';
            }
        })
        ->addColumn('actions', function($row){
            return '
            <button type="button" class="btn btn-info btn-edit-feature btn-sm" data-id="' . $row->id . '" data-target="#ModalFeature" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-feature btn-sm" data-id="' . $row->id . '">Hapus</button>
            ';
        })
        ->rawColumns(['id', 'title', 'image', 'order', 'published', 'actions'])
        ->make(true);
    }

    public function show($id){
        $data = Feature::find($id);
        return response()->json([
            'feature' => $data
        ]);
    }

    public function destroy($id){
        $data = Feature::where('id', $id)->first();
        if($data->image){
            $path = public_path('feature') . '/' . $data->image;
            if(fileExists($path)){
                unlink($path);
            }
        }
        $data->delete();
        return response()->json([
            'message' => 'Feature was deleted'
        ]);
    }
}
