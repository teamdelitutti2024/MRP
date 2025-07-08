<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class ResourcesController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('name', 'asc')->get();

        return view('resources.index', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'cost' => 'required',
            'description' => '',
        ]);

        $resource = $this->store_callback($validated_data['name']);

        if($resource) {
            request()->session()->flash('alertmessage', [
                'message' => 'El recurso ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        $resource = Resource::create($validated_data);

        // Build new key to be updated
        $resource_key = 'RC-' . str_pad($resource->id, 3, '0', STR_PAD_LEFT);

        // Update resource key
        $resource->resource_key = $resource_key;
        $resource->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Recurso agregado',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $resource = Resource::find(request('id'));
        return response()->json($resource);
    }

    public function update(Request $request, Resource $resource)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'cost' => 'required',
            'description' => '',
        ]);

        $existing_resource = $this->update_callback($resource->id, $validated_data['name']);

        if($existing_resource) {
            request()->session()->flash('alertmessage', [
                'message' => 'El recurso ya se encuentra registrado',
                'type' => 'error',
            ]);

            return back();
        }

        $resource->name = $validated_data['name'];
        $resource->cost = $validated_data['cost'];
        $resource->description = $validated_data['description'];
        $resource->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Recurso actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name)
    {
        // Search resource by name
        $resource = Resource::where('name', $name)->first();
        return $resource;
    }

    private function update_callback($id, $name)
    {
        // Search resource by name different from id
        $resource = Resource::where('id', '<>', $id)->where('name', $name)->first();
        return $resource;
    }
}
