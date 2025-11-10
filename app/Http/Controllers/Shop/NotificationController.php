<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebNotificationResource;
use App\Models\Notification;
use App\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    // fetch notifications for admin
    public function index()
    {
        $shop = generaleSetting('shop');

        $notifications = NotificationRepository::query()->where('shop_id', $shop->id)->orderBy('is_read', 'asc')->latest('id')->take(10)->get();

        $total = NotificationRepository::query()->where('shop_id', $shop->id)->whereIsRead(false)->count();

        return $this->json('notifications', [
            'total' => $total >= 10 ? '9+' : $total,
            'notifications' => WebNotificationResource::collection($notifications),
        ]);
    }

    // mark as read
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        if ($notification->url != null) {
            return redirect()->to($notification->url);
        }

        return back();
    }

    // show notification list
    public function show()
    {
        $shop = generaleSetting('shop');
        $notifications = NotificationRepository::query()->where('shop_id', $shop->id)->orderBy('is_read', 'asc')->latest('id')->paginate(20);

        return view('shop.notification', compact('notifications'));
    }

    // mark all as read
    public function markAllAsRead()
    {
        $shop = generaleSetting('shop');
        NotificationRepository::query()->where('shop_id', $shop->id)->update(['is_read' => true]);

        return back()->withSuccess(__('All notifications marked as read!'));
    }

    // destroy notification
    public function destroy(Notification $notification)
    {

        $notification->delete();

        return back()->withSuccess(__('Notification deleted!'));
    }
}
