<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $coordinates = $getCoordinates();
        $latitude = $coordinates['latitude'];
        $longitude = $coordinates['longitude'];
        $latitudeField = $getLatitudeField();
        $longitudeField = $getLongitudeField();
        $height = $getHeight();
        $zoom = $getZoom();
        $showPasteButton = $shouldShowPasteButton();
        $showLabel = $shouldShowLabel();
        $interactive = $isInteractive();
        $statePath = $getStatePath();
        $isDisabled = $isDisabled();
    @endphp

    <div
        x-data="{
            latitude: @js($latitude),
            longitude: @js($longitude),
            latitudeField: @js($latitudeField),
            longitudeField: @js($longitudeField),

            updateCoordinates(lat, lng) {
                if (this.latitudeField && this.longitudeField) {
                    // Support both simple fields ('latitude') and nested fields ('ubicacion.latitud')
                    // The dot notation is automatically handled by Livewire's $set method
                    $wire.$set('data.' + this.latitudeField, lat);
                    $wire.$set('data.' + this.longitudeField, lng);

                    // Update local state
                    this.latitude = lat;
                    this.longitude = lng;
                }
            },

            init() {
                // Listen for coordinate updates from the livewire map component
                window.addEventListener('map-coordinates-updated', (event) => {
                    const data = event.detail;
                    if (data && data.latitude !== undefined && data.longitude !== undefined) {
                        this.updateCoordinates(data.latitude, data.longitude);
                    }
                });

                // Also listen for Livewire events (for backward compatibility)
                Livewire.on('map-coordinates-updated', (data) => {
                    if (data && data.latitude !== undefined && data.longitude !== undefined) {
                        this.updateCoordinates(data.latitude, data.longitude);
                    }
                });
            }
        }"
        wire:key="{{ $statePath }}-map-field"
    >
        <livewire:lbcdev-map
            :latitude="$latitude"
            :longitude="$longitude"
            :interactive="$interactive && !$isDisabled"
            :showLabel="$showLabel"
            :showPasteButton="$showPasteButton"
            :height="$height"
            :zoom="$zoom"
            wire:key="{{ $statePath }}-lbcdev-map"
        />
    </div>
</x-dynamic-component>

