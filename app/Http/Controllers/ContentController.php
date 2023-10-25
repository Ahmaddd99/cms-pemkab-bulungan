<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Models\Feature;
use App\Models\FeatureValue;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;

class ContentController extends Controller
{
    public function index()
    {
        return view('contents.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'subcategory_id' => 'nullable',
            'image' => 'image|mimes:jpg,jpeg,png',
            'title' => 'required',
            'body' => 'required',
            'meta' => 'required'
        ];
        $messages = [
            'category_id.required' => 'kategori id harus terisi',
            'image.image' => 'file harus berupa gambar',
            'image.mimes' => 'file harus berupa jpeg, jpg, atau png',
            'title.required' => 'title harus terisi',
            'body.required' => 'body harus terisi',
            'meta.required' =>  'meta harus terisi'
        ];
        $validation = Validator::make($request->all(), $messages, $rules);
        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->getMessageBag()->toArray()
            ], 422);
        } else {
            DB::beginTransaction();
            try {
                $contentData = [
                    'category_id' => $request->category_id,
                    'title' => $request->title,
                    'body' => $request->body,
                    'meta' => $request->meta
                ];

                if ($request->subcategory_id) {
                    $contentData['subcategory_id'] = $request->subcategory_id;
                }else{
                    $contentData['subcategory_id'] = null;
                }

                // cek gambar berdasarkan id
                $id = $request->id;
                $content = Content::find($id);
                if($content->image){
                    $gambar = storage_path('content/' . $content->image);
                    if (File::exists($gambar)) {
                        File::delete($gambar);
                    }
                }

                if ($request->hasFile('image')) {
                    $imgContent = $request->file('image');
                    $namaFile = 'content-' . time() . '_' . $imgContent->getClientOriginalName();
                    $tujuanUpload = 'content';
                    $imgContent->move($tujuanUpload, $namaFile);
                    $contentData['image'] = $namaFile;
                }

                $content = Content::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    $contentData
                );

                if($request->feature_id){
                    FeatureValue::updateOrCreate([
                        'id' => $request->id_featureValue
                    ],
                    [
                        'feature_id' => $request->feature_id,
                        'content_id' => $content->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
                DB::commit();
                return response()->json([
                    'message' => "Commit was success"
                ], 200);
            } catch (Throwable $e) {
                DB::rollBack();
                return response()->json([
                    'message' => "error in server " . $e->getMessage()
                ], 500);
            }
        }
    }

    public function datatables()
    {
        $data = Content::select("*")->orderBy('id', 'desc')->with('category', 'subcategory')->get();
        return DataTables::of($data)
            ->editColumn('image', function ($row) {
                return '<img src="' . $row->image . '" alt="" style="width:15em">';
            })
            ->addColumn('actions', function ($row) {
                return '
            <button type="button" class="btn btn-info btn-edit-content btn-sm" data-id="' . $row->id . '" data-target="#ModalContent" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-content btn-sm" data-id="' . $row->id . '">Hapus</button>
            ';
            })
            ->rawColumns(['category_id', 'subcategory_id', 'image', 'title', 'body', 'meta', 'actions'])
            ->make(true);
    }

    public function show($id)
    {
        $data = Content::where('id', $id)->with('category', 'subcategory', 'featureValue')->first();
        return response()->json([
            'content' => $data
        ]);
    }

    public function getCategoryId()
    {
        $data = Category::select("*")->limit(100)->orderBy('id', 'desc')->get();
        return response()->json([
            'category' => $data
        ]);
    }

    public function getSubcategoryId()
    {
        $data = Subcategory::select("*")->limit(100)->orderBy('id', 'desc')->get();
        return response()->json([
            'subcategory' => $data
        ]);
    }

    public function getFeatureId()
    {
        $data = Feature::select("*")->limit(100)->orderBy('id', 'desc')->get();
        return response()->json([
            'feature' => $data
        ]);
    }

    public function destroy($id){
        $data = Content::where('id', $id)->with('featureValue')->first();
        if($data->image){
            $path = public_path('content') . '/' . $data->image;
            if(fileExists($path)){
                unlink($path);
            }
        }

        $data->delete();
        return response()->json([
            'message' => 'Content was deleted'
        ]);
    }
}
