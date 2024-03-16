<button {{$attributes->merge(['class'=>'flex flex-row items-center justify-center px-4 h-10 m-2 bg-gray-300 rounded-lg hover:bg-gray-400 md:h-12'])}}>
    <h1>{{$name}}</h1>
    {{$slot}}
</button>