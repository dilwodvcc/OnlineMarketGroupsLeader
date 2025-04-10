<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use \MoonShine\UI\Fields\Text;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<Category>
 */
class CategoryResource extends ModelResource
{
    protected string $model = Category::class;

    protected string $title = 'Categories';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('name'),
            Text::make('parent')->nullable(),
            HasMany::make('Images','images')
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('name'),
                BelongsTo::make('parent', 'parent', fn($item)=>"$item->id. $item->name",CategoryResource::class)->nullable(),

            ]),
            HasMany::make('Images','images')

        ];
    }
    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('name'),
            Text::make('parent_id')->nullable(),
            BelongsTo::make('parent', 'parent', fn($item)=>"$item->id. $item->name",CategoryResource::class)->nullable(),
            HasMany::make('Products', 'products', fn($item)=>"$item->id. $item->name",
                ProductResource::class),
            HasMany::make('Images','images')

        ];
    }

    /**
     * @param Category $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{P}\p{Zs}]+$/u'],
        ];
    }
}
