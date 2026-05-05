<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tenant;
use App\Models\TenantCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterTenantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        return DB::transaction(function () use ($request) {
            /* =========================
                      1. CREATE OWNER USER
                   ========================= */
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => null,  // غادي يتربط من بعد
                'status' => 'active',
            ]);
            /* =========================
               2. CREATE TENANT
            ========================= */
            $tenant = Tenant::create([
                'name' => $request->shop_name,
                'slug' => Str::slug($request->shop_name) . '-' . uniqid(),
                'owner_id' => $user->id,
                'status' => 'active',
                'plan' => 'free',
            ]);

            /* =========================
               3. attach tenant to user
            ========================= */
            $user->update([
                'tenant_id' => $tenant->id
            ]);

            /* =========================
               4. AUTO SETUP (IMPORTANT)
            ========================= */

            // 🔥 ننسخو categories default
            // $categories = Category::whereNull('parent_id')->get();

            // foreach ($categories as $category) {
            //     TenantCategory::create([
            //         'tenant_id' => $tenant->id,
            //         'category_id' => $category->id,
            //         'name' => $category->name,
            //         'parent_id' => null,
            //     ]);
            // }

            /* =========================
               5. LOGIN
            ========================= */
            auth()->login($user);

            /* =========================
               6. REDIRECT
            ========================= */
            return redirect()->route('vendor.types.index');
        });
    }
}
