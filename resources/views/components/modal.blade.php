    <div id="selectTypeParent" {{$attributes->merge(['class'=>'hidden fixed top-0 left-0 z-50 flex items-center justify-center w-screen h-screen rounded shadow-lg bg-grey-400 backdrop-blur-sm'])}} aria-hidden="true" >
        <div id="selectTypeChild" {{$attributes->merge(['class'=>'relative h-auto p-10 pt-8 m-auto mx-4 shadow-lg rounded-xl bg-seagreen'])}}>
            {{$slot}}
        </div>
    </div>     