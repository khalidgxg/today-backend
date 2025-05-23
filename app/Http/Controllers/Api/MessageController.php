<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;

class MessageController extends Controller
{
    use ApiResponseTrait;

    public function getMessagesByCategory($id)
    {
        $messages = Message::where('category_id',$id)->get();
       
        return $this->sendResponse('Messages fetched successfully', MessageResource::collection($messages));
    }
} 