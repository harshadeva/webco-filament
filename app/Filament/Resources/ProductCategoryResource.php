<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'primary' : 'info';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('description')
                    ->nullable()
                    ->rows(4)
                    ->maxLength(65535),
                TextInput::make('external_url')
                    ->nullable()
                    ->url()
                    ->maxLength(255)
                    ->label('External URL'),
                Select::make('product_type_ids')
                    ->label('Product Types')
                    ->required()
                    ->multiple()
                    ->relationship('productTypes', 'name')
                    ->preload()
                    ->searchable()
                    ->native(false),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->weight('bold'),
                TextColumn::make('products_count')
                    ->label('Products Count')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn($state) => $state == 0 ? 'gray' : 'primary')
                    ->counts('products')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('description')
                    ->placeholder('No description.')
                    ->searchable()
                    ->limit(50)
                    ->toggleable()
                    ->tooltip(fn($state) => $state)
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->color('primary'),
                    ViewAction::make()->color('primary'),
                    DeleteAction::make()->disabled(
                        fn(ProductCategory $record) =>
                        $record->products()->exists()
                    )
                        ->color(
                            fn(ProductCategory $record) =>
                            $record->products()->exists()
                                ? 'gray'
                                : 'danger'
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()->action(function (Collection $records) {
                        $nonDeletables = $records->filter(function ($record) {
                            return $record->products()->exists();
                        });

                        if ($nonDeletables->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Some records cannot be deleted')
                                ->body('One or more selected Product Categories are associated with products.')
                                ->danger()
                                ->persistent()
                                ->send();

                            return;
                        }

                        $records->each->delete();

                        \Filament\Notifications\Notification::make()
                            ->title('Records deleted successfully.')
                            ->success()
                            ->send();
                    }),
                ]),
            ])
            ->searchPlaceholder('Search (Name, Description, URL)')
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Category Details')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Category Name')
                            ->weight('bold'),
                        TextEntry::make('description')->placeholder('No description.'),
                        TextEntry::make('external_url')
                            ->visible(fn($record) => !empty($record->external_url))
                            ->label('External URL')
                            ->formatStateUsing(
                                fn($state) => $state
                                    ? '<a href="' . e($state) . '" target="_blank" class="text-primary-600 underline">' . e($state) . '</a>'
                                    : ''
                            )
                            ->html(),
                        TextEntry::make('productTypes.name')
                            ->label('Product Types')
                            ->listWithLineBreaks()
                            ->badge()
                            ->color('success'),
                        TextEntry::make('products_count')
                            ->label('Products Count')
                            ->formatStateUsing(fn($record) => $record->products()->count())
                            ->badge(),
                    ])
                    ->collapsible()
                    ->columns(1),
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
