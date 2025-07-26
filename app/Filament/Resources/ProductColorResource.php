<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductColor;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\ColorPicker;
use App\Filament\Resources\ProductColorResource\Pages;
use Illuminate\Database\Eloquent\Collection;

class ProductColorResource extends Resource
{
    protected static ?string $model = ProductColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255),
                        ColorPicker::make('color_code')->required()->rgba()
                            ->label('Color Code'),
                    ])->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),


                TextColumn::make('color_code')
                    ->label('Color Code')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('products_count')
                    ->label('Products Count')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn($state) => $state == 0 ? 'gray' : 'primary')
                    ->counts('products')
                    ->toggleable()
                    ->sortable(),

                ColorColumn::make('color_code_color')
                    ->label('Color')
                    ->toggleable()
                    ->alignment(Alignment::Center)
                    ->getStateUsing(fn(ProductColor $record): string => $record->color_code)
                    ->tooltip(fn(ProductColor $record): string => "Created At :  {$record->created_at->format('F j, Y, g:i a')}"),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->color('primary'),
                    DeleteAction::make()->disabled(
                        fn(ProductColor $record) =>
                        $record->products()->exists()
                    )
                        ->color(
                            fn(ProductColor $record) =>
                            $record->products()->exists()
                                ? 'gray'
                                : 'danger'
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->action(function (Collection $records) {
                        $nonDeletables = $records->filter(function ($record) {
                            return $record->products()->exists();
                        });

                        if ($nonDeletables->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Some records cannot be deleted')
                                ->body('One or more selected Product Colors are associated with products.')
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
            ])->searchPlaceholder('Search (Name)')
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
            'index' => Pages\ListProductColors::route('/'),
            'create' => Pages\CreateProductColor::route('/create'),
            'edit' => Pages\EditProductColor::route('/{record}/edit'),
        ];
    }
}
