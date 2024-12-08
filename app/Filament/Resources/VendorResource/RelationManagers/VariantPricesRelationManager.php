<?php

namespace App\Filament\Resources\VendorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\ProductVariant;
use Guava\FilamentModalRelationManagers\Concerns\CanBeEmbeddedInModals;
use App\Models\Category;

class VariantPricesRelationManager extends RelationManager
{
    use CanBeEmbeddedInModals;
    
    protected static string $relationship = 'variant_prices';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->label('Price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('variant.name')
                    ->label('Product Variant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variant.product.category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
