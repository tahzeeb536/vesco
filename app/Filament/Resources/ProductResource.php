<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    // protected static ?string $navigationGroup = 'Manage Products';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_for_vendor')
                    ->maxLength(255)
                    ->required()
                    ->default(null),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'name'),
                Forms\Components\TextInput::make('article_number')
                    ->maxLength(255)
                    ->required()
                    ->default(null),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->default(true),

                // Full-width Product Variant Repeater Field
                Forms\Components\Repeater::make('variants')
                    ->relationship('variants')
                    ->schema([
                        Forms\Components\Select::make('size_id')
                            ->label('Size')
                            ->relationship('size', 'name')
                            ->required()
                            ->columnSpan(2)
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('color_id')
                            ->label('Color')
                            ->relationship('color', 'name')
                            ->columnSpan(2)
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('vendor_price')
                            ->numeric()
                            ->required()
                            ->label('Vendor Price')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('customer_price')
                            ->numeric()
                            ->required()
                            ->label('Customer Price')
                            ->columnSpan(2),
                    ])
                    ->columns(8)
                    ->columnSpan('full')
                    ->defaultItems(1),
                ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('article_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name_for_vendor')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
