<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return auth()
            ->user()
            ->conversations()
            ->with('users')
            ->latest()
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $conversation = Conversation::create([
            'tenant_id' => auth()->user()->tenant->id,
            'type' => 'direct',
        ]);

        $conversation->users()->attach([
            auth()->id(),
            $request->user_id
        ]);

        return $conversation->load('users');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        $conversation = Conversation::with('users', 'messages')
            ->findOrFail($conversation->id);

        return $conversation;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
