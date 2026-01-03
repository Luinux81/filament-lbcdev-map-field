<?php

namespace Lbcdev\FilamentMapField\Forms\Components;

use Filament\Forms\Components\Field;

class MapField extends Field
{
    protected string $view = 'filament-map-field::forms.components.map-field';

    protected string|null $latitudeField = null;
    protected string|null $longitudeField = null;
    protected int $height = 400;
    protected int $zoom = 15;
    protected bool $showPasteButton = false;
    protected bool $showLabel = true;
    protected bool $interactive = true;

    /**
     * Set the latitude field name
     */
    public function latitude(string $field): static
    {
        $this->latitudeField = $field;
        return $this;
    }

    /**
     * Set the longitude field name
     */
    public function longitude(string $field): static
    {
        $this->longitudeField = $field;
        return $this;
    }

    /**
     * Set the map height in pixels
     */
    public function height(int $height): static
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Set the default zoom level
     */
    public function zoom(int $zoom): static
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * Show the paste coordinates button
     */
    public function showPasteButton(bool $show = true): static
    {
        $this->showPasteButton = $show;
        return $this;
    }

    /**
     * Show the coordinates label
     */
    public function showLabel(bool $show = true): static
    {
        $this->showLabel = $show;
        return $this;
    }

    /**
     * Set if the map is interactive
     */
    public function interactive(bool $interactive = true): static
    {
        $this->interactive = $interactive;
        return $this;
    }

    /**
     * Make the field read-only (non-interactive)
     * This is an alias for interactive(false) to maintain compatibility with Filament's standard API
     */
    public function readOnly(bool $condition = true): static
    {
        $this->interactive = !$condition;
        return $this;
    }

    /**
     * Get the latitude field name
     */
    public function getLatitudeField(): ?string
    {
        return $this->latitudeField;
    }

    /**
     * Get the longitude field name
     */
    public function getLongitudeField(): ?string
    {
        return $this->longitudeField;
    }

    /**
     * Get the map height
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Get the zoom level
     */
    public function getZoom(): int
    {
        return $this->zoom;
    }

    /**
     * Check if paste button should be shown
     */
    public function shouldShowPasteButton(): bool
    {
        return $this->showPasteButton;
    }

    /**
     * Check if label should be shown
     */
    public function shouldShowLabel(): bool
    {
        return $this->showLabel;
    }

    /**
     * Check if map is interactive
     */
    public function isInteractive(): bool
    {
        return $this->interactive;
    }

    /**
     * Get the current coordinates from the form
     */
    public function getCoordinates(): array
    {
        $container = $this->getContainer();

        if ($this->latitudeField && $this->longitudeField) {
            return [
                'latitude' => data_get($container->getState(), $this->latitudeField),
                'longitude' => data_get($container->getState(), $this->longitudeField),
            ];
        }

        return [
            'latitude' => null,
            'longitude' => null,
        ];
    }
}
