<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleInvoiceResource\Pages;
use App\Filament\Resources\SaleInvoiceResource\RelationManagers;
use App\Models\SaleInvoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use App\Models\ProductVariant;
use App\Models\Customer;

class SaleInvoiceResource extends Resource
{
    protected static ?string $model = SaleInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    //->relationship('customer', 'full_name')
                    ->getSearchResultsUsing(function (string $query) {
                        return Customer::where('full_name', 'like', "%{$query}%")
                            ->limit(50)
                            ->pluck('full_name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return Customer::find($value)?->full_name ?? '';
                    })
                    ->label('Customer')
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('invoice_date')
                    ->required()
                    ->default(now()),

                Forms\Components\Textarea::make('note')
                    ->label('Note')
                    ->nullable(),

                Forms\Components\Hidden::make('order_items')
                    ->required()
                    ->reactive()
                    ->dehydrated(true),
                
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\View::make('components.si-custom-repeater')
                            ->label('Items')
                            ->columnSpan('full')
                    ])
                    ->hiddenOn('view'),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\View::make('components.si-view-custom-repeater')
                            ->label('Items')
                            ->columnSpan('full')
                    ])
                    ->hiddenOn(['create', 'edit']),

                // Forms\Components\Grid::make(1)->schema([
                //     TableRepeater::make('items')
                //         ->relationship('items')
                //         ->schema([
                //             Forms\Components\Select::make('variant_id')
                //                 ->label('Product Variant')
                //                 ->required()
                //                 ->searchable()
                //                 ->reactive()
                //                 ->debounce(500)
                //                 ->getSearchResultsUsing(function (string $query) {
                //                     return ProductVariant::where('name', 'like', "%{$query}%")
                //                         ->limit(50)
                //                         ->pluck('name', 'id');
                //                 })
                //                 ->getOptionLabelUsing(function ($value) {
                //                     return ProductVariant::find($value)?->name ?? '';
                //                 })
                //                 ->afterStateUpdated(function ($state, callable $set) {
                //                     $variant = \App\Models\ProductVariant::find($state);
                //                     if ($variant) {
                //                         $set('unit_price', round($variant->vendor_price, 2));
                //                     }
                //                 }),
                            
                //             Forms\Components\Hidden::make('ordered_quantity')
                //                 ->default(0),
                            
                //             Forms\Components\TextInput::make('quantity')
                //                 ->label('Qty')
                //                 ->numeric()
                //                 ->required()
                //                 ->default(0)
                //                 ->reactive()
                //                 ->afterStateUpdated(function ($state, callable $set, callable $get) {
                //                     $unitPrice = $get('unit_price') ?? 0;
                //                     $set('total_price', $state * $unitPrice);
                //                 }),

                //             Forms\Components\TextInput::make('unit_price')
                //                 ->label('Unit Price')
                //                 ->numeric()
                //                 ->reactive()
                //                 ->default(0)
                //                 ->required()
                //                 ->afterStateUpdated(function ($state, callable $set, callable $get) {
                //                     $quantity = $get('quantity') ?? 1;
                //                     $set('total_price', $state * $quantity);
                //                 }),

                //             Forms\Components\TextInput::make('total_price')
                //                 ->label('Total Price')
                //                 ->numeric()
                //                 ->default(0)
                //                 ->required()
                //                 ->reactive()
                //                 ->readOnly(),
                //         ])
                //         ->reorderable()
                //         ->minItems(1)
                //         ->colStyles(function(){
                //             return [
                //                 'variant_id' => 'width: 55%;',
                //                 'quantity' => 'width: 15%',
                //                 'unit_price' => 'width: 15%',
                //                 'total_price' => 'width: 15%',
                //             ];
                //         }),
                // ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice Number')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('invoice_date')
                    ->label('Invoice Date')
                    ->date(),
                    
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSaleInvoices::route('/'),
            'create' => Pages\CreateSaleInvoice::route('/create'),
            'edit' => Pages\EditSaleInvoice::route('/{record}/edit'),
            'view' => Pages\ViewSaleInvoice::route('/{record}/view'),
        ];
    }
}
