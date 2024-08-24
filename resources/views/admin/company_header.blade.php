@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

<div class="row" style="margin:0;padding: 0;">
    <div class="col-12 text-center"> <!-- Added text-center class -->
        <div class="d-flex justify-content-center align-items-center">
            @if ($settings !== null)
                <div class="d-flex flex-column" style="font-size: 14px">
                    <span style="font-size: 26px; font-weight:bold">{{ $settings->full_name }}</span>
                    <span>Address: {{ $settings->address }}</span>

                    @if ($settings->contact1 && $settings->contact2)
                        <span>Phone: {{ $settings->contact1 }}, {{ $settings->contact2 }}</span>
                    @elseif ($settings->contact1)
                        <span>Phone: {{ $settings->contact1 }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
