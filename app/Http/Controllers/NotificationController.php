<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return NotificationResource::collection($this->notifications->latestFor($request->user()))
            ->additional(['meta' => ['unread' => $this->notifications->unreadCountFor($request->user())]]);
    }
}
