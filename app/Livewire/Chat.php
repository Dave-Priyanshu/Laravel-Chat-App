<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $user;
    public $message;
    public $senderId;
    public $receiverId;
    public $messages;

    public function mount($userId){
        // dd($userId);
        $this->user = $this->getUser($userId);
        $this->senderId = Auth::id();
        $this->receiverId = $userId;

        $this->messages = $this->getMessages();

        // dd($messages);

    }
    public function render()
    {
        return view('livewire.chat');
    }

    public function getMessages(){
        return Message::with('sender:id,name','receiver:id,name')
            ->where(function($query) {
                $query->where('sender_id', $this->senderId)
                    ->where('receiver_id', $this->receiverId);
            })
            ->orWhere(function($query) {
                $query->where('sender_id', $this->receiverId)
                    ->where('receiver_id', $this->senderId);
            })
            ->get();
    }


    public function getUser($userId){
        return User::find($userId);
    }

    public function sendMessage(){
        // dd($this->message);
        $this->saveMessage();

        $this->message = null;
    }

    public function saveMessage(){
        return Message::create([
        'sender_id'=>$this->senderId,
        'receiver_id' => $this->receiverId,
        'message'=> $this->message,
        // 'file_name',
        // 'file_original_name',
        // 'folder_path',
        'is_read'=>false,
        ]);
    }
}
