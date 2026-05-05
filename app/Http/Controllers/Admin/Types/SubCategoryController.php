<?php

namespace App\Http\Controllers\Admin\Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\Types\CreateSubCategoryRequest;
use App\Http\Requests\Types\UpdateSubCategoryRequest;
use App\Services\Types\SubCategoryService;

class SubCategoryController extends Controller
{
    
    public function __construct(private SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenant_id = 1;
        $types = $this->subCategoryService->getAll($tenant_id);
        return response()->json($types);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubCategoryRequest $request)
    {
        $this->subCategoryService->create($request->validated());
        return redirect()->route('subcategories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $type = $this->subCategoryService->findById($id, 1);
            return response()->json([
                'data' => $type
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Type not found'
            ], 404);
        }

   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type = $this->subCategoryService->findById($id, 1);
        return view('types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, string $id)
    {
        $this->subCategoryService->update($id, 1, $request->validated());
        return redirect()->route('subcategories.index');
    }

    /**
     * Remove the specified resource from storage. auth()->user()->tenant_id
     */
    public function destroy(string $id)
    {
        $this->subCategoryService->delete($id, 1);
        return redirect()->route('subcategories.index');
    }
}
