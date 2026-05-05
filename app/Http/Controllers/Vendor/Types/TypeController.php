<?php

namespace App\Http\Controllers\Vendor\Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\Types\CreateTypeRequest;
use App\Http\Requests\Types\UpdateTypeRequest;
use App\Models\Category;
use App\Services\Types\TypeService;

class TypeController extends Controller
{
    public function __construct(
        private TypeService $typeService
    ) {
        $this->typeService = $typeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenantId = app('tenant_id');
        $types = $this->typeService->getAll();
        $typesByTenant = $this->typeService->getAll($tenantId);
        $featuredTypes = $this->typeService->getFeaturedTypesWithArticles();
        return inertia('Vendor/Types/Index', [
            'types' => $types,
            'typesByTenant' => $typesByTenant,
            'featuredTypes' => $featuredTypes
        ]);
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
    public function store(CreateTypeRequest $request)
    {
        $tenantId = app('tenant_id');
        $this->typeService->create($request->validated(), $tenantId);
        return redirect()->route('vendor.types.index');
    }

    public function attachTypeToTenant($id, $tenantId = null)
    {
        $type = $this->typeService->findById($id);

        $this->typeService->attachTypeToTenant($type);
        return redirect()->route('vendor.types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tenantId = app('tenant_id');
        $type = $this->typeService->findById($id, $tenantId);

        if (!$type) {
            return response()->json([
                'message' => 'Type not found'
            ], 404);
        }

        return response()->json([
            'data' => $type
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenantId = app('tenant_id');
        $type = $this->typeService->findById($id, $tenantId);
        return view('types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, string $id)
    {
        $tenantId = app('tenant_id');
        $this->typeService->update($id, $tenantId, $request->validated());
        return redirect()->route('vendor.types.index');
    }

    /**
     * Remove the specified resource from storage. auth()->user()->tenant_id
     */
    public function destroy(string $id)
    {
        $tenantId = app('tenant_id');
        $this->typeService->delete($id, $tenantId);
        return redirect()->route('vendor.types.index');
    }
}
