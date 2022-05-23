<div>
    <div
        class="flex flex-col space-y-4 filters md:flex-row md:items-center md:justify-center md:space-x-6 md:space-y-0">
        <div class="w-full md:w-1/3">
            <select wire:model='category' name="category" id="category" class="w-full px-4 py-2 border-none rounded-xl">
                <option value="All" >All Category</option>
                @foreach($categories as $category)
                    <option value="{{$category->name}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <select name="other_filter" id="other_filter" class="w-full px-4 py-2 border-none rounded-xl">
                <option value="Filter One">Filter One</option>
                <option value="Filter Two">Filter Two</option>
                <option value="Filter three">Filter three</option>
                <option value="Filter four">Category four</option>
            </select>
        </div>
        <div class="relative w-full md:w-2/3">
            <div class="absolute top-0 flex items-center h-full ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <input type="search" placeholder="Find Ideas"
                   class="w-full px-4 py-2 pl-10 placeholder-gray-900 bg-white border-none rounded-xl">
        </div>


    </div>
    {{--    end filters--}}


    <div class="my-6 space-y-6 ideas-container">
        @foreach($ideas as $idea)
            @livewire('idea-index',['idea'=>$idea,'key'=>$idea->id])

        @endforeach
    </div> <!--end ideas-container -->

    <div class="m-5">
        {{$ideas->appends(request()->query())->links()}}
    </div>
</div>