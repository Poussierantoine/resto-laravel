<div class="w-4/5 m-auto">


    <table class="w-full">
        <thead>
            <tr>
                @foreach ($columns as $columnName => $column)
                    @if ($column['visible'])
                        <th class="p-4 border-black border">
                            <div class="flex flex-row items-center gap-6 justify-between">

                                <p class="w-fit">{{ $column['label'] }}</p>

                                @if ($column['isSortable'])
                                    <div id="orderBy" class="w-fit h-fit flex flex-col">
                                        <button wire:click="orderBy('{{ $columnName }}', 'ASC')">
                                            <x-icon :name="'chevron-up'" :imgClasses="'w-4 h-4'" :divClasses="'w-4 h-4'" />
                                        </button>
                                        <button wire:click="orderBy('{{ $columnName }}', 'DESC')">
                                            <x-icon :name="'chevron-down'" :imgClasses="'w-4 h-4'" :divClasses="'w-4 h-4'" />
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </th>
                    @endif
                @endforeach

                @if ($canEdit || $canDelete)
                    <th class="p-4 border-solid border-black border">
                        <p class="w-fit">Actions</p>
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>

            @foreach ($models as $model)
                <tr>
                    @foreach ($columns as $columnName => $column)
                        @if ($column['visible'])
                            @if ($editing == $model->id && $column['isEditable'])
                                <td class="p-4 border border-slate-400">

                                    @if ($editForm[$columnName]['type'] == 'textarea')
                                        <textarea wire:model.debounce.500ms="editForm.{{ $columnName }}.value" class="w-full h-full">{{ $editForm[$columnName]['value'] }}</textarea>
                                        @error('editForm.' . $columnName . '.value')
                                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                        @enderror
                                    @elseif($editForm[$columnName]['type'] == 'text')
                                        <input type="text" wire:model="editForm.{{ $columnName }}.value"
                                            class="w-full h-full" value="{{ $editForm[$columnName]['value'] }}">
                                        @error('editForm.' . $columnName . '.value')
                                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                        @enderror
                                    @elseif($editForm[$columnName]['type'] == 'select')
                                        <select wire:model="editForm.{{ $columnName }}.value" class="w-full h-full">
                                            @foreach ($editForm[$columnName]['options'] as $option)
                                                <option value="{{ $option['value'] }}">
                                                    {{ $option['text'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('editForm.' . $columnName . '.value')
                                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                        @enderror
                                    @endif

                                </td>
                            @else
                                <td class="p-4 border border-slate-400">
                                    <div class="flex items-center justify-center max-w-full">

                                        @if ($column['type'] == 'image')
                                            <img src="{{ Storage::url($model->$columnName) }}"
                                                alt="{{ $model->$columnName }}" width="100px" height="100px">
                                        @else
                                            <p class="text-center break-words max-w-[40ch]">{{ $model->$columnName }}
                                            </p>
                                        @endif

                                    </div>
                                </td>
                            @endif
                        @endif
                    @endforeach

                    @if ($editing == $model->id)
                        <td class="p-4 border border-slate-400">
                            <div class="flex flex-col gap-4 items-center justify-center">
                                <form method="POST" wire:submit.prevent="update({{ $model->id }})">
                                    @method('PUT')
                                    @csrf
                                    <button type="submit">
                                        <x-icon :name="'check'" />
                                    </button>
                                </form>
                                <button wire:click="cancelEdit({{ $model->id }})">
                                    <x-icon :name="'x'" />
                                </button>
                            </div>
                        </td>
                    @elseif ($canEdit || $canDelete)
                        <td class="p-4 border border-slate-400">
                            <div class="flex flex-col gap-4 items-center justify-center">

                                @if ($canEdit)
                                    <button wire:click="edit({{ $model->id }})">
                                        <x-icon :name="'edit'" />
                                    </button>
                                @endif
                                @if ($canDelete)
                                    <button wire:click="delete({{ $model->id }})">
                                        <x-icon :name="'trash'" />
                                    </button>
                                @endif

                            </div>
                        </td>
                    @endif
                </tr>

                </form>
            @endforeach

        </tbody>
    </table>


    @if (!$models->count())
        <p class="text-center">Aucun résultat</p>
    @endif
</div>
