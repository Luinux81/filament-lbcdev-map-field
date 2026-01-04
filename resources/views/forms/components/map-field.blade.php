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
        // Enable debug logs with ?map_debug=1 in URL or set APP_DEBUG_MAP=true in .env
        $debugMode = request()->has('map_debug') || config('app.debug_map', false);
    @endphp

    <div
        x-data="{
            latitude: @js($latitude),
            longitude: @js($longitude),
            latitudeField: @js($latitudeField),
            longitudeField: @js($longitudeField),
            debug: @js($debugMode),

            log(...args) {
                if (this.debug) {
                    console.log(...args);
                }
            },

            warn(...args) {
                if (this.debug) {
                    console.warn(...args);
                }
            },

            init() {
                // Listen for coordinate updates from the livewire map component
                Livewire.on('map-coordinates-updated', (eventData) => {
                    this.log('🗺️ Evento map-coordinates-updated recibido (raw):', eventData);

                    // Livewire 3 wraps parameters in an array, so we need to extract the first element
                    const data = Array.isArray(eventData) ? eventData[0] : eventData;

                    this.log('🗺️ Datos extraídos:', data);
                    this.log('🗺️ Campos configurados:', {
                        latitudeField: this.latitudeField,
                        longitudeField: this.longitudeField
                    });

                    if (this.latitudeField && this.longitudeField && data) {
                        // Update the form fields with 'data.' prefix for Filament
                        const latPath = 'data.' + this.latitudeField;
                        const lngPath = 'data.' + this.longitudeField;

                        this.log('🗺️ Actualizando paths:', { latPath, lngPath });
                        this.log('🗺️ Valores a guardar:', {
                            latitude: data.latitude,
                            longitude: data.longitude
                        });

                        $wire.set(latPath, data.latitude);
                        $wire.set(lngPath, data.longitude);

                        // Update local state
                        this.latitude = data.latitude;
                        this.longitude = data.longitude;

                        this.log('✅ Coordenadas actualizadas correctamente');
                    } else {
                        this.warn('⚠️ Faltan datos:', {
                            hasLatitudeField: !!this.latitudeField,
                            hasLongitudeField: !!this.longitudeField,
                            hasData: !!data
                        });
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

