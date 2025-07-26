<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductType;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Services\VocusApiService;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ProductTypeResource\Pages;

class ProductTypeResource extends Resource
{
    protected static ?string $model = ProductType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?int $navigationSort = 4;

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
                TextInput::make('unique_identifier')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true)
                    ->label('API Unique Identifier')
                    ->maxLength(255)
                    ->suffixAction(
                        Action::make('fetchApiNumber')
                            ->icon('heroicon-o-arrow-path')
                            ->action(function ($livewire, $state) {
                                $uniqueId = (new VocusApiService())->fetchApiUniqueNumber();

                                if (in_array($uniqueId, ['AUTH_FAIL', 'TIMEOUT_ERROR', 'API_FAIL', 'NO_ID'])) {
                                    Notification::make()
                                        ->title('Failed to fetch API number')
                                        ->body('There was a problem contacting the external API. Please try again later.')
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                    return;
                                }

                                $livewire->form->fill([
                                    'unique_identifier' => $uniqueId,
                                ]);
                            })
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->toggleable(),
                TextColumn::make('products_count')
                    ->label('Products Count')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn($state) => $state == 0 ? 'gray' : 'primary')
                    ->counts('products')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('categories_count')
                    ->label('Categories Count')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn($state) => $state == 0 ? 'gray' : 'primary')
                    ->counts('categories')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('unique_identifier')->label('API Unique Key')->copyable()
                    ->copyMessage('API Unique Key copied to clipboard')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->color('primary'),
                    ViewAction::make()->color('primary'),
                    DeleteAction::make()
                        ->disabled(
                            fn(ProductType $record) =>
                            $record->products()->exists() ||
                                $record->categories()->exists()
                        )
                        ->color(
                            fn(ProductType $record) =>
                            $record->products()->exists() ||
                                $record->categories()->exists()
                                ? 'gray'
                                : 'danger'
                        )
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->action(function (Collection $records) {
                        $nonDeletables = $records->filter(function ($record) {
                            return $record->products()->exists()
                                || $record->categories()->exists();
                        });

                        if ($nonDeletables->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Some records cannot be deleted')
                                ->body('One or more selected Product Types are associated with products or categories.')
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
            ])->searchPlaceholder('Search (Name, Unique Key)')
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Product Type Details')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Type Name')
                            ->weight('bold'),
                        TextEntry::make('unique_identifier')
                            ->label('API Unique Identifier')
                            ->copyable()
                            ->copyMessage('API Unique Key copied to clipboard'),
                        TextEntry::make('products_count')
                            ->label('Products Count')
                            ->formatStateUsing(fn($record) => $record->products()->count())
                            ->badge()
                            ->color(fn($state) => $state == 0 ? 'gray' : 'primary'),
                        TextEntry::make('categories_count')
                            ->label('Categories Count')
                            ->formatStateUsing(fn($record) => $record->categories()->count())
                            ->badge()
                            ->color(fn($state) => $state == 0 ? 'gray' : 'primary'),
                    ])
                    ->collapsible()
                    ->columns(2),
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
            'index' => Pages\ListProductTypes::route('/'),
            'create' => Pages\CreateProductType::route('/create'),
            'edit' => Pages\EditProductType::route('/{record}/edit'),
        ];
    }
}
