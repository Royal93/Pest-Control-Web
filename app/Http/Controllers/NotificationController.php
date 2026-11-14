<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * The notification bell on the admin portal and SP SALES. Admins and reps are
 * separate models (admin_users / users) but share Laravel's notifications table;
 * the route group decides which guard's user we're looking at.
 */
class NotificationController extends Controller
{
    /** JSON for the bell: unread count and the latest notifications. Polled every 30 seconds. */
    public function feed(Request $request)
    {
        $user = $this->user($request);

        return response()->json(self::payload($user));
    }

    /** Open one notification: mark it read and go to the page it's about. */
    public function open(Request $request, string $id)
    {
        $notification = $this->user($request)->notifications()->whereKey($id)->firstOrFail();
        $notification->markAsRead();

        $url = (string) ($notification->data['url'] ?? '/');

        // Only ever redirect within this site.
        if (! str_starts_with($url, '/') || str_starts_with($url, '//')) {
            $url = '/';
        }

        return redirect($url);
    }

    public function readAll(Request $request)
    {
        $this->user($request)->unreadNotifications()->update(['read_at' => now()]);

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back();
    }

    /** What the bell needs, used for the first page load and for every poll. */
    public static function payload($user, int $limit = 15): array
    {
        if (! $user) {
            return ['unread' => 0, 'items' => []];
        }

        $prefix = $user instanceof \App\Models\AdminUser ? '/admin' : '/sales';

        return [
            'unread' => $user->unreadNotifications()->count(),
            'items' => $user->notifications()->latest()->limit($limit)->get()->map(fn ($n) => [
                'id' => $n->id,
                'kind' => $n->data['kind'] ?? 'info',
                'title' => $n->data['title'] ?? '',
                'body' => $n->data['body'] ?? '',
                'href' => $prefix.'/notifications/'.$n->id,
                'time' => $n->created_at->diffForHumans(),
                'read' => $n->read_at !== null,
            ])->all(),
        ];
    }

    private function user(Request $request)
    {
        $user = $request->routeIs('admin.*') ? Auth::guard('admin')->user() : Auth::user();

        abort_unless($user, 403);

        return $user;
    }
}
