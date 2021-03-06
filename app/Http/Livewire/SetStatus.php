<?php

namespace App\Http\Livewire;

use App\Jobs\NotifyVoters;
use App\Mail\IdeaStatusUpdate;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class SetStatus extends Component
{
    public $idea;
    public $status;
    public $notify;
    public $body;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->status = $idea->status_id;
    }

    public function setStatus()
    {
        if (auth()->user()->isAdmin()) {
            $this->idea->update([
                "status_id" => $this->status
            ]);
            $this->emit('statusUpdateEvent');
//            return redirect()->route('idea',$this->idea);
            $this->dispatchBrowserEvent('close-status');

        } else {
            abort(Response::HTTP_FORBIDDEN);
        }
        if ($this->notify) {
            $voters =  $this->idea->votes()->get(['email'])->toArray();

            foreach ($voters as $user){
                NotifyVoters::dispatch($user['email'], $this->idea)->delay(now()->addSeconds(20));
            }

        }
        Comment::create([
            'user_id'=>auth()->id(),
            'idea_id'=>$this->idea->id,
            'status_id'=>$this->status,
            'body'=> $this->body ?: "No Comment Was Added",
            'is_status_update'=>1
        ]);

        $this->emit('statusUpdate');

    }


    public function render()
    {
        return view('livewire.set-status', [
            'statuses' => Status::all()
        ]);
    }
}
