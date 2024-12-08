<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorProductPriceResource\Pages;
use App\Filament\Resources\VendorProductPriceResource\RelationManagers;
use App\Models\VendorProductPrice;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorProductPriceResource extends Resource
{
    protected static ?string $model = VendorProductPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vendor_id')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Vendor::query()
                            ->where('full_name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('full_name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return \App\Models\Vendor::find($value)?->full_name ?? '';
                    }),
                Forms\Components\Select::make('product_variant_id')
                    ->label('Product Variant')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->debounce(500)
                    ->getSearchResultsUsing(function (string $query) {
                        return ProductVariant::where('name', 'like', "%{$query}%")
                            ->limit(50)
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return ProductVariant::find($value)?->name ?? '';
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $variant = \App\Models\ProductVariant::find($state);
                        if ($variant) {
                            $set('price', round($variant->vendor_price, 2));
                        }
                    }),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.full_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('variant.name')
                    ->label('Product Variant')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
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
                
            ])
            ->actions([
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
            // 
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendorProductPrices::route('/'),
            'create' => Pages\CreateVendorProductPrice::route('/create'),
            'edit' => Pages\EditVendorProductPrice::route('/{record}/edit'),
        ];
    }
}
