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
use Filament\Tables\Actions\ActionGroup;
use Filament\Actions;
use Filament\Tables\Actions\Action as PopupAction;

class SaleInvoiceResource extends Resource
{
    protected static ?string $model = SaleInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationGroup = 'Stock Management';
    protected static ?int $navigationSort = 10;

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
                    ->label('Port of Loading'),

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
                
                Forms\Components\TextInput::make('freight_charges')
                    ->label('Freight Charges')
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
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice Number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_date')
                    ->label('Invoice Date')
                    ->searchable()
                    ->sortable()
                    ->date(),

                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->getStateUsing(function ($record) {
                        $currency = $record->customer->currency ?? 'USD';
                        return number_format($record->total_amount, 2) . ' ' . strtoupper($currency);
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Paid Amount')
                    ->getStateUsing(function ($record) {
                        $currency = $record->customer->currency ?? 'USD';
                        return number_format($record->total_amount, 2) . ' ' . strtoupper($currency);
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('pending_amount')
                    ->label('Pending Amount')
                    ->getStateUsing(function ($record) {
                        $currency = $record->customer->currency ?? 'USD';
                        return number_format($record->total_amount, 2) . ' ' . strtoupper($currency);
                    })
                    ->sortable(),

                // Tables\Columns\TextColumn::make('status')
                //     ->sortable()
                //     ->formatStateUsing(function ($state) {
                //         return ucwords(str_replace('_', ' ', $state));
                //     })
                //     ->badge()
                //     ->colors([
                //         'success' => 'paid',         
                //         'danger' => 'not_paid',      
                //         'warning' => 'partially_paid',
                //     ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Actions\Action::make('print_sale_invoice')
                        ->label('Print Invoice')
                        ->url(fn ($record) => self::print_sale_invoice($record->id))
                        ->openUrlInNewTab(),
                    Actions\Action::make('print_sale_invoice_with_stamp')
                        ->label('Invoice with Stamp')
                        ->url(fn ($record) => self::print_sale_invoice_with_stamp($record->id))
                        ->openUrlInNewTab(),
                    PopupAction::make('pay_invoice')
                        ->label('Pay Invoice')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-banknotes')
                        ->action(function (SaleInvoice $record, array $data) {
                            $paidAmount = $data['paid_amount'];
                            $totalAmount = $record->total_amount;

                            if ($paidAmount == $totalAmount) {
                                $status = 'paid';
                            } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
                                $status = 'partially_paid';
                            } else {
                                $status = 'pending';
                            }

                            $record->update([
                                'paid_amount' => $data['paid_amount'],
                                'pending_amount' => $record->total_amount - $data['paid_amount'],
                                'status' => $status, 
                            ]);
                        })
                        ->modalHeading('Pay Invoice')
                        ->form([
                            Forms\Components\TextInput::make('total_amount')
                                ->label('Total Amount')
                                ->numeric()
                                ->readonly(),
                            Forms\Components\TextInput::make('paid_amount')
                                ->label('Paid Amount')
                                ->required()
                                ->numeric()
                        ])
                        ->mountUsing(function (Forms\ComponentContainer $form, SaleInvoice $record) {
                            $form->fill([
                                'total_amount'   => $record->total_amount,
                                'paid_amount'    => $record->paid_amount,
                                'pending_amount' => $record->total_amount - $record->paid_amount,
                            ]);
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
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

    protected static function print_sale_invoice($recordId)
    {
        return route('print_sale_invoice', ['record' => $recordId]);
    }

    protected static function print_sale_invoice_with_stamp($recordId)
    {
        return route('print_sale_invoice_with_stamp', ['record' => $recordId]);
    }
}
