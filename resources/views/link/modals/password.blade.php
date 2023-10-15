<x-adminlte-modal id="passwordModal{{ $link->id }}" title="Set Password" icon="fas fa-lock" size='lg'
    theme="primary">
    <div id="error{{ $link->id }}"></div>
    <x-adminlte-callout theme="secondary">
        <i>Protected link is kind of link that can be given a Secret key / Passphrase before being redirected to the
            original link.</i>
    </x-adminlte-callout>
    <h5>Password</h5>
    <x-adminlte-input type="text" id="password{{ $link->id }}" name="edited_title" placeholder="*****"
        igroup-size="md">
        @if (isset($link->password))
            <x-slot name="bottomSlot">
                <span class="text-sm text-gray">
                    You already make password for this link. If you wish to change your current password, fill you new
                    password into form then click <code>Change Password</code>.
                </span>
            </x-slot>
        @endif
    </x-adminlte-input>
    <x-slot name="footerSlot">
        @if (isset($link->password))
            <x-adminlte-button id="remove_password{{ $link->id }}" class="mr-auto" theme="danger"
                label="Remove Password" data-dismiss="modal" onclick="removePassword({{ $link->id }})" />
        @endif
        <x-adminlte-button class="ml-auto" theme="danger" label="Close" data-dismiss="modal" />
        @if (isset($link->password))
            <x-adminlte-button class="edit-button" theme="outline-secondary" label="Change Password"
                onclick="password({{ $link->id }})" />
        @else
            <x-adminlte-button class="edit-button" theme="primary" label="Set Password"
                onclick="password({{ $link->id }})" />
        @endif
    </x-slot>
</x-adminlte-modal>
