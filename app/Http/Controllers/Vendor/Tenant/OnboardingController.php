<?php

namespace App\Http\Controllers\Vendor\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    public function index()
    {
        return Inertia::render('Onboarding', [
            'step' => auth()->user()->onboarding_step
        ]);
    }

    public function step1(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'onboarding_step' => 2
        ]);

        return redirect('/onboarding');
    }

    public function step2(Request $request)
    {
        $user = auth()->user();

        $tenant = \App\Models\Tenant::create([
            'name' => $request->company_name,
            'owner_id' => $user->id,
            'slug' => Str::slug($request->company_name)
        ]);

        $user->update([
            'tenant_id' => $tenant->id,
            'onboarding_step' => 3
        ]);

        return redirect('/onboarding');
    }

    public function finish()
    {
        $user = auth()->user();

        $user->update([
            'onboarding_step' => 999  // completed
        ]);

        return redirect('/dashboard');
    }
}
