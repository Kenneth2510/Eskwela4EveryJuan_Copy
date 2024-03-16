<button {{ $attributes->merge([
    'class' => 'flex flex-row items-center justify-center py-4 px-4 mx-2 rounded-lg shadow-lg bg-'. (in_array($color, $customColors) ? $color : $color.'-400') .' hover:bg-'.(in_array($color, $customColors) ? $color : $color.'-500').' md:h-12',
    'type'=> 'submit'
    ])}}>
    <h1>{{ $name }}</h1>
    {{$slot}}
</button>