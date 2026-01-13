# Twill Geo Package

This package allows you to easily manage Geo Structured Data (JSON-LD) for your Twill models.

It works by adding a temporary field to your Twill form, which is then captured and saved into its own dedicated database table. This keeps your main model's table clean while still providing a seamless editing experience.

Key features:
- **Custom Twill Form fieldset**: ready-to-use fieldset that includes a custom form field that allows for both manual entry and AI-powered generation of geo-structured data.
- **Frontend Component**: Includes a ready-to-use Blade component for outputting the saved data as JSON-LD in your frontend templates.

## Requirements

- **PHP**: `^8.1`
- **Laravel (Illuminate/Support)**: `^10.0`
- **Twill**: `^3.0`

## Installation

### 1. Install package

```bash
composer require futuraddb/twill-geo
```

### 2. Publish Assets and Config

To use the package, you need to publish the assets:

```bash
php artisan vendor:publish --tag=twill-geo-assets
```

Optionally, you can also publish the configuration file:

```bash
php artisan vendor:publish --tag=twill-geo-config
```

### 3. Update Twill Assets

After publishing the assets, you need to rebuild Twill's assets:

```bash
php artisan twill:build
```

### 4. Run Migrations

The package includes a migration for the `geo_structured_data` table. If you declined running migrations during the `twill:build` step, run the migrations manually:

```bash
php artisan migrate
```

## Setup

### 1. Configure env variables
Set the `TWILL_GEO_OPENAI_API_KEY` env variable. Optionally you can also set `TWILL_GEO_LLM_MODEL`.

### 2. Configure the Model

Add the `HasGeoStructuredData` trait to your Twill model:

```php
use futuraddb\TwillGeo\Repositories\Behaviors\HasGeoStructuredData;
use A17\Twill\Models\Model;

class Project extends Model
{
    use HasGeoStructuredData;

    // Optionally override the field name used in Twill's form
    public static function getTwillModelFieldNameForGeoStructuredData(): string
    {
        return 'custom_geo_field_name';
    }
}
```

### 3. Configure the Repository

Add the `HandleGeoStructuredData` trait to your Twill repository:

```php
use futuraddb\TwillGeo\Repositories\Behaviors\HandleGeoStructuredData;

class ProjectRepository extends ModuleRepository
{
    use HandleGeoStructuredData;
}
```

### 4. Configure the Controller

Add the Geo fieldset to your Twill controller's `getForm` method:

```php
use futuraddb\TwillGeo\Helpers\GeoFormFieldset;

public function getForm(TwillModelContract $model): Form
{
    return parent::getForm($model)
        // ... other fieldsets
        ->addFieldset(GeoFormFieldset::getFieldset(translated: true));
}
```

The `getFieldset` method accepts two optional arguments:
- `bool $translated` (default: `false`): Whether the field should be translatable.
- `string $fieldLabel` (default: `'GEO'`): The label for the fieldset.

## Usage

### Displaying Structured Data in Frontend

To output the structured data in your layout, use the provided Blade component:

```blade
<x-twill-geo::snippet :item="$item ?? null" />
```

This component will automatically retrieve the geo-structured data from the given item and output it as a `<script type="application/ld+json">` tag.
