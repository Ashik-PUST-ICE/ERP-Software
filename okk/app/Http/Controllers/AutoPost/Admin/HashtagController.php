<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HashtagController extends Controller
{
    /**
     * Get all hashtags (for API)
     */
    public function index(Request $request)
    {
        $hashtags = Hashtag::where('status', STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $hashtags
        ]);
    }

    

    /**
     * Store a newly created hashtag
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:hashtags,name'
        ]);

        // Add # prefix if not present
        $name = $request->name;
        if (!Str::startsWith($name, '#')) {
            $name = '#' . $name;
        }

        $hashtag = Hashtag::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => STATUS_ACTIVE,
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Hashtag created successfully',
            'data' => $hashtag
        ]);
    }

    
    
}