<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return view('products.index',compact('products'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        if ($request->file('picture')) {
            $date_today = Carbon::today()->format('d').'-'.Carbon::today()->format('M').'-'.Carbon::today()->format('Y');
            $path = 'upload/'.$date_today.'/';
            $file = $request->picture;

            $result = self::du_uploads($path,$file);
            if($result['status'] == true){
                $file_name = $result['message'];
            }else{
                session()->flash('error',$result['message']);
                return redirect()->route('admin.products.create');
            }
        }else{
            $file_name = '';
        }

        $pro = new Product;
        $pro->name = $request->name;
        $pro->picture= $file_name;
        $pro->detail = $request->detail;
        $pro->save();

        return redirect()->route('admin.products.index')
                        ->with('success','Product created successfully.');
    }



    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }


    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }


    public function update(Request $request, Product $product)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')
                        ->with('success','Product updated successfully');
    }


    public function destroy(Product $product)
    {
        if (file_exists($product->picture)){
            unlink($product->picture);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success','Product deleted successfully');
    }


    public function du_uploads($path,$file){
        try{
            $base64string = self::convert_to_base64($file); //convert file to base64 string
            if(self::is_base64($base64string) == true){     // check if base64 string is valid

                $type = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION); // get file extension
                $base64string = 'data:image/'.$type.';base64,' . $base64string;

                // check size file as base 64 string
                if(self::check_size($base64string) != true){
                    return [
                        'status'    => false,
                        'message'   => 'file can not be creater than 800000 bits.'
                    ];
                }

                // check directory path if exist else create new dir
                if(self::check_dir($path) != true){
                    return [
                        'status'    => false,
                        'message'   => 'Can not create directory'
                    ];
                }

                // check file type as base 64 string
                if(self::check_file_type($base64string) != true){
                    return [
                        'status'    => false,
                        'message'   => 'File type is not allowed.'
                    ];
                }

                /*======================= uploads =====================*/
                list($type, $base64string) = explode(';', $base64string);
                list(,$extension)          = explode('/',$type);
                list(,$base64string)       = explode(',', $base64string);
                $fileName                  = uniqid().'.'.date('d_m_Y').'.'.$extension;
                $base64string              = base64_decode($base64string);
                $success = file_put_contents($path.$fileName, $base64string);
                if($success){
                    return [
                        'status'    => true,
                        'message'   => $path.$fileName
                    ];
                }else{
                    return [
                        'status'    => false,
                        'message'   => 'Can not upload'
                    ];
                }
            }else{
                return [
                    'status'    => false,
                    'message'   => 'This Base64 String not allowed !'
                ];
            }
        }catch(Exception $e){
            return [
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }

    }

    function is_base64($s){
        // Check if there are valid base64 characters
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
        // Decode the string in strict mode and check the results
        $decoded = base64_decode($s, true);
        if(false === $decoded) return false;
        // Encode the string again
        if(base64_encode($decoded) != $s) return false;
        return true ;
    }

    public function check_size($base64string){
            $file_size = 800000;
            // $size_in_kb    = $size_in_bytes / 1024;
            // $size_in_mb    = $size_in_kb / 1024;
            $size_in_bytes = (int)(strlen(rtrim($base64string, '=')) * 3 / 4);
            if($size_in_bytes > $file_size){
                return false;
            }else{
                return true;
            }

        // $size = getimagesize($base64string);
        // if($size['bits'] >= $file_size){
        //     return 'file size not allowed | only < 8000000 bits';
        // }else{
        //     return true;
        // }
    }

    public function check_dir($path){
        if (!file_exists($path)) {
            $success = mkdir($path, 0777, true); // mkdir(directory,permission,recursive)
            if($success){
                return true;
            }else{
                return false;
            }
        }
        return true;
    }

    public function check_file_type($base64string){
        $mime_type = mime_content_type($base64string);
        $allowed_file_types = ['image/png', 'image/jpeg', 'application/pdf'];
        if (!in_array($mime_type, $allowed_file_types)) {
            // File type is NOT allowed
           return false;
        }
        return true;
    }

    public function convert_to_base64($file){
        $data = file_get_contents($file);
        $base64 = base64_encode($data);
        if($base64){
            return $base64;
        }else{
            return false;
        }
    }
}
