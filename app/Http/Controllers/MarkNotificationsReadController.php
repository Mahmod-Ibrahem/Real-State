<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class MarkNotificationsReadController extends Controller
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->notifications->markAllRead($request->user());

        return back();
    }
}
