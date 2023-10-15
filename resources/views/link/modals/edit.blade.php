<x-adminlte-modal id="editModal{{ $link->id }}" title="Edit" icon="fas fa-edit" size='lg' theme="primary">
    <div id="invalid{{ $link->id }}"></div>
    <h5>Title (Optional)</h5>
    <x-adminlte-input type="text" id="edited_title{{ $link->id }}" name="edited_title"
        placeholder="{{ $link->title }}" value="{{ $link->title }}" igroup-size="md">
    </x-adminlte-input>
    <h5>Short URL</h5>
    <x-adminlte-input type="text" id="edited_short_url{{ $link->id }}" name="edited_short_url"
        placeholder="{{ $link->short_url }}" value="{{ $link->short_url }}" igroup-size="md">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                {{ env('APP_URL') }}/
            </div>
        </x-slot>
        <x-slot name="bottomSlot">
            <span class="text-sm text-gray">
                <i class="fas fa-info-circle"></i> Only alphabets (A-Z, a-z), numeric (0-9), and minus(-) are allowed.
                <code>No Space!</code>
            </span>
            <br>
            <span class="text-sm text-gray">
                <i class="fas fa-info-circle"></i> Changing links also changing QR Code
                information.
            </span>
        </x-slot>
    </x-adminlte-input>
    <h5>Original URL</h5>
    <x-adminlte-input type="text" id="edited_original_url{{ $link->id }}" name="edited_original_url"
        placeholder="{{ $link->original_url }}" value="{{ $link->original_url }}" igroup-size="md" readonly>
    </x-adminlte-input>
    <x-slot name="footerSlot">
        <x-adminlte-button class="ml-auto" theme="danger" label="Dismiss" data-dismiss="modal" />
        <x-adminlte-button id="edit" class="edit-button" theme="primary" label="Save"
            onclick="edit({{ $link->id }})" />
    </x-slot>
</x-adminlte-modal>
