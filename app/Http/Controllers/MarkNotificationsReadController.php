<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class MarkNotificationsReadController extends Controller
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->notifications->markAllRead($request->user());

        return response()->noContent();
    }
}
