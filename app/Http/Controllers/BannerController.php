<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;

class BannerController extends Controller
{
    public function index(){
        return view('banners.index');
    }

    public function store(Request $request, Banner $banner){
        $rules = [
            'keterangan' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg'
        ];
        $messages = [
            'keterangan.required' => 'Keterangan harus terisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Tipe file harus .jpg/.jpeg/.png'
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
                $bannerData = ['keterangan' => $request->keterangan];

                if($request->hasFile('image')){
                    // cek kalo ada gambar lama
                    $currentImagePath = public_path('banner/' . $request->current_image_banner);
                    if(File::exists($currentImagePath)) {
                        File::delete($currentImagePath);
                    }
                    // upload gambar
                    $foto = $request->file('image');
                    $namaFile = 'banner-' . time() . '-' . Str::slug($foto->getClientOriginalName());
                    $tujuanUpload = 'banner';
                    $foto->move($tujuanUpload, $namaFile);
                    $bannerData['image'] = $namaFile;
                }
                Banner::updateOrCreate(['id' => $request->id], $bannerData);
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Success commited'
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
        $banner = Banner::select("*")->orderBy('id', 'desc')->get();
        // return $banner;
        return DataTables::of($banner)
        ->editColumn('image', function($row){
            return '<img src="'.$row->image.'" alt="" style="width:6.5em">';
        })
        ->addColumn('actions', function($row){
            return '
            <button type="button" class="btn btn-info btn-edit-banner btn-sm" data-id="'.$row->id.'" data-target="#ModalBanner" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-banner btn-sm" data-id="'.$row->id.'">Hapus</button>
            ';
        })
        ->rawColumns(['id', 'keterangan', 'image', 'actions'])
        ->make(true);
    }

    public function show($id){
        $data = Banner::find($id);
        return response()->json([
            'banner' => $data
        ]);
    }

    public function destroy($id){
        $banner = Banner::where('id', $id)->first();
        if($banner->image){
            $path = public_path('banner') . '/' . $banner->image;
            if(fileExists($path)){
                unlink($path);
            }
        }

        $banner->delete();
        return response()->json([
            'message' => 'Banner was deleted'
        ]);
    }
}
