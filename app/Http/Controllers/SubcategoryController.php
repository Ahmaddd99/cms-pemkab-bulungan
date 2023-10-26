<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;

class SubcategoryController extends Controller
{
    public function index(){
        return view('subcategories.index');
    }

    public function store(Request $request, Subcategory $subcategories)
    {
        $rules = [
            'category_id' => 'required',
            'name' => 'required',
            // 'image' => 'image|mimes:jpg,jpeg,png',
            'published' => 'required',
        ];
        $message = [
            'category_id.required' => 'kategori id harus terisi',
            'name.required' => 'nama subkategori harus terisi',
            // 'image.image' => 'file harus berupa gambar',
            // 'image.mimes' => 'file harus berupa jpeg, jpg, atau png',
            'published.required' => 'Published harus terisi'
        ];
        $validation = Validator::make($request->all(), $rules, $message);
        if($validation->fails()){
            return response()->json([
                'message' => $validation->getMessageBag()->toArray()
            ], 422);
        }else{
            DB::beginTransaction();
            try{
                $subcategoryData = [
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                    'published' => $request->published
                ];
                if ($request->hasFile('image')) {
                    //jika sudah ada file nya
                    $gambar = $subcategories->image;
                    if(File::exists($gambar)){
                        File::delete($gambar);
                    }

                    $foto = $request->file('image');
                    $namafile = 'subcategory-' . time() . '_' . $foto->getClientOriginalName();
                    $tujuan_upload = 'subcategory';
                    $foto->move($tujuan_upload, $namafile);
                    $subcategoryData['image'] = $namafile;
                }

                Subcategory::updateOrCreate(['id' => $request->id], $subcategoryData);
                DB::commit();
                return response()->json([
                    'message' => "success commit"
                ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([
                    'message' => 'error in server' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function datatables(){
        $data = Subcategory::select("*")->orderBy('id', 'desc')->with('category')->get();
        // return $data;
        return DataTables::of($data)
        ->addColumn('category', function($row){
            return $row->category->name;
        })
        ->editColumn('image', function($row){
            return '<img src="'.$row->image.'" alt="" style="width:15em">';
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
            <button type="button" class="btn btn-info btn-edit-subcategory btn-sm" data-id="'.$row->id.'" data-target="#ModalSub" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-subcategory btn-sm" data-id="'.$row->id.'">Hapus</button>
            ';
        })
        ->rawColumns(['category', 'name', 'image', 'published', 'actions'])
        ->make(true);
    }

    public function show($id){
        $data = Subcategory::find($id);
        return response()->json([
            'subcategory' => $data
        ]);
    }

    public function getCategoryId(){
        $data = Category::select("*")->limit(100)->orderBy('id', 'desc')->get();
        return response()->json([
            'getCategoryId' => $data
        ]);
    }

    public function destroy($id){
        $subcategory = Subcategory::where('id', $id)->first();
        if($subcategory->image){
            $path = public_path('subcategory') . '/' . $subcategory->image;
            if(fileExists($path)){
                unlink($path);
            }
        }
        $subcategory->delete();
        return response()->json([
            'message' => 'subcategory was deleted'
        ]);
    }
}
