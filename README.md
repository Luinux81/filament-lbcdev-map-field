# Filament Map Field

Un paquete de Filament que proporciona componentes de campo de mapa para formularios e infolists, utilizando el componente Livewire [lbcdev-map](https://github.com/Luinux81/livewire-lbcdev-component-map).

## ✨ Características

- 🗺️ **MapField** para formularios Filament (interactivo)
- 📋 **MapEntry** para infolists Filament (solo lectura)
- 🎯 Integración perfecta con el componente Livewire lbcdev-map
- 📍 Soporte para campos de latitud/longitud separados
- ⚡ Actualización reactiva de coordenadas
- 🎨 Compatible con el tema de Filament
- 🔧 Altamente configurable

## 📋 Requisitos

- PHP 8.1 o superior
- Laravel 10.x o 11.x
- Filament 3.x
- Livewire 3.x
- [lbcdev/livewire-map-component](https://github.com/Luinux81/livewire-lbcdev-component-map) ^1.0

## 📦 Instalación

### 1. Instalar el paquete via Composer

```bash
composer require lbcdev/filament-map-field
```

### 2. Incluir Leaflet.js en tu layout

El paquete depende de `lbcdev/livewire-map-component`, que requiere Leaflet.js. Agrega estos scripts en el `<head>` de tu layout principal:

```html
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

### 3. (Opcional) Publicar las vistas

Si deseas personalizar las vistas del componente:

```bash
php artisan vendor:publish --tag=filament-map-field-views
```

Las vistas se publicarán en `resources/views/vendor/filament-map-field/`.

## 🚀 Uso

### MapField en Formularios

El componente `MapField` permite a los usuarios seleccionar coordenadas de forma interactiva en un formulario.

#### Uso básico

```php
use Lbcdev\FilamentMapField\Forms\Components\MapField;

MapField::make('location')
    ->latitude('latitude')
    ->longitude('longitude');
```

#### Con todas las opciones

```php
MapField::make('location')
    ->latitude('latitude')      // Campo donde se guardará la latitud
    ->longitude('longitude')    // Campo donde se guardará la longitud
    ->height(500)              // Altura del mapa en píxeles (default: 400)
    ->zoom(15)                 // Nivel de zoom inicial (default: 15)
    ->showPasteButton()        // Mostrar botón para pegar coordenadas
    ->showLabel()              // Mostrar etiqueta con coordenadas
    ->interactive();           // Permitir interacción (default: true)
```

#### Ejemplo completo en un Resource

```php
<?php

namespace App\Filament\Resources;

use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Lbcdev\FilamentMapField\Forms\Components\MapField;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->maxLength(255),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->required(),
                    ]),

                MapField::make('map')
                    ->latitude('latitude')
                    ->longitude('longitude')
                    ->height(500)
                    ->zoom(15)
                    ->showPasteButton()
                    ->columnSpanFull(),
            ]);
    }
}
```

### MapEntry en Infolists

El componente `MapEntry` muestra las coordenadas en un mapa de solo lectura en infolists.

#### Uso básico

```php
use Lbcdev\FilamentMapField\Infolists\Entries\MapEntry;

MapEntry::make('location')
    ->latitude('latitude')
    ->longitude('longitude');
```

#### Con opciones

```php
MapEntry::make('location')
    ->latitude('latitude')
    ->longitude('longitude')
    ->height(400)
    ->zoom(15)
    ->showLabel();
```

#### Ejemplo completo en un Resource

```php
<?php

namespace App\Filament\Resources;

use App\Models\Location;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Lbcdev\FilamentMapField\Infolists\Entries\MapEntry;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),

                Infolists\Components\TextEntry::make('address'),

                Infolists\Components\Grid::make(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('latitude')
                            ->numeric(decimalPlaces: 6),

                        Infolists\Components\TextEntry::make('longitude')
                            ->numeric(decimalPlaces: 6),
                    ]),

                MapEntry::make('map')
                    ->latitude('latitude')
                    ->longitude('longitude')
                    ->height(400)
                    ->zoom(15)
                    ->columnSpanFull(),
            ]);
    }
}
```

## 🎨 Métodos Disponibles

### MapField (Forms)

| Método | Descripción | Default |
|--------|-------------|---------|
| `latitude(string $field)` | Campo donde se guardará la latitud | `null` |
| `longitude(string $field)` | Campo donde se guardará la longitud | `null` |
| `height(int $height)` | Altura del mapa en píxeles | `400` |
| `zoom(int $zoom)` | Nivel de zoom inicial (1-20) | `15` |
| `showPasteButton(bool $show = true)` | Mostrar botón para pegar coordenadas | `false` |
| `showLabel(bool $show = true)` | Mostrar etiqueta con coordenadas | `true` |
| `interactive(bool $interactive = true)` | Permitir interacción con el mapa | `true` |

### MapEntry (Infolists)

| Método | Descripción | Default |
|--------|-------------|---------|
| `latitude(string $field)` | Campo de donde leer la latitud | `null` |
| `longitude(string $field)` | Campo de donde leer la longitud | `null` |
| `height(int $height)` | Altura del mapa en píxeles | `300` |
| `zoom(int $zoom)` | Nivel de zoom inicial (1-20) | `15` |
| `showLabel(bool $show = true)` | Mostrar etiqueta con coordenadas | `true` |

## 💡 Ejemplos Avanzados

### Formulario con validación

```php
Forms\Components\Grid::make(2)
    ->schema([
        Forms\Components\TextInput::make('latitude')
            ->numeric()
            ->required()
            ->minValue(-90)
            ->maxValue(90)
            ->step(0.000001),

        Forms\Components\TextInput::make('longitude')
            ->numeric()
            ->required()
            ->minValue(-180)
            ->maxValue(180)
            ->step(0.000001),
    ]),

MapField::make('map')
    ->latitude('latitude')
    ->longitude('longitude')
    ->height(600)
    ->zoom(12)
    ->showPasteButton()
    ->columnSpanFull()
    ->required(),
```

### Múltiples mapas en un formulario

```php
Forms\Components\Tabs::make('Locations')
    ->tabs([
        Forms\Components\Tabs\Tab::make('Origen')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('origin_latitude')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('origin_longitude')
                            ->numeric()
                            ->required(),
                    ]),

                MapField::make('origin_map')
                    ->latitude('origin_latitude')
                    ->longitude('origin_longitude')
                    ->height(400)
                    ->showPasteButton(),
            ]),

        Forms\Components\Tabs\Tab::make('Destino')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('destination_latitude')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('destination_longitude')
                            ->numeric()
                            ->required(),
                    ]),

                MapField::make('destination_map')
                    ->latitude('destination_latitude')
                    ->longitude('destination_longitude')
                    ->height(400)
                    ->showPasteButton(),
            ]),
    ]),
```

## 🔧 Personalización

### Publicar y personalizar vistas

```bash
php artisan vendor:publish --tag=filament-map-field-views
```

Las vistas estarán disponibles en:
- `resources/views/vendor/filament-map-field/forms/components/map-field.blade.php`
- `resources/views/vendor/filament-map-field/infolists/entries/map-entry.blade.php`

## 🤝 Créditos

Este paquete utiliza:
- [lbcdev/livewire-map-component](https://github.com/Luinux81/livewire-lbcdev-component-map) - Componente Livewire de mapas
- [Leaflet.js](https://leafletjs.com/) - Biblioteca de mapas interactivos
- [Filament](https://filamentphp.com/) - Framework de administración para Laravel

## 📄 Licencia

Este paquete es software de código abierto licenciado bajo la [Licencia MIT](LICENSE).

## 🐛 Soporte

Si encuentras algún problema o tienes sugerencias:

- 🐛 [Reportar un bug](https://github.com/Luinux81/filament-lbcdev-map-field/issues)
- 💡 [Solicitar una característica](https://github.com/Luinux81/filament-lbcdev-map-field/issues)

## 👨‍💻 Autor

Desarrollado por [Luinux81](https://github.com/Luinux81)
