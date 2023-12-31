<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;

class CategoryController extends Controller
{
    public function index(){
        return view('categories.index');
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png',
            'published' => 'required'
        ];
        $messages = [
            'name.required' => 'Nama harus terisi',
            'image.image' => 'file harus berupa gambar',
            'image.mimes' => 'tipe file harus .jpeg/.jpg/.png',
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
                $categoryData = [
                    'name' => $request->name,
                    'published' => $request->published
                ];
                if ($request->hasFile('image')) {
                    $currentImagePath = public_path('category/' . $request->current_image_category);
                    if(File::exists($currentImagePath)) {
                        File::delete($currentImagePath);
                    }

                    $foto = $request->file('image');
                    $namafile = 'category-' . Str::slug($request->name)  .  '.'  .  $foto->extension();
                    $tujuan_upload = 'category';
                    $foto->move($tujuan_upload, $namafile);
                    $categoryData['image'] = $namafile;
                }

                if ($request->hasFile('image_placeholder')) {
                    $placeholder = $request->file('image_placeholder');
                    $path = time() . '_' . Str::slug($request->name)  .  '.'  .  $placeholder->extension();
                    $placeholder->move('placeholder', $path);
                    $categoryData['image_placeholder'] = $path;
                }

                Category::updateOrCreate(['id' => $request->id], $categoryData);
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Success commited"
                ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => "Internal server error " . $e->getMessage()
                ], 500);
            }
        }
    }

    public function datatables(){
        $data = Category::select("*")->orderBy('id', 'desc')->get();
        // return $data;
        return DataTables::of($data)
        ->editColumn('image', function($row){
            return '<img src="../../category/'.$row->image.'" alt="" style="width:6.5em">';
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
            <button type="button" class="btn btn-info btn-edit-category btn-sm" data-id="'.$row->id.'" data-target="#ModalCategory" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-category btn-sm" data-id="'.$row->id.'">Hapus</button>
            ';
        })
        ->rawColumns(['name', 'image', 'published', 'actions'])
        ->make(true);
    }

    public function show($id){
        $data = Category::find($id);
        return response()->json([
            'category' => $data
        ]);
    }

    public function destroy($id){
        $category = Category::where('id', $id)->first();
        if($category->image){
            $path = public_path('category') . '/' . $category->image;
            if(fileExists($path)){
                unlink($path);
            }
        }

        $category->delete();
        return response()->json([
            'message' => 'Category was deleted'
        ]);
    }
}
