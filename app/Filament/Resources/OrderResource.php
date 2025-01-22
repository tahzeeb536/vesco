<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Customer Orders';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([

                Forms\Components\DatePicker::make('order_date')
                    ->required()
                    ->label('Order Date')
                    ->columnSpan(4)
                    ->default(fn () => now()->toDateString()),
                
                Forms\Components\DatePicker::make('email_date')
                    ->required()
                    ->label('Email Date')
                    ->columnSpan(4)
                    ->default(fn () => now()->toDateString()),
                
                Forms\Components\DatePicker::make('delivery_date')
                    ->required()
                    ->label('Delivery Date')
                    ->columnSpan(4)
                    ->default(fn () => now()->toDateString()),   

                Forms\Components\TextInput::make('order_name')->required()->label('Order Name / Reference')->columnSpan(12),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'full_name')
                    ->required()
                    ->searchable()
                    ->options(function () {
                        return \App\Models\Customer::query()
                            ->limit(50)
                            ->pluck('full_name', 'id');
                    })->columnSpan(12),
                
                Forms\Components\TextInput::make('invoice_number')->nullable()->columnSpan(4),
                Forms\Components\Select::make('status')
                    ->options([
                        'preparing' => 'Preparing',
                        'shippded' => 'Shippded',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])->required()->columnSpan(4),
                
                Forms\Components\Select::make('currency')
                    ->required()
                    ->searchable()
                    ->options([
                        '' => 'Select',
                        'USD' => 'USD - US Dollar',
                        'EUR' => 'EUR - Euro',
                        'GBP' => 'GBP - British Pound',
                        'AUD' => 'AUD - Australian Dollar',
                        'CAD' => 'CAD - Canadian Dollar',
                        'JPY' => 'JPY - Japanese Yen',
                        'RUB' => 'RUB - Russian Ruble',
                        'TRY' => 'TRY - Turkish Lira',
                        'CHF' => 'CHF - Swiss Franc',
                        'CNY' => 'CNY - Chinese Yuan',
                        'SEK' => 'SEK - Swedish Krona',
                        'NZD' => 'NZD - New Zealand Dollar',
                        'MXN' => 'MXN - Mexican Peso',
                        'SGD' => 'SGD - Singapore Dollar',
                        'HKD' => 'HKD - Hong Kong Dollar',
                        'NOK' => 'NOK - Norwegian Krone',
                        'PKR' => 'PKR - Pakistani Rupee',
                    ])
                    ->columnSpan(4),

                Forms\Components\TextInput::make('order_amount')
                    ->numeric()
                    ->required()
                    ->columnSpan(2)
                    ->reactive()
                    ->debounce(800)
                    ->afterStateUpdated(function (callable $set, $state, $get) {
                        $grandTotal = $get('order_amount') - $get('damage_amount');
                        $set('grand_total', $grandTotal);
                        $set('balance', $grandTotal - $get('paid_amount'));
                    }),
                
                Forms\Components\TextInput::make('damage_amount')
                    ->numeric()
                    ->required()
                    ->columnSpan(2)
                    ->default(0)
                    ->reactive()
                    ->debounce(800)
                    ->afterStateUpdated(function (callable $set, $state, $get) {
                        $grandTotal = $get('order_amount') - $get('damage_amount');
                        $set('grand_total', $grandTotal);
                        $set('balance', $grandTotal - $get('paid_amount'));
                    }),
                
                Forms\Components\TextInput::make('grand_total')
                    ->numeric()
                    ->required()
                    ->columnSpan(3)
                    ->readonly()
                    ->reactive(),
                
                Forms\Components\TextInput::make('paid_amount')
                    ->numeric()
                    ->columnSpan(3)
                    ->default(0)
                    ->reactive()
                    ->debounce(800)
                    ->afterStateUpdated(function (callable $set, $state, $get) {
                        $grandTotal = $get('order_amount') - $get('damage_amount');
                        $set('grand_total', $grandTotal);
                        $set('balance', $grandTotal - $get('paid_amount'));
                    }),
                
                Forms\Components\TextInput::make('balance')
                    ->numeric()
                    ->columnSpan(2)
                    ->readonly()
                    ->reactive(),
                

                Forms\Components\FileUpload::make('order_file_admin')
                    ->disk('public')
                    ->directory('order-files')
                    ->nullable()
                    ->columnSpan(6),

                Forms\Components\FileUpload::make('order_file_manager')
                    ->disk('public')
                    ->directory('order-files')
                    ->nullable()
                    ->columnSpan(6),
                
                Forms\Components\TextInput::make('total_boxes')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Dynamically adjust the boxes_details based on total_boxes
                        $set('boxes_details', array_fill(0, (int) $state, ['box_number' => '', 'weight' => '', 'dimensions' => '']));
                    })
                    ->label('Total Boxes')
                    ->columnSpan(2),
                
                Forms\Components\TextInput::make('shipping_carrier')->nullable()->columnSpan(4),
                Forms\Components\TextInput::make('tracking_number')->nullable()->columnSpan(3),
                
                Forms\Components\TextInput::make('airway_bill_number')
                    ->nullable()
                    ->columnSpan(3)
                    ->label('Airway Bill Number'),

                Forms\Components\Repeater::make('boxes_details')
                    ->schema([
                        Forms\Components\TextInput::make('box_number')
                            ->label('Box Number')
                            ->required()
                            ->placeholder('Enter Box Number'),
                    ])
                    ->label('Box Numbers')
                    ->default([]) // Default to an empty array for new records
                    ->hidden(fn (callable $get) => !$get('total_boxes')) // Hide if `total_boxes` is not set
                    ->afterStateHydrated(function (callable $set, $state) {
                        // If the state already has data, set it into the form
                        if ($state) {
                            $set('boxes_details', $state);
                        }
                    })
                    ->dehydrateStateUsing(fn ($state) => collect($state)->map(fn ($box) => ['box_number' => $box['box_number']])->toArray())
                    ->columns(3)
                    ->columnSpan(12),
                
                Forms\Components\Repeater::make('payments')
                    ->relationship('payments')
                    ->schema([
                        Forms\Components\DatePicker::make('payment_date')->required(),
                        Forms\Components\TextInput::make('amount')->numeric()->required(),
                        Forms\Components\TextInput::make('ref')->label('Reference'),
                        Forms\Components\Hidden::make('added_by')
                            ->default(fn () => auth()->id()),
                    ])
                    ->label('Payments')
                    ->columns(3)
                    ->columnSpan(12),
                
        ])
        ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_date')->sortable(),
                Tables\Columns\TextColumn::make('email_date')->sortable(),
                Tables\Columns\TextColumn::make('delivery_date')->sortable(),
                Tables\Columns\TextColumn::make('order_name')->searchable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer'),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\TextColumn::make('grand_total')->sortable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
