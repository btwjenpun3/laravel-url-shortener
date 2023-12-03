<x-adminlte-modal id="timeModal{{ $link->id }}" title="Time Based Link" icon="fas fa-clock" size="md">
    <div id="timeError{{ $link->id }}"></div>
    <x-adminlte-callout theme="secondary">
        <i>Time based link is kind of link that only lasts of certain period time. When the link has expired, then the
            link will no longer accessible.</i>
    </x-adminlte-callout>
    @if (isset($link->time))
        <p>Link will be accessible until: <b>{{ $link->time }}</b></p>
        <div class="input-group date" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#time{{ $link->id }}"
                name="time{{ $link->id }}" id="time{{ $link->id }}" value="{{ $link->time }}"
                placeholder="Choose a time..." />
            <div class="input-group-append" data-target="#time{{ $link->id }}" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    @else
        <div class="input-group date" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#time{{ $link->id }}"
                name="time{{ $link->id }}" id="time{{ $link->id }}" placeholder="Choose a time..." />
            <div class="input-group-append" data-target="#time{{ $link->id }}" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    @endif
    @if (isset($link->time))
        <x-slot name="footerSlot">
            <x-adminlte-button id="removeTime{{ $link->id }}" class="mr-auto" theme="danger" label="Remove Time"
                data-dismiss="modal" onclick="removeTime({{ $link->id }})" />
            <x-adminlte-button theme="outline-secondary" label="Close" data-dismiss="modal" />
            <x-adminlte-button id="edit" theme="primary" label="Update" onclick="time({{ $link->id }})" />
        </x-slot>
    @else
        <x-slot name="footerSlot">
            <x-adminlte-button theme="outline-secondary" label="Close" data-dismiss="modal" />
            <x-adminlte-button id="edit" theme="danger" label="Set" onclick="time({{ $link->id }})" />
        </x-slot>
    @endif
</x-adminlte-modal>
