<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('products.index', compact('products'));
    }

    public function add()
    {
        return view('products.add');
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'expiry_days' => 'required|integer',
            'category_id' => 'required',
            'status' => 'required',
            'is_cake' => 'required',
            'product_order' => 'required|integer',
            'mobile_order' => 'required|integer',
            'photo' => 'sometimes|image',
            'description' => ''
        ]);

        $product = $this->store_callback($validated_data['name']);

        if($product) {
            request()->session()->flash('alertmessage', [
                'message' => 'El producto ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        if(isset($validated_data['photo'])) {
            $validated_data['photo'] = $request->file('photo')->store('products', 'public');
        }

        $validated_data['category_id'] = $validated_data['category_id'] == 0 ? null : $validated_data['category_id'];

        $product = Product::create($validated_data);

        request()->session()->flash('alertmessage', [
            'message' => 'Producto agregado',
            'type' => 'success',
        ]);

        return redirect('/products');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Saving the photo in case it was not edited
        $photo = $product->photo;

        $validated_data = $request->validate([
            'name' => 'required',
            'expiry_days' => 'required|integer',
            'category_id' => 'required',
            'status' => 'required',
            'is_cake' => 'required',
            'product_order' => 'required|integer',
            'mobile_order' => 'required|integer',
            'photo' => 'sometimes|image',
            'description' => ''
        ]);

        $existing_product = $this->update_callback($product->id, $validated_data['name']);

        if($existing_product) {
            request()->session()->flash('alertmessage', [
                'message' => 'El producto ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        if(isset($validated_data['photo'])) {
            $photo = $request->file('photo')->store('products', 'public');
        }

        $validated_data['category_id'] = $validated_data['category_id'] == 0 ? null : $validated_data['category_id'];

        $product->name = $validated_data['name'];
        $product->expiry_days = $validated_data['expiry_days'];
        $product->category_id = $validated_data['category_id'];
        $product->status = $validated_data['status'];
        $product->is_cake = $validated_data['is_cake'];
        $product->product_order = $validated_data['product_order'];
        $product->mobile_order = $validated_data['mobile_order'];
        $product->photo = $photo;
        $product->description = $validated_data['description'];
        $product->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Producto actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name)
    {
        // Search product by name
        $product = Product::where('name', $name)->first();
        return $product;
    }

    private function update_callback($id, $name)
    {
        // Search product by name different from id
        $product = Product::where('id', '<>', $id)->where('name', $name)->first();
        return $product;
    }

    public function detail(Product $product)
    {
        return view('products.detail', compact('product'));
    }

    public function change_status($id = null, $status = null)
    {
        $product = Product::find($id);

        $message = '';
        
        if($status == 'active') {
            $product->status = true;
            $message = 'activado';
        }
        else {
            $product->status = false;
            $message = 'desactivado';
        }

        $product->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Producto ' . $message,
            'type' => 'success',
        ]);

        return back();
    }
}
