<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class OpenNotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    public function __invoke(Request $request, string $notification): RedirectResponse
    {
        $opened = $this->notifications->open($request->user(), $notification);

        return redirect()->to($opened->data['url'] ?? route($request->user()->dashboardRoute()));
    }
}
