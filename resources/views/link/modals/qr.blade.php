<x-adminlte-modal id="qrModal{{ $link->id }}" title="QR Code" icon="fas fa-qrcode" theme="primary" size="sm">
    <div class="d-flex justify-content-center">
        {{ QrCode::size(250)->format('svg')->generate(env('APP_URL') . '/' . $link->short_url) }}
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="outline-secondary" label="Close" data-dismiss="modal" />
        <a href="{{ route('link.download', ['url' => $link->short_url]) }}" download="qr-{{ $link->short_url }}.svg">
            <x-adminlte-button id="edit" theme="primary" label="Download" />
        </a>
    </x-slot>
</x-adminlte-modal>
