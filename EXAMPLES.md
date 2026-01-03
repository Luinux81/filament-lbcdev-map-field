# Ejemplos de Uso

Este documento contiene ejemplos prácticos de cómo usar el paquete `filament-map-field`.

## Ejemplo 1: Resource Completo con Ubicaciones

```php
<?php

namespace App\Filament\Resources;

use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Lbcdev\FilamentMapField\Forms\Components\MapField;
use Lbcdev\FilamentMapField\Infolists\Entries\MapEntry;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('address')
                            ->label('Dirección')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Coordenadas')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitud')
                                    ->numeric()
                                    ->required()
                                    ->minValue(-90)
                                    ->maxValue(90)
                                    ->step(0.000001)
                                    ->placeholder('40.416775'),
                                
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitud')
                                    ->numeric()
                                    ->required()
                                    ->minValue(-180)
                                    ->maxValue(180)
                                    ->step(0.000001)
                                    ->placeholder('-3.703790'),
                            ]),
                        
                        MapField::make('map')
                            ->label('Seleccionar en el mapa')
                            ->latitude('latitude')
                            ->longitude('longitude')
                            ->height(500)
                            ->zoom(15)
                            ->showPasteButton()
                            ->showLabel()
                            ->columnSpanFull()
                            ->helperText('Haz clic en el mapa para seleccionar la ubicación o arrastra el marcador.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Latitud')
                    ->numeric(decimalPlaces: 6)
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Longitud')
                    ->numeric(decimalPlaces: 6)
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información General')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nombre'),
                        
                        Infolists\Components\TextEntry::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        
                        Infolists\Components\TextEntry::make('address')
                            ->label('Dirección')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Ubicación')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('latitude')
                                    ->label('Latitud')
                                    ->numeric(decimalPlaces: 6),
                                
                                Infolists\Components\TextEntry::make('longitude')
                                    ->label('Longitud')
                                    ->numeric(decimalPlaces: 6),
                            ]),
                        
                        MapEntry::make('map')
                            ->label('Mapa')
                            ->latitude('latitude')
                            ->longitude('longitude')
                            ->height(400)
                            ->zoom(15)
                            ->showLabel()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view' => Pages\ViewLocation::route('/{record}'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
```

## Ejemplo 2: Modelo Location

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
```

## Ejemplo 3: Migración

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address', 500)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
```

## Ejemplo 4: Formulario con Múltiples Ubicaciones

```php
Forms\Components\Repeater::make('locations')
    ->label('Ubicaciones')
    ->schema([
        Forms\Components\TextInput::make('name')
            ->label('Nombre')
            ->required(),
        
        Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\TextInput::make('latitude')
                    ->label('Latitud')
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('longitude')
                    ->label('Longitud')
                    ->numeric()
                    ->required(),
            ]),
        
        MapField::make('map')
            ->latitude('latitude')
            ->longitude('longitude')
            ->height(300)
            ->showPasteButton()
            ->columnSpanFull(),
    ])
    ->columnSpanFull()
    ->collapsible()
    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
```

## Ejemplo 5: Formulario con Tabs para Origen y Destino

```php
Forms\Components\Tabs::make('Route')
    ->tabs([
        Forms\Components\Tabs\Tab::make('Origen')
            ->icon('heroicon-o-map-pin')
            ->schema([
                Forms\Components\TextInput::make('origin_name')
                    ->label('Nombre del origen')
                    ->required(),
                
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('origin_latitude')
                            ->label('Latitud')
                            ->numeric()
                            ->required(),
                        
                        Forms\Components\TextInput::make('origin_longitude')
                            ->label('Longitud')
                            ->numeric()
                            ->required(),
                    ]),
                
                MapField::make('origin_map')
                    ->label('Ubicación de origen')
                    ->latitude('origin_latitude')
                    ->longitude('origin_longitude')
                    ->height(400)
                    ->zoom(15)
                    ->showPasteButton(),
            ]),
        
        Forms\Components\Tabs\Tab::make('Destino')
            ->icon('heroicon-o-flag')
            ->schema([
                Forms\Components\TextInput::make('destination_name')
                    ->label('Nombre del destino')
                    ->required(),
                
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('destination_latitude')
                            ->label('Latitud')
                            ->numeric()
                            ->required(),
                        
                        Forms\Components\TextInput::make('destination_longitude')
                            ->label('Longitud')
                            ->numeric()
                            ->required(),
                    ]),
                
                MapField::make('destination_map')
                    ->label('Ubicación de destino')
                    ->latitude('destination_latitude')
                    ->longitude('destination_longitude')
                    ->height(400)
                    ->zoom(15)
                    ->showPasteButton(),
            ]),
    ])
    ->columnSpanFull(),
```

## Ejemplo 6: Infolist con Secciones Colapsables

```php
Infolists\Components\Section::make('Ubicación en el Mapa')
    ->description('Visualización de la ubicación en el mapa')
    ->schema([
        Infolists\Components\Grid::make(3)
            ->schema([
                Infolists\Components\TextEntry::make('latitude')
                    ->label('Latitud')
                    ->numeric(decimalPlaces: 6)
                    ->copyable()
                    ->icon('heroicon-o-map-pin'),
                
                Infolists\Components\TextEntry::make('longitude')
                    ->label('Longitud')
                    ->numeric(decimalPlaces: 6)
                    ->copyable()
                    ->icon('heroicon-o-map-pin'),
                
                Infolists\Components\TextEntry::make('coordinates')
                    ->label('Coordenadas')
                    ->state(fn ($record) => "{$record->latitude}, {$record->longitude}")
                    ->copyable()
                    ->icon('heroicon-o-clipboard'),
            ]),
        
        MapEntry::make('map')
            ->label(false)
            ->latitude('latitude')
            ->longitude('longitude')
            ->height(500)
            ->zoom(15)
            ->columnSpanFull(),
    ])
    ->collapsible()
    ->collapsed(false),
```

