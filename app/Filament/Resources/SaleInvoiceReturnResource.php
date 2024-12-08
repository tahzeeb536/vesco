<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleInvoiceReturnResource\Pages;
use App\Filament\Resources\SaleInvoiceReturnResource\RelationManagers;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceReturn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use App\Models\ProductVariant;

class SaleInvoiceReturnResource extends Resource
{
    protected static ?string $model = SaleInvoiceReturn::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sale_invoice_id')
                    // ->relationship('sale_invoice', 'invoice_number')
                    ->label('Sale invoice number')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->getSearchResultsUsing(function (string $query) {
                        return SaleInvoice::where('invoice_number', 'like', "%{$query}%")
                            ->limit(50)
                            ->pluck('invoice_number', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return SaleInvoice::find($value)?->invoice_number ?? '';
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $purchaseOrder = SaleInvoice::with('items.variant')->find($state);
                            $items = $purchaseOrder->items->map(function ($item) {
                                return [
                                    'variant_id' => $item->variant_id,
                                    'quantity' => $item->quantity, 
                                    'unit_price' => $item->unit_price ?? 0,
                                    'total_price' => round(($item->unit_price ?? 0) * $item->quantity, 2),
                                ];
                            })->toArray();

                            $set('items', $items);
                        }
                    }),

                Forms\Components\DatePicker::make('return_date')
                    ->required()
                    ->default(now()),

                Forms\Components\Textarea::make('reason')
                    ->label('Reason')
                    ->nullable(),
                
                Forms\Components\Grid::make(1)->schema([
                    TableRepeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Forms\Components\Select::make('variant_id')
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
                                        $set('unit_price', $variant->vendor_price);
                                    }
                                }),
                            
                            Forms\Components\Hidden::make('ordered_quantity')
                                ->default(0),
                            
                            Forms\Components\TextInput::make('quantity')
                                ->label('Qty')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->reactive()
                                ->debounce(1000)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $set('total_price', round($state * $unitPrice, 2));
                                }),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->numeric()
                                ->reactive()
                                ->default(0)
                                ->required()
                                ->debounce(700)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $quantity = $get('quantity') ?? 1;
                                    $set('total_price', round($state * $quantity, 2));
                                }),

                            Forms\Components\TextInput::make('total_price')
                                ->label('Total Price')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->reactive()
                                ->readOnly(),
                        ])
                        ->reorderable()
                        ->minItems(1)
                        ->colStyles(function(){
                            return [
                                'variant_id' => 'width: 55%;',
                                'quantity' => 'width: 15%',
                                'unit_price' => 'width: 15%',
                                'total_price' => 'width: 15%',
                            ];
                        }),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sale_invoice.customer.full_name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sale_invoice_return_number')
                    ->label('Invoice Number')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('return_date')
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
            'index' => Pages\ListSaleInvoiceReturns::route('/'),
            'create' => Pages\CreateSaleInvoiceReturn::route('/create'),
            'view' => Pages\ViewSaleInvoiceReturn::route('/{record}'),
            'edit' => Pages\EditSaleInvoiceReturn::route('/{record}/edit'),
        ];
    }
}
