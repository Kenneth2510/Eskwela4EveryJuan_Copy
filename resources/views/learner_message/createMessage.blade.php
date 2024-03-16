@include('partials.header')

<section class="flex flex-row w-full h-screen text-sm main-container bg-mainwhitebg md:text-base">
    @include('partials.instructorNav')
    @include('partials.learnerSidebar')

        {{-- MAIN --}}
    <section class="w-full px-2 pt-[20px] mx-2 mt-2 md:w-3/4 lg:w-9/12  overscroll-auto md:overflow-auto">
        <div class="h-screen px-3 pb-4 overflow-auto rounded-lg shadow-lg b overscroll-auto">




        </div>
    </section>


{{-- @include('partials.learnerProfile') --}}
</section>

{{-- modal area --}}
@include('partials.footer')