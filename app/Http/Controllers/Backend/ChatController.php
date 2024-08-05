<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function SendMessage(Request $request)
    {

        $request->validate([
            'msg' => 'required'
        ]);

        ChatMessage::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'msg' => $request->msg,
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Message Send Successfully']);
    } // End Method 

    public function GetAllUsers()
    {
        // $chats = ChatMessage::orderBy('id', 'DESC')
        //     ->where('sender_id', auth()->id())
        //     ->orWhere('receiver_id', auth()->id())
        //     ->get();

        //// lấy tất cả message liên quan đến người dùng hiện tại (người gửi or người nhận)
        $chats = ChatMessage::orderBy('id', 'DESC')
            ->where(function ($query) {
                $query->where('sender_id', auth()->id()) // tin nhắn gửi đi
                    ->orWhere('receiver_id', auth()->id()); // tin nhắn nhận lại (phản hồi)
            })
            ->get();

        ///// lấy những user liên quan đến đoạn chat
        $users = $chats->flatMap(function ($chat) {
            if ($chat->sender_id === auth()->id()) {
                return [$chat->sender, $chat->receiver];
            }
            return [$chat->receiver, $chat->sender];
        })->unique();

        return $users;
    } // End Method 

    public function UserMsgById($userId)
    {

        $user = User::find($userId);

        if ($user) {
            // lấy tất cả tin nhắn của user hiện tại đang đăng nhập và user được chọn
            // nghĩa là: tất cả tin nhắn mà user hiện tại (auth()->id()) gửi tới user khác hoặc
            // user khác gửi tới user hiện tại
            $messages = ChatMessage::where(function ($q) use ($userId) {
                // all tin nhắn user hiện tại -> user khác
                $q->where('sender_id', auth()->id());
                $q->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($userId) {
                // all tin nhắn user khác -> user hiện tại
                $q->where('sender_id', $userId);
                $q->where('receiver_id', auth()->id());
            })->with('user')->get();

            // => dịch sang SQL: 
            // SELECT * FROM chat_messages WHERE (sender_id = {auth()->id()} 
            // AND receiver_id = {$userId}) OR (sender_id = {$userId} AND receiver_id = {auth()->id()})


            return response()->json([
                'user' => $user,
                'messages' => $messages,
            ]);
        } else {
            abort(404);
        }
    } // End Method


    public function LiveChat()
    {
        return view('instructor.chat.live_chat');
    }
}
