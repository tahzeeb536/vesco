<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GrnResource\Pages;
use App\Filament\Resources\GrnResource\RelationManagers;
use App\Models\Grn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;

class GrnResource extends Resource
{
    protected static ?string $model = Grn::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Stock Management';
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Products Received (PR)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('purchase_order_id')
                    ->label('Purchase Order Number')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        return PurchaseOrder::with('vendor')
                            ->get()
                            ->mapWithKeys(function ($po) {
                                return [$po->id => "{$po->purchase_order_number} ( {$po->vendor?->first_name} {$po->vendor?->first_name} )"];
                            })
                            ->toArray();
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        return PurchaseOrder::with('vendor')
                            ->where('purchase_order_number', 'like', "%{$search}%")
                            ->orWhereHas('vendor', function ($query) use ($search) {
                                $query->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($po) {
                                return [$po->id => "{$po->purchase_order_number} ( {$po->vendor?->first_name} {$po->vendor?->last_name} )"];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $po = PurchaseOrder::with('vendor')->find($value);
                        return $po ? "{$po->purchase_order_number} - {$po->vendor?->name}" : '';
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $purchaseOrder = PurchaseOrder::with('items.variant')->find($state);
                            $items = $purchaseOrder->items->map(function ($item) {
                                return [
                                    'variant_id' => $item->variant_id,
                                    'shelf_id' => $item->shelf_id,
                                    'ordered_quantity' => $item->quantity,
                                    'received_quantity' => $item->quantity,
                                    'unit_price' => $item->unit_price ?? 0,
                                    'total_price' => ($item->unit_price ?? 0) * $item->quantity,
                                ];
                            })->toArray();

                            $set('items', $items);
                        }
                    }),

                Forms\Components\DatePicker::make('received_date')
                    ->label('Received Date')
                    ->default(now())
                    ->required(),

                Forms\Components\Textarea::make('note')
                    ->label('Note')
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
                            
                            Forms\Components\Select::make('room_id')
                                ->label('Room')
                                ->options(\App\Models\Room::pluck('name', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('rack_id', null);
                                    $set('shelf_id', null);
                                })
                                ->required()
                                ->afterStateHydrated(function (callable $set, callable $get) {
                                    $shelfId = $get('shelf_id');
                                    if (!$shelfId) return;

                                    $shelf = \App\Models\Shelf::with('rack.room')->find($shelfId);
                                    if ($shelf?->rack?->room) {
                                        $set('room_id', $shelf->rack->room->id);
                                    }
                                }),

                            Forms\Components\Select::make('rack_id')
                                ->label('Rack')
                                ->options(function (callable $get) {
                                    $roomId = $get('room_id');
                                    if (!$roomId) return [];
                                    return \App\Models\Rack::where('room_id', $roomId)->pluck('name', 'id');
                                })
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('shelf_id', null);
                                })
                                ->required()
                                ->afterStateHydrated(function (callable $set, callable $get) {
                                    $shelfId = $get('shelf_id');
                                    if (!$shelfId) return;

                                    $shelf = \App\Models\Shelf::with('rack')->find($shelfId);
                                    if ($shelf?->rack) {
                                        $set('rack_id', $shelf->rack->id);
                                    }
                                }),


                            Forms\Components\Select::make('shelf_id')
                                ->label('Shelf')
                                ->options(function (callable $get) {
                                    $rackId = $get('rack_id');
                                    if (!$rackId) return [];
                                    return \App\Models\Shelf::where('rack_id', $rackId)->pluck('name', 'id');
                                })
                                ->required(),


                            Forms\Components\Hidden::make('ordered_quantity')
                                ->default(0)
                                ->debounce(500),
                            
                            Forms\Components\TextInput::make('received_quantity')
                                ->label('Qty')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->reactive()
                                ->debounce(500)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $set('total_price', $state * $unitPrice);
                                }),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->numeric()
                                ->reactive()
                                ->default(0)
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $quantity = $get('received_quantity') ?? 1;
                                    $set('total_price', $state * $quantity);
                                }),

                            Forms\Components\TextInput::make('total_price')
                                ->label('Total Price')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->reactive()
                                ->readOnly(),

                            Forms\Components\TextInput::make('remarks')
                                ->label('Remarks')
                                ->nullable(),
                        ])
                        // ->cloneable()
                        ->minItems(1)
                        ->colStyles(function(){
                            return [
                                'variant_id' => 'width: 25%;',
                                // 'shelf_id' => 'width: 25%',
                                // 'received_quantity' => 'width: 10%',
                                // 'unit_price' => 'width: 10%',
                                // 'total_price' => 'width: 10%',
                            ];
                        }),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('grn_number')
                    ->label('PR Number')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('purchase_order.purchase_order_number')
                    ->label('PO Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('purchase_order.vendor.full_name')
                    ->label('Vendor')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('received_date')
                    ->label('Received Date')
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
            'index' => Pages\ListGrns::route('/'),
            'create' => Pages\CreateGrn::route('/create'),
            'view' => Pages\ViewGrn::route('/{record}'),
            'edit' => Pages\EditGrn::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Products Received';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Products Received';
    }

    
}
