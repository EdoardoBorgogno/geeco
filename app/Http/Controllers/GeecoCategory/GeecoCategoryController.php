<?php

namespace App\Http\Controllers\GeecoCategory;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\GeecoCategory;
use Illuminate\Http\Request;

class GeecoCategoryController extends Controller
{
    public function index()
    {
        //Return all GeecoCategories

        try
        {
            $geecoCategories = GeecoCategory::all();
            return response()->json(['GeecoCategories' => $geecoCategories], 200);
        }
        catch (Exception $e)
        {
            return response()->json(['Message' => "Sorry, Something went wrong." ], 500);
        }
    }

    public function store(Request $request)
    {
        
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }
}
