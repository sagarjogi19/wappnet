<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-screen">
   <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
      <div class="flex items-center space-x-4">
         <div class="flex flex-col leading-tight">
            <div class="text-2xl mt-1 flex items-center">
               <span class="text-gray-700 mr-3">{{$user->name}}</span>
               <span class="text-green-500">
                  <svg width="10" height="10">
                     <circle cx="5" cy="5" r="5" fill="currentColor"></circle>
                  </svg>
               </span>
            </div>
         </div>
      </div>
   </div>
   <div id="messages" class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
       @foreach($chat as $v)
      <div class="chat-message">
         <div class="flex items-end {{($v->sender_id==Auth::id())?'justify-end':''}}">
            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
               <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none {{($v->sender_id==Auth::id())?'bg-blue-600 text-white':'bg-gray-300 text-gray-600'}}">{{$v->message}}</span></div>
            </div>
            
         </div>
      </div>
      @endforeach
      
      
      
   </div>
   <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
      <div class="relative flex">
         <form action="{{route('users.store')}}" method="POST">
               <input type="hidden" name="_token" value="{{ csrf_token() }}" >
         <input type="text" placeholder="Write Something" class="w-full msg focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-full py-3">
         <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
           
            <button type="button" class="inline-flex items-center justify-center rounded-full send-msg h-12 w-12 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 transform rotate-90">
                  <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
               </svg>
            </button>
         </div>
         </form>
      </div>
   </div>
</div>

<style>
.scrollbar-w-2::-webkit-scrollbar {
  width: 0.25rem;
  height: 0.25rem;
}

.scrollbar-track-blue-lighter::-webkit-scrollbar-track {
  --bg-opacity: 1;
  background-color: #f7fafc;
  background-color: rgba(247, 250, 252, var(--bg-opacity));
}

.scrollbar-thumb-blue::-webkit-scrollbar-thumb {
  --bg-opacity: 1;
  background-color: #edf2f7;
  background-color: rgba(237, 242, 247, var(--bg-opacity));
}

.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
  border-radius: 0.25rem;
}
</style>

<script>
	const el = document.getElementById('messages')
	el.scrollTop = el.scrollHeight
	var socket = io.connect('http://205.134.254.135:8008');
	var sender_id='{{Auth::id()}}';
	var receiver_id='{{$user->id}}';
    socket.on('message', function (data) {
        data = jQuery.parseJSON(data);
        console.log(data.message);
        if((data.sender_id==sender_id && data.receiver_id==receiver_id) || (data.sender_id==receiver_id && data.receiver_id==sender_id)){
        if(data.sender_id==sender_id){
            var class1='justify-end';
            var class2='bg-blue-600 text-white';
        } else {
            var class1='';
            var class2='bg-gray-300 text-gray-600';
        }
        html='<div class="chat-message"><div class="flex items-end '+class1+'"><div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start"><div><span class="px-4 py-2 '+class2+'rounded-lg inline-block rounded-bl-none">'+data.message+'</span></div></div></div></div>';
        $( "#messages" ).append(html);
        }
      });
	 $(".send-msg").click(function(e){
        e.preventDefault();
        var token = $("input[name='_token']").val();
        var receiver_id = '{{$user->id}}';
        var msg = $(".msg").val();
        if(msg != ''){
            $.ajax({
                type: "POST",
                url: "{{route('users.store')}}",
                dataType: "json",
                data: {'_token':token,'message':msg,'receiver_id':receiver_id},
                success:function(data){
                    console.log(data);
                    $(".msg").val('');
                }
            });
        }else{
            alert("Please Add Message.");
        }
    })
</script>
        </div>
    </div>
</x-app-layout>
