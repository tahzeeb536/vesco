<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GrnrResource\Pages;
use App\Filament\Resources\GrnrResource\RelationManagers;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use App\Models\Grnr;
use App\Models\Grn;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GrnrResource extends Resource
{
    protected static ?string $model = Grnr::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Stock Management';
    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Products Returned (PRT)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('grn_id')
                    ->label('PR Number')
                    // ->relationship('grn', 'grn_number')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->getSearchResultsUsing(function (string $query) {
                        return Grn::where('grn_number', 'like', "%{$query}%")
                            ->limit(50)
                            ->pluck('grn_number', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return Grn::find($value)?->grn_number ?? '';
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $purchaseOrder = Grn::with('items.variant')->find($state);
                            $items = $purchaseOrder->items->map(function ($item) {
                                return [
                                    'variant_id' => $item->variant_id,
                                    'returned_quantity' => $item->quantity, 
                                    'unit_price' => $item->unit_price ?? 0,
                                    'total_price' => ($item->unit_price ?? 0) * $item->quantity,
                                ];
                            })->toArray();

                            $set('items', $items);
                        }
                    }),
                
                Forms\Components\DatePicker::make('returned_date')
                    ->default(now())
                    ->required(),
                
                Forms\Components\Textarea::make('reason')
                    ->label('Note')
                    ->nullable(),

                Forms\Components\Grid::make(1)->schema([
                    TableRepeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Forms\Components\Select::make('variant_id')
                                ->label('Product Variant')
                                ->required()
                                ->reactive()
                                ->searchable()
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
                                    if ($state) {
                                        $variant = ProductVariant::find($state);
                                        if ($variant) {
                                            $set('unit_price', $variant->vendor_price);
                                        }
                                    } else {
                                        $set('unit_price', 0);
                                    }
                                }),
                            
                            Forms\Components\TextInput::make('returned_quantity')
                                ->label('Returned Quantity')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->reactive()
                                ->debounce(500)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $set('total_price', round($state * $unitPrice, 2));
                                }),
                            
                            Forms\Components\TextInput::make('reason')
                                ->label('Fault')
                                ->required(),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->numeric()
                                ->reactive()
                                ->default(0)
                                ->required()
                                ->debounce(500)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $quantity = $get('received_quantity') ?? 1;
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
                        // ->reorderable()
                        ->minItems(1)
                        ->colStyles(function(){
                            return [
                                'variant_id' => 'width: 40%;',
                                'returned_quantity' => 'width: 10%;',
                                'reason' => 'width: 30%;',
                                'unit_price' => 'width: 10%;',
                                'total_price' => 'width: 10%;',
                            ];
                        }),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('grnr_number')
                    ->label('PRT Number')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('grn.grn_number')
                    ->label('PR Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grn.purchase_order.purchase_order_number')
                    ->label('PO Number')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('grn.purchase_order.vendor.full_name')
                    ->label('Vendor')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('returned_date')
                    ->label('Returned Date')
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
                // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGrnrs::route('/'),
            'create' => Pages\CreateGrnr::route('/create'),
            'view' => Pages\ViewGrnr::route('/{record}'),
            'edit' => Pages\EditGrnr::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Products Returned (PRT)';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Products Returned (PRT)';
    }

}
