<div class="notification {{ $classClosed }} w-3/4 m-auto mb-1" wire:model.debounce.lazy='close'>
    <div class="notification-header flex flex-row justify-between items-center md:gap-32 gap-8 p-2.5  bg-slate-100 rounded-lg shadow-xl">
        <div wire:click="toggleMessages" class="{{ "notification-" . $mainType }} flex flex-row gap-52 justify-between items-center w-11/12 p-1.5 rounded box-border {{ $hasMessages?"cursor-pointer":"cursor-default" }}">

            <p class="text-black font-semibold text-base m-0">{{ $mainMessage }}</p>

            @if ($hasMessages)
                @if ($subMessagesActive)
                        <x-icon :name="'chevron-up'" />
                @else
                <x-icon :name="'chevron-down'" />
                @endif

            @endif

        </div>

        <button class="close-button" wire:click='close'>
            <x-icon :name="'x'" />
        </button>
        
    </div>

    @if ($hasMessages && $subMessagesActive)
        <div class="notification-body bg-[rgba(255,255,255,0.5)] rounded">

            <div class="flex flex-col gap-2 w-full p-1.5 box-border">
                @foreach ($subMessages as $subMessage)
                    <div class="{{ "notification-" . $subMessage['type'] }} flex flex-row items-center gap-7 w-10/12 p-1.5 rounded">

                        @if ($subMessage['type'] !== 'info')
                            <x-icon :name="$subMessage['type']" :divClasses="'flex items-center jusitfy-center'"/>
                        @else
                            <div class="w-5"></div>
                        @endif

                        <p class="font-medium text-base m-0">{{ $subMessage['message'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    @endif
</div>
