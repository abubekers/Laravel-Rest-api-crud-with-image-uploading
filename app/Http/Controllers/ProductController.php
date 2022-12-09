<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use UrlSigner;
use Carbon\Carbon;
class ProductController extends Controller
{
    public function index()
    {
       // All Product
       $products = Product::latest()->paginate(10);

      // $fileName = Str::random(32).".".$request->file->getClientOriginalExtension();
      // $url = UrlSigner::sign(url($fileName), Carbon::now()->addSeconds(600));
      //  return view('file', compact('url'));
       // Return Json Response
       return response()->json([
          'products' => $products
       ],200);
    }
 
    public function store(ProductStoreRequest $request)
    {
        try {
            $fileName = Str::random(32).".".$request->file->getClientOriginalExtension();
     
            // Create Product
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'file' => $fileName,
                'type' => $request->type
            ]);
     
            // Save Image in Storage folder in public
            // Storage::disk('public')->put($fileName, file_get_contents($request->file));
            //save image file in private folder
             Storage::disk('local')->put('private/' .$fileName, file_get_contents($request->file));
            // Return Json Response
            return response()->json([
                'message' => "Product successfully created."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went wrong please try again!"
            ],500);
        }
    }
 
    public function show($id)
    {
       // Product Detail 
       $product = Product::find($id);
       if(!$product){
         return response()->json([
            'message'=>'Woops! Product Not Found.'
         ],404);
       }
     
       // Return Json Response
       return response()->json([
          'product' => $product
       ],200);
    }
 
    public function update(ProductStoreRequest $request, $id)
    {
        try {
            // Find product
            $product = Product::find($id);
            if(!$product){
              return response()->json([
                'message'=>'Product Not Found.'
              ],404);
            }
     
            $product->name = $request->name;
            $product->description = $request->description;
            $product->type = $request->type;
     
            if($request->file) {
                // Public storage
                $storage = Storage::disk('public');
     
                // Old iamge delete
                if($storage->exists($product->file))
                    $storage->delete($product->file);
     
                // Image name
                $fileName = Str::random(32).".".$request->file->getClientOriginalExtension();
                $product->file = $fileName;
     
                // Image save in public folder
                $storage->put($fileName, file_get_contents($request->file));
            }
     
            // Update Product
            $product->save();
     
            // Return Json Response
            return response()->json([
                'message' => "Product successfully updated."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went wrong please try again!"
            ],500);
        }
    }
 
    public function destroy($id)
    {
        // Detail 
        $product = Product::find($id);
        if(!$product){
          return response()->json([
             'message'=>'Product Not Found.'
          ],404);
        }
     
        // Public storage
        $storage = Storage::disk('public');
     
        // Iamge delete
        if($storage->exists($product->file))
            $storage->delete($product->file);
     
        // Delete Product
        $product->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "Product successfully deleted."
        ],200);
    }
}