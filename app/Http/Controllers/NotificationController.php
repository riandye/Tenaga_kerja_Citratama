<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
{
    // Temukan notifikasi berdasarkan ID
    $notification = notifications::find($id);

    if ($notification) {
        // Perbarui kolom read_at dengan timestamp saat ini
        $notification->read_at = now();
        $notification->save();

        return response()->json(['status' => 'success', 'message' => 'Notification marked as read']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Notification not found'], 404);
    }
}

}

