<?php

namespace App\Livewire;

use App\Events\MessageSentEvent;
use App\Events\UnreadMessage;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public $user;
    public $message;
    public $senderId;
    public $receiverId;
    public $messages;

    public function mount($userId)
    {
        $this->user = $this->getUser($userId);
        $this->senderId = Auth::id();
        $this->receiverId = $userId;
        $this->messages = $this->getMessages();
        $this->dispatch('messages-updated'); 
        
        $this->readAllMessages();
    }

    public function render()
    {
        $this->readAllMessages();

        return view('livewire.chat', [
            'senderId' => $this->senderId, 
        ]);
    }

    public function getMessages()
    {
        return Message::with('sender:id,name', 'receiver:id,name')
            ->where(function ($query) {
                $query->where('sender_id', $this->senderId)
                    ->where('receiver_id', $this->receiverId);
            })
            ->orWhere(function ($query) {
                $query->where('sender_id', $this->receiverId)
                    ->where('receiver_id', $this->senderId);
            })
            ->get();
    }

    public function userTyping()
    {
        broadcast(new UserTyping($this->senderId, $this->receiverId))->toOthers();
    }

    public function readAllMessages(){
        Message::where('sender_id',$this->receiverId)
        ->where('receiver_id',$this->senderId)
        ->where('is_read', false)
        ->update(['is_read' => true]);
    }

    public function getUser($userId)
    {
        return User::find($userId);
    }

    public function sendMessage()
    {
        $sentMessage = $this->saveMessage();
        $this->messages[] = $sentMessage;
        broadcast(new MessageSentEvent($sentMessage));

        $unreadMessageCount = $this->getUnreadMessageCount();
        broadcast(new UnreadMessage($this->senderId, $this->receiverId,$unreadMessageCount))->toOthers();
        $this->message = null;
        $this->dispatch('messages-updated');

    }

    public function getUnreadMessageCount(){
        return Message::where('receiver_id',$this->receiverId)
        ->where('is_read', false)->count();
    }

    #[On('echo-private:chat-channel.{senderId},MessageSentEvent')]
    public function listenMessage($event)
    {
        $newMessage = Message::find($event['message']['id'])->load('sender:id,name', 'receiver:id,name');
        $this->messages[] = $newMessage;
        $this->dispatch('messages-updated'); // Add this to ensure UI updates
    }

    public function saveMessage()
    {
        return Message::create([
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'message' => $this->message,
            'is_read' => false,
        ]);
    }
}