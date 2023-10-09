<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $modelLabel = 'Kategoria produktów';
    protected static ?string $pluralModelLabel = 'Kategorie produktów';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Zarządzanie produktami';

    public static function rules(): array
    {
        return (new ProductCategoryRequest())->rules();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label(__('Nazwa kategorii'))
                    ->autofocus()
                    ->required()
                    ->rules(self::rules()['name']),
                Toggle::make('is_subcategory')
                    ->label(__('Podkategoria'))
                    ->formatStateUsing(fn (Get $get) => $get('parent_id') !== null)
                    ->live(),
                Select::make('parent_id')
                    ->label(__('Kategoria nadrzędna'))
                    ->placeholder(__('Wybierz kategorię nadrzędną'))
                    ->hidden(fn (Get $get) => $get('is_subcategory') === false)
                    ->required(fn (Get $get) => $get('is_subcategory') === true)
                    ->rules(self::rules()['parent_id'])
                    ->options(
                        ProductCategory::query()
                            ->withoutGlobalScope(SoftDeletingScope::class)
                            ->whereNull('parent_id')
                            ->get()
                            ->mapWithKeys(function (ProductCategory $productCategory) {
                                return [$productCategory->getKey() => $productCategory->name];
                            })
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nazwa kategorii'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('parent_id')
                    ->label(__('Podkategoria'))
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check-circle' : '')
                    ->color('success'),
                TextColumn::make('parent.name')
                    ->label(__('Kategoria nadrzędna'))
                    ->searchable()
                    ->sortable()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductCategories::route('/'),
        ];
    }    
}
