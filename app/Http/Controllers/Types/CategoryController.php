<?php

namespace App\Http\Controllers\Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\Types\CreateCategoryRequest;
use App\Http\Requests\Types\UpdateCategoryRequest;
use App\Services\Types\CategoryService;

class CategoryController extends Controller
{
    
    public function __construct(private CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenant_id = 1;
        $types = $this->categoryService->getAll($tenant_id);
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
    public function store(CreateCategoryRequest $request)
    {
        $this->categoryService->create($request->validated());
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $type = $this->categoryService->findById($id, 1);
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
        $type = $this->categoryService->findById($id, 1);
        return view('types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $this->categoryService->update($id, 1, $request->validated());
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage. auth()->user()->tenant_id
     */
    public function destroy(string $id)
    {
        $this->categoryService->delete($id, 1);
        return redirect()->route('categories.index');
    }
}
