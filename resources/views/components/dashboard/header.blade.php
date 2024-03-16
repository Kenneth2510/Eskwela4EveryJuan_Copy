@props(['type'=> 'search', 'name', 'id', 'placeholder'])

<div {{$attributes->merge(['class'=>'flex flex-row items-center justify-between h-20 px-4'])}}>
    <h1 {{$attributes->merge(['class'=>'text-xl font-semibold md:text-4xl'])}}>{{$title}}</h1>
    <form {{$attributes->merge(['class'=>'relative flex flex-row items-center'])}} action="">
        <button {{$attributes->merge(['class'=>'absolute left-0'])}} type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
        </button>
        <input {{$attributes->merge(['class'=>'w-32 h-10 pl-6 rounded-lg md:w-64 md:h-12'])}} type={{$type}} name={{$name}} id={{$id}} placeholder={{$placeholder}}>
    </form>
</div>