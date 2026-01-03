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
    @endphp

    <div 
        x-data="{
            latitude: @js($latitude),
            longitude: @js($longitude),
            latitudeField: @js($latitudeField),
            longitudeField: @js($longitudeField),
            
            init() {
                // Listen for coordinate updates from the livewire map component
                Livewire.on('map-coordinates-updated', (data) => {
                    if (this.latitudeField && this.longitudeField) {
                        // Update the form fields
                        $wire.set(this.latitudeField, data.latitude);
                        $wire.set(this.longitudeField, data.longitude);
                        
                        // Update local state
                        this.latitude = data.latitude;
                        this.longitude = data.longitude;
                    }
                });
            }
        }"
        wire:key="{{ $statePath }}-map-field"
    >
        <livewire:lbcdev-map 
            :latitude="$latitude" 
            :longitude="$longitude"
            :interactive="$interactive"
            :showLabel="$showLabel"
            :showPasteButton="$showPasteButton"
            :height="$height"
            :zoom="$zoom"
            wire:key="{{ $statePath }}-lbcdev-map"
        />
    </div>
</x-dynamic-component>

