<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;

class ContentGalleryController extends Controller
{
    public function index(){
        return view('galleries.index');
    }

    public function store(Request $request){
        $rules = [
            'content_id' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png'
        ];
        $messages = [
            'content_id.required' => 'konten id harus terisi',
            'image.image' => 'file harus berupa gambar',
            'image.mimse' => 'tipe file harus .jpeg/.jpg/.png'
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
                $galleryImages = [];
                if($images = $request->file('image_gallery')){
                    foreach ($images as $image){
                        $namaImage = 'gallery-' . time() . '-' . Str::slug($image)  .  '.'  .  $image->extension();
                        $image->move('gallery', $namaImage);
                        $galleryImages[] = [
                            // 'id' => $request->id,
                            'content_id' => $request->content_id,
                            'image' => $namaImage
                        ];

                    }
                    // return $galleryImages;
                    ContentGallery::upsert($galleryImages, ['id']);
                }
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Success post data'
                ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal server error' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function datatables(){
        // $data = ContentGallery::select("*")->orderBy('id', 'desc')->groupBy('content_id')->with('content')->get();
        $data = Content::with('galleries')->with('category')->with('subcategory')->orderBy('id','desc')->get();
        // return $data;
        return DataTables::of($data)
        ->addColumn('content', function($row){
            if($row->subcategory === null){
                return '<div>
                            <h5 class="mb-3">'.$row->title.'</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Subkategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color:white">
                                <td class="text-center">'.$row->category->name.'</td>
                                <td class="text-center">-</td>
                            </tr>
                        </tbody>
                    </table>

                    </div>';
                } else {
                return '<div>
                            <h5 class="mb-3">'.$row->title.'</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Subkategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color:white">
                                <td class="text-center">'.$row->category->name.'</td>
                                <td class="text-center">'.$row->subcategory->name.'</td>
                            </tr>
                        </tbody>
                    </table>

                    </div>';
            }
        })
        ->editColumn('image', function($row){
            // return '<img src="'.$row->image.'" alt="" style="width:15em">';
            $images = '';
            foreach($row->galleries as $image) {
                $images .= "<img class='float-left rounded mx-1' style='width:6.5em' src='". asset("gallery/" . $image->image) ."' />";
            }
            return $images;
        })
        ->addColumn('actions', function($row){
            return '
            <button type="button" class="btn btn-info btn-sm btn-edit-gallery" data-id="'.$row->id.'" data-toggle="modal" data-target="#ModalGallery">Kelola Foto</button>
            ';
        })
        ->rawColumns(['id', 'content', 'image', 'actions'])
        ->make(true);
    }

    public function show($id){
        $data = Content::where('id', $id)->with('galleries')->first();
        return response()->json([
            'gallery' => $data
        ]);
    }

    public function getContent(){
        $content = Content::select("*")->orderBy('id', 'desc')->limit(100)->get();
        return response()->json([
            'content' => $content
        ]);
    }

    public function destroy($id){
        $data = ContentGallery::find($id);
        if($data->image){
            $path = public_path('gallery') . '/' . $data->image;
            if(fileExists($path)){
                unlink($path);
            }
        }
        $data->delete();
        return response()->json([
            'message' => 'Content gallery was deleted'
        ]);
    }

    public function getKoleksi($contentId){
        $data = ContentGallery::where('content_id', $contentId)->get();
        return response()->json([
            'gallery' => $data
        ]);
    }
}
