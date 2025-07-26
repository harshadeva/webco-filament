<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTypeResource\Pages;
use App\Filament\Resources\ProductTypeResource\RelationManagers;
use App\Models\ProductType;
use App\Services\VocusApiService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class ProductTypeResource extends Resource
{
    protected static ?string $model = ProductType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

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
                TextColumn::make('unique_identifier')->label('API Unique Key')->copyable()
                    ->copyMessage('API Unique Key copied to clipboard')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->color('primary'),
                    DeleteAction::make()
                        ->disabled(
                            fn(ProductType $record) =>
                            $record->products()->exists() ||
                                $record->categories()->exists() ||
                                $record->colors()->exists()
                        )
                        ->color(
                            fn(ProductType $record) =>
                            $record->products()->exists() ||
                                $record->categories()->exists() ||
                                $record->colors()->exists()
                                ? 'gray'
                                : 'danger'
                        )
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->searchPlaceholder('Search (Name, Unique Key)')
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
            'index' => Pages\ListProductTypes::route('/'),
            'create' => Pages\CreateProductType::route('/create'),
            'edit' => Pages\EditProductType::route('/{record}/edit'),
        ];
    }
}
