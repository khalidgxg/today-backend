<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $this->sendResponse('Categories fetched successfully', CategoryResource::collection($categories));
    }

    public function sendResponse($message, $data = null, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }
} 