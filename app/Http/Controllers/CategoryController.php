<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
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

    public function store(Request $request, Category $categories){
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
                    //jika sudah ada file nya
                    $gambar = $categories->image;
                    if(File::exists($gambar)){
                        File::delete($gambar);
                    }

                    $foto = $request->file('image');
                    $namafile = 'category-' . time() . '_' . $foto->getClientOriginalName();
                    $tujuan_upload = 'category';
                    $foto->move($tujuan_upload, $namafile);
                    $categoryData['image'] = $namafile;
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
            return '<img src="'.$row->image.'" alt="" style="width:15em">';
        })
        ->editColumn('published', function($row){
            if($row->published == 1){
                return '<div class="btn-primary btn-sm">Publish</div>';
            }else{
                return '<div class="btn-secondary btn-sm">Tidak Dipublish</div>';
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
