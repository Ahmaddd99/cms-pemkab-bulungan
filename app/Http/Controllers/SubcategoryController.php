<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
                    $image_path = public_path('subcategory/' . $request->current_image_subcategory);
                    if(File::exists($image_path)){
                        File::delete($image_path);
                    }

                    $foto = $request->file('image');
                    $namafile = 'subcategory-' . Str::slug($request->name)  .  '.'  .  $foto->extension();
                    $tujuan_upload = 'subcategory';
                    $foto->move($tujuan_upload, $namafile);
                    $subcategoryData['image'] = $namafile;
                }

                if ($request->hasFile('image_placeholder')) {
                    $placeholder = $request->file('image_placeholder');
                    $path = time() . '_' . Str::slug($request->name)  .  '.'  .  $placeholder->extension();
                    $placeholder->move('placeholder', $path);
                    $subcategoryData['image_placeholder'] = $path;
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
            return '<img src="../../subcategory/'.$row->image.'" alt="" style="width:6.5em">';
        })
        ->editColumn('published', function($row){
            if($row->published == 1){
                return '<span class="badge badge-pill badge-primary">Publish</span>';
            }else{
                return '<span class="badge badge-pill badge-secondary">Tidak Dipublish</span>';
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
