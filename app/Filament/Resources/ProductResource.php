<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Basic Details')
                ->description('Fill out the basic product information.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, string $state, callable $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $slug = str($state)->slug();
                                $set('slug', $slug);
                            }),

                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ]),

                    Textarea::make('description')
                        ->required()
                        ->label('Description')
                        ->rows(3)
                        ->maxLength(65535),

                    Toggle::make('status')
                        ->default(true)
                        ->inline(false)
                        ->label('Status'),
                ])
                ->columns(1),

            Section::make('Product Additional Details')
                ->description('Assign product type, category, and color.')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('product_color_id')
                            ->label('Product Color')
                            ->relationship('productColor', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->native(false),

                        Select::make('product_type_ids')
                            ->label('Product Types')
                            ->multiple()
                            ->relationship('productTypes', 'name')
                            ->required()
                            ->preload()
                            ->reactive()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('unique_identifier')
                                    ->required()
                                    ->unique(ProductType::class, 'unique_identifier')
                                    ->maxLength(255),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Create Product Type')
                                    ->modalSubmitActionLabel('Create')
                                    ->modalWidth('lg');
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $categories = ProductCategory::whereHas('productTypes', function ($query) use ($state) {
                                    $query->whereIn('product_types.id', $state);
                                })->pluck('name', 'id')->toArray();

                                $set('product_category_id', null);
                                $set('available_categories', $categories);
                            })
                            ->afterStateHydrated(function ($state, callable $set, $record) {
                                if ($record && $record->product_category_id) {
                                    $currentCategory = ProductCategory::where('id', $record->product_category_id)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                    $set('available_categories', $currentCategory);
                                }
                            }),

                        Select::make('product_category_id')
                            ->label('Product Category')
                            ->options(fn(callable $get) => $get('available_categories') ?? [])
                            ->native(false)
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn(ProductCategory $record) => $record->name),
                    ]),
                ])
                ->columns(1),

            Hidden::make('available_categories')->default([]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Product Name')->wrap()->searchable()->sortable()->toggleable(),

                TextColumn::make('productCategory.name')->label('Category')->searchable()->sortable()->toggleable(),

                TextColumn::make('status')->badge()->color(fn(string $state): string => match ($state) {
                    '1' => 'success',
                    '0' => 'danger',
                })->formatStateUsing(fn(string $state): string => $state === '1' ? 'Active' : 'Inactive')->sortable()->toggleable(),

                ColorColumn::make('productColor.name')->label('Color')->sortable()->toggleable(),

                TextColumn::make('description')->limit(60)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),

            ])
            ->filters([
                SelectFilter::make('status')
                ->label('Status')
                ->options([
                    '1' => 'Active',
                    '0' => 'Inactive',
                ])
                ->native(false)
                ->default(null)
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->searchPlaceholder('Search (Product Name, Category)')
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
