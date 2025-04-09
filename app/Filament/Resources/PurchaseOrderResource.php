<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Log;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Stock Management';
    protected static ?int $navigationSort = 4;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vendor_id')
                    ->label('Vendor')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Vendor::query()
                            ->where('full_name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('full_name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return \App\Models\Vendor::find($value)?->full_name ?? '';
                    }),

                Forms\Components\DatePicker::make('order_date')
                    ->required()
                    ->default(now()),

                Forms\Components\DatePicker::make('delivery_date'),

                Forms\Components\Textarea::make('note')
                    ->nullable(),

                Forms\Components\Hidden::make('order_items')
                    ->required()
                    ->reactive()
                    ->dehydrated(true), 

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\View::make('components.po-custom-repeater')
                            ->label('Items')
                            ->columnSpan('full')
                    ])
                    ->hiddenOn('view'),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\View::make('components.po-view-custom-repeater')
                            ->label('Items')
                            ->columnSpan('full')
                    ])
                    ->hiddenOn(['create', 'edit']),

            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('purchase_order_number')
                    ->label('Order Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vendor.full_name')
                    ->label('Vendor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->date(),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'view' => Pages\ViewPurchaseOrder::route('/{record}'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }

}
