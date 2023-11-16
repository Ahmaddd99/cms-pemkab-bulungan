<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use App\Models\AttributesValue;
use App\Models\Category;
use App\Models\Content;
use App\Models\ContentGallery;
use App\Models\ContentRating;
use App\Models\Feature;
use App\Models\FeatureValue;
use App\Models\Rating;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\fileExists;
use function Symfony\Component\String\b;

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
            'meta' => 'required'
        ];
        $messages = [
            'category_id.required' => 'kategori id harus terisi',
            'image.image' => 'file harus berupa gambar',
            'image.mimes' => 'file harus berupa jpeg, jpg, atau png',
            'title.required' => 'title harus terisi',
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
                    'meta' => $request->meta,
                    'qrcode' => $request->qrcode,
                    'order' => $request->content_order
                ];

                if ($request->subcategory_id) {
                    $contentData['subcategory_id'] = $request->subcategory_id;
                } else {
                    $contentData['subcategory_id'] = null;
                }

                // cek gambar berdasarkan id
                // $id = $request->id;
                // $content = Content::find($id);
                // if($content->image){
                //     $gambar = storage_path('content/' . $content->image);
                //     if (File::exists($gambar)) {
                //         File::delete($gambar);
                //     }
                // }

                if ($request->hasFile('image')) {
                    $currentImagePath = public_path('content/' . $request->current_image);
                    if(File::exists($currentImagePath)) {
                        File::delete($currentImagePath);
                    }
                    $imgContent = $request->file('image');
                    $namaFile = 'content-' . time() . '_' . Str::slug($request->title)  .  '.'  .  $imgContent->extension();
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

                if ($request->feature_id) {
                    FeatureValue::updateOrCreate(
                        [
                            'id' => $request->id_featureValue
                        ],
                        [
                            'feature_id' => $request->feature_id,
                            'content_id' => $content->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]
                    );
                }

                if($request->attribute_id){
                    // mulai loop attribute
                    $items = $request->attribute_id;
                    $dataItems = [];
                    for ($i = 0; $i < count($items); $i++) {
                        $dataItems[] = [
                                'id' => $request->attribute_value_id[$i],
                                'content_id' => $content->id,
                                'attribut_id' => $request->attribute_id[$i],
                                'description' => $request->description[$i],
                                'order' => $request->order[$i]
                        ];
                    }
                    AttributesValue::upsert($dataItems, ['id']);
                }

                if ($request->rating_id){
                    $content->ratings()->sync($request->rating_id);
                }

                //content gallery
                $galleryImages = [];
                if($images = $request->file('image_gallery')){
                    foreach ($images as $image){
                        $namaImage = 'gallery-' . time() . '-' . Str::slug($image)  .  '.'  .  $image->extension();
                        $image->move('gallery', $namaImage);
                        $galleryImages[] = [
                            'content_id' => $content->id,
                            'image' => $namaImage
                        ];
                    }
                }

                ContentGallery::upsert($galleryImages, ['id']);

                DB::commit();

                return response()->json([
                    'message' => "Commit was success",
                ], 200);

            } catch (Throwable $e) {
                DB::rollBack();
                return response()->json([
                    'message' => "Error in server " . $e->getMessage()
                ], 500);
            }
        }
    }

    public function createAttribute(Request $request)
    {
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
        if ($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validation->getMessageBag()->toArray()
            ], 422);
        } else {
            DB::beginTransaction();
            try {
                $name = $request->name;
                $attribute = Attribut::updateOrCreate(
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
                    'message' => 'Success post data',
                    'attribute' => [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                    ]
                ], 200);
            } catch (Throwable $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal server error ' . $e
                ], 500);
            }
        }
    }

    public function deleteAttributeValue($id){
        $data = AttributesValue::find($id);
        if($data){
            $data->delete();
            return response()->json([
                'message' => 'success deleted attribute value'
            ]);
        }
    }

    public function createFeature(Request $request){
        $rules = [
            'title' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg',
            'published' => 'required',
            'order' => 'nullable'
        ];
        $messages = [
            'title.required' => 'Title harus terisi',
            'image.image' => 'file harus berbentuk gambar',
            'image.mimes' => 'tipe file harus .jpg/.jpeg/.png',
            'published.required' => 'published harus terisi',
        ];
        $validation = Validator::make($request->all(), $messages, $rules);
        if($validation->fails()) {
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
                    $imgFeature = $request->file('image');
                    $namaFile = 'feature-' . time() . '_' . Str::slug($request->title)  .  '.'  .  $imgFeature->extension();
                    $tujuanUpload = 'feature';
                    $imgFeature->move($tujuanUpload, $namaFile);
                    $featureData['image'] = $namaFile;
                }

                Feature::updateOrCreate(['id' => $request->id], $featureData);
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Success post feature"
                ], 200);
            }catch(Throwable $e){
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => "Internal server error" . $e->getMessage()
                ], 500);
            }
        }
    }

    public function datatables()
    {
        $data = Content::select("*")->with('getRatings', function($q) {
            $q->with('rating');
        })->orderBy('id', 'desc')->with('category', 'subcategory')->get();
        // return $data;
        return DataTables::of($data)
            ->editColumn('category_id', function ($row) {
                return $row->category->name;
            })
            ->editColumn('subcategory_id', function ($row) {
                if($row->subcategory == null){
                    return "-";
                }else{
                    return $row->subcategory->name;
                }
            })
            ->editColumn('title', function($row){
                $ratings = '';
                foreach($row->getRatings as $data) {
                    $ratings .= ' <img src="../../rating/'.$data->rating->icon.'" alt="'.$data->rating->name.'" class="mx-1" style="max-width:80px" />';
                }
                if($row->qrcode !== null){
                    return '<div>
                                <strong>'.$row->title.'</strong>
                                <span class="badge badge-pill badge-success" style="font-size:0.55em">QR Code</span>
                                <p>'.$row->meta.'</p>
                                '.$ratings.'
                            </div>
                            ';
                } else {
                    return '
                        <div>
                            <strong>'.$row->title.'</strong>
                            <p>'.$row->meta.'</p>
                            '.$ratings.'
                        </div>
                    ';
                }
            })
            ->editColumn('image', function ($row) {
                return '<img src="../../content/' . $row->image . '" alt="" style="width:6.5em">';
            })
            ->addColumn('actions', function ($row) {
                return '
            <button type="button" class="btn btn-info btn-edit-content btn-sm" data-id="' . $row->id . '" data-target="#ModalContent" data-toggle="modal" >Edit</button>
            <button type="button" class="btn btn-danger btn-delete-content btn-sm" data-id="' . $row->id . '">Hapus</button>
            ';
            })
            ->rawColumns(['category_id', 'subcategory_id', 'image', 'title', 'actions'])
            ->make(true);
    }

    public function show($id)
    {
        $data = Content::where('id', $id)->with('getRatings')->with('category', 'subcategory', 'featureValue')->with('attributValue', function ($q) {
            $q->select('id', 'attribut_id', 'content_id', 'description', 'order')->with('attribut');
        })->with('galleries')->first();
        return response()->json([
            'content' => $data
        ]);
    }

    public function dynamicSelectCategory($id){
        $data = Category::whereId($id)->with('subcategory')->first();
        return response()->json([
            'category' => $data
        ]);
    }

    public function rating(){
        $data = Rating::orderBy('id', 'desc')->get();
        return response()->json([
            'rating' => $data
        ]);
    }

    public function getCategoryId()
    {
        $data = Category::select("*")->limit(100)->orderBy('id', 'desc')->get();
        return response()->json([
            'category' => $data
        ]);
    }

    public function getSubcategoryId($categoryId)
    {
        $data = Subcategory::select("*")->where('category_id', $categoryId)->limit(100)->orderBy('id', 'desc')->get();
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

    public function getAttribute()
    {
        $data = Attribut::select("*")->limit(100)->orderBy('id', 'desc')->get();
        return response()->json([
            'attribute' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Content::where('id', $id)->with('featureValue')->first();
        if ($data->image) {
            $path = public_path('content') . '/' . $data->image;
            if (fileExists($path)) {
                unlink($path);
            }
        }

        $data->delete();
        return response()->json([
            'message' => 'Content was deleted'
        ]);
    }

    public function getgallery($contentid){
        $data = ContentGallery::where('content_id', $contentid)->get();
        return response()->json([
            'gallery' => $data
        ]);
    }

    public function deleteGallery($id){
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

    public function clearRatings($contentid){
        $data = ContentRating::where('content_id', $contentid);
        if($data){
            $data->delete();
            return response()->json([
                "Berhasil membersihkan rating"
            ]);
        }
    }
}
