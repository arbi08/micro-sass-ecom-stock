<?php

namespace App\Http\Controllers\Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\Types\CreateTypeRequest;
use App\Http\Requests\Types\UpdateTypeRequest;
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
        $tenant_id = 1;
        $types = $this->typeService->getAll($tenant_id);
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
    public function store(CreateTypeRequest $request)
    {
        $this->typeService->create($request->validated());
        return redirect()->route('types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $type = $this->typeService->findById($id, 1);

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
        $type = $this->typeService->findById($id, 1);
        return view('types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, string $id)
    {
        $this->typeService->update($id, 1, $request->validated());
        return redirect()->route('types.index');
    }

    /**
     * Remove the specified resource from storage. auth()->user()->tenant_id
     */
    public function destroy(string $id)
    {
        $this->typeService->delete($id, 1);
        return redirect()->route('types.index');
    }
}
