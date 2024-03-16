<div {{$attributes->merge(['class'=>'px-4 pt-4 mt-16 md:mx-auto md:w-3/4 md:pt-8 lg:w-full lg:p-0 lg:m-0'])}}>
    <h1 {{$attributes->merge(['class'=>'my-2 text-3xl font-bold md:text-4xl'])}}>{{ $title }}</h1>
    {{ $slot }}
</div>