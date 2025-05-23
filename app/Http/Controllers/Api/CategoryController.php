<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $categories = Category::all();
        return $this->sendResponse('Categories fetched successfully', CategoryResource::collection($categories));
    }
} 