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
                Forms\Components\DatePicker::make('invoice_date')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('invoice_number')
                    ->readOnly(),

                Forms\Components\TextInput::make('ntn')
                    ->default('2130732-6')
                    ->label('NTN No'),

                Forms\Components\TextInput::make('financial_instrument_no')
                    ->label('Financial Instrument No'),

                Forms\Components\TextInput::make('bank_name')
                    ->label('Bank Name')
                    ->required()
                    ->default('HBL'),

                Forms\Components\Select::make('shipping')
                    ->options([
                        'By Air' => 'By Air',
                        'By Sea' => 'By Sea',
                    ])
                    ->default('By Air')
                    ->required(),

                Forms\Components\TextInput::make('port_of_loading')
                    ->label('Port of Landing'),

                Forms\Components\TextInput::make('port_of_discharge')
                    ->label('Port of Discharge'),

                Forms\Components\Select::make('customer_id')
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
                
                Forms\Components\TextInput::make('term')
                    ->default('Advance'),
                
                Forms\Components\TextInput::make('hs_code')
                    ->label('HS Code')
                    ->default('9022-2100'),
                
                Forms\Components\TextInput::make('po_no')
                    ->label('P.O. No'),
                
                Forms\Components\TextInput::make('frieght_charges')
                    ->label('Frieght Charges')
                    ->numeric(),
                
                Forms\Components\TextInput::make('tax_charges')
                    ->label('Tax Charges')
                    ->numeric(),

                // Forms\Components\Textarea::make('note')
                //     ->label('Note')
                //     ->nullable(),

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
