<x-layout.layout>
    @include('dashboard.search-section')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col lg:flex-row gap-8">
        
        @include('dashboard.filter')

        @include('dashboard.room-list')

    </div>
</x-layout.layout>