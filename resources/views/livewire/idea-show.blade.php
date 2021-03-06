<div class="idea-buttons-show">
    <div
        class="idea-container mt-5 bg-white rounded-xl  flex ">
        <div class="flex flex-1 px-4 py-6 flex-col md:flex-row ">
            <a href="#" class="flex-none h-fit m-3">
                <img src="{{$idea->user->getAvatar()}}" alt="avatar"
                     class="w-14 h-14 rounded-2xl">
            </a>
            <div class="px-2 w-full">
                <h4 class="text-xl font-semibold ">
                    <a href="#" class="hover:underline">{{$idea->title}}</a>
                </h4>
                <div class="text-gray-600 mt-4">{!! nl2br(e($idea->description)) !!}</div>
                <div class="flex md:justify-between md:items-center flex-col md:flex-row mt-5 space-y-3 md:space-y-0">
                    <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                        <div class="font-bold text-gray-900 md:block hidden">{{$idea->user->name}}</div>
                        <div class="hidden md:block">&bull;</div>
                        <div>{{$idea->created_at->diffForHumans()}}</div>
                        <div class="hidden md:block">&bull;</div>
                        <div>{{$idea->category->name}}</div>
                        <div class="hidden md:block">&bull;</div>
                        <div class="text-gray-800">{{$idea->comments->count()}} comment</div>
                        <div class="hidden md:block">&bull;</div>

                    </div>
                    <div class="flex justify-between items-center px-4">
                        <div class="md:hidden flex items-center">
                            <div class="bg-gray-200  text-center text-xxs rounded-l-2xl h-10 px-4 py-2">
                                <div
                                    class="font-bold leading-none text-xs @if($hasVoted) text-blue @endif">{{$votes}}</div>
                                <div class="font-bold uppercase text-gray-600 ">Votes</div>
                            </div>
                            @if($hasVoted)
                                <button wire:click.prevent="vote"
                                        class="text-white bg-blue px-3 py-2 h-10 rounded-2xl  uppercase hover:bg-blue-hover transition duration-300 ease-in hover:border-blue border -mx-3 font-bold">
                                    Voted
                                </button>
                            @else
                                <button wire:click.prevent="vote"
                                        class="px-3 py-2 h-10 rounded-2xl  uppercase hover:bg-gray-600 transition duration-300 ease-in hover:border-gray-300 border hover:text-white -mx-3 font-bold bg-gray-400 ">
                                    Vote
                                </button>
                            @endif
                        </div>
                        <div class="flex space-x-2 items-center">
                            <div
                                style="background-color: {{$idea->status->color}}"
                                class="text-xxs font-bold text-white uppercase leading-none text-center w-28 rounded-full h-7 py-2 px-4">
                                {{$idea->status->name}}
                            </div>


                            <div class="relative"
                                 x-data="{
                            isOpen : false
                            }" @keydown.esc.window="isOpen =false"
                                 @close-modal.window="isOpen =false">
                                <button @click="isOpen = !isOpen"
                                        class="relative bg-gray-200 hover:bg-gray-300 rounded-full h-7 px-2 transition duration-300 ease-in ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                    </svg>
                                </button>
                                <ul @click.outside="isOpen = false"
                                    x-show="isOpen" x-transition.origin.top.left.duration.200ms x-cloak
                                    class="absolute p-0 w-44 font-semibold text-sm shadow-lg   bg-white overflow-hidden text-left right-0 md:left-5  mt-1 md:mt-0 rounded-xl ">
                                    @can("update",$idea)
                                        <li><a href="#"
                                               @click.prevent="$dispatch('edit-modal')"
                                               class="hover:bg-gray-200 px-5 py-3 block transition w-full duration-200 ease-in">Edit Idea</a></li>
                                    @endcan

                                    @can('delete',$idea)
                                        <li><a href="#"
                                               @click.prevent="$dispatch('delete-modal')"
                                               class="hover:bg-gray-200 px-5 py-3 block transition w-full duration-200 ease-in">Delete Idea</a></li>
                                    @endcan
                                    <li><a href="#"
                                           @click.prevent="$dispatch('spam-modal')"
                                           class="hover:bg-gray-200 px-5 py-3 block transition w-full duration-200 ease-in">Mark As Spam</a></li>
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                            <li><a href="#" wire:click.prevet="resetSpam()"
                                                   class="hover:bg-gray-200 px-5 py-3 block transition w-full duration-200 ease-in">Not A Spam</a></li>
                                        @endif
                                    @endauth
                                </ul>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div> <!--end idea-container-->
    <div
        class="buttons  px-5 py-8 flex items-center w-full justify-between flex-col md:flex-row space-y-2 md:space-y-0">
        <div class="flex justify-center space-x-3 items-center ">
            @livewire("add-comment",['idea'=>$idea])
            @auth
                @if(auth()->user()->isAdmin())
                    @livewire("set-status", ['idea'=>$idea])
                @endif
            @endauth

        </div>
        <div class="hidden md:flex justify-center items-center space-x-3">
            <div class="bg-white font-semibold text-center rounded-xl px-2 py-2 shadow drop-shadow">
                <div class="text-xl leading-snug text-xs uppercase @if($hasVoted) text-blue @endif">{{$votes}}</div>
                <div class="text-gray-400 leading-none text-xs uppercase">Votes</div>
            </div>
            @if($hasVoted)
                <button wire:click.prevent="vote"
                        class="text-white bg-blue px-3 py-2 h-10 rounded-2xl  uppercase hover:bg-blue-hover transition duration-300 ease-in hover:border-blue border  -mx-3 font-bold ">
                    Voted
                </button>
            @else
                <button wire:click.prevent="vote"
                        class="px-3 py-2 h-10 rounded-2xl  uppercase hover:bg-gray-600 transition duration-300 ease-in hover:border-gray-300 border hover:text-white -mx-3 font-bold bg-gray-400 ">
                    Vote
                </button>
            @endif

        </div>

    </div> <!-- end button-container-->

</div>
