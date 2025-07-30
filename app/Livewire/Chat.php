<?php

namespace App\Livewire;

use App\Events\MessageSentEvent;
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
        $this->dispatch('messages-updated'); // Changed from 'message-updated' for consistency
    }

    public function render()
    {
        return view('livewire.chat', [
            'senderId' => $this->senderId, // Pass senderId to Blade
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

    public function getUser($userId)
    {
        return User::find($userId);
    }

    public function sendMessage()
    {
        $sentMessage = $this->saveMessage();
        $this->messages[] = $sentMessage;
        broadcast(new MessageSentEvent($sentMessage));
        $this->message = null;
        $this->dispatch('messages-updated');
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