<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourierReceiptResource\Pages;
use App\Filament\Resources\CourierReceiptResource\RelationManagers;
use App\Models\CourierReceipt;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourierReceiptResource extends Resource
{
    protected static ?string $model = CourierReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Courier Receipts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('date')
                    ->required()
                    ->type('date')
                    ->columnSpan(6),
                Forms\Components\TextInput::make('airway_bill_number')
                    ->label('Airway Bill #')
                    ->columnSpan(6),
                Forms\Components\TextInput::make('destination_code')
                    ->default('LHR')
                    ->columnSpan(6),
                Forms\Components\TextInput::make('origin_code')
                    ->columnSpan(6),

                // Shipper Information
                Forms\Components\Section::make('Shipper Information')
                    ->schema([
                        Forms\Components\TextInput::make('shipper_account_number')
                            ->label('Account Number')
                            ->default('VES VEACUUM INT.')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_credit_card')
                            ->label('Credit Card / Cheque')
                            ->default('ACCOUNT SCB')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_name')
                            ->label('Shipper Name / Company')
                            ->default('VES VACUUM INTERNATIONAL')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_address')
                            ->label('Address')
                            ->default('NEW ABADI SOHAWA CIRCULAR ROAD')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_city')
                            ->label('City')
                            ->default('Daska, Sialkot')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_zip')
                            ->label('Zip')
                            ->default('51010')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_country')
                            ->label('Country')
                            ->default('Pakistan')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_phone')
                            ->label('Phone')
                            ->default('+923006464270')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('shipper_department')
                            ->label('Shipping')
                            ->default('SHIPPING')
                            ->columnSpan(6),
                    ])
                    ->columns(12),

                // Receiver Information
                Forms\Components\Section::make('Receiver Information')
                    ->schema([
                        Forms\Components\Select::make('receiver_company_name')
                            ->label('Company Name')
                            ->searchable()
                            ->options(function (string $search = null) {
                                $query = Customer::query();
                                $query->whereNotNull('organization')
                                    ->where('organization', '!=', '');
                                if ($search) {
                                    $query->where('organization', 'like', "%{$search}%");
                                }
                                return $query->limit(50)
                                    ->pluck('organization', 'id')
                                    ->toArray();
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $customer = Customer::find($state);
                                    if ($customer) {
                                        $set('receiver_company_name', $customer->organization);
                                        $set('receiver_attention_to', $customer->full_name);
                                        $set('receiver_address', $customer->address);
                                        $set('receiver_city', $customer->city);
                                        $set('receiver_state', $customer->state);
                                        $set('receiver_country', $customer->country);
                                        $set('receiver_zip', $customer->post_code);
                                        $set('receiver_phone', $customer->phone);
                                    }
                                }
                            })
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_attention_to')
                            ->label('Attention To')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_address')
                            ->label('Address')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_city')
                            ->label('City')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_state')
                            ->label('State') 
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_country')
                            ->label('Country')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_zip')
                            ->label('Zip')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('receiver_phone')
                            ->label('Phone')
                            ->columnSpan(6),
                            Forms\Components\Textarea::make('extra_information')
                                ->columnSpan(12),
                    ])
                    ->columns(12),

                // Other Information
                Forms\Components\Section::make('Other Information')
                    ->schema([
                        Forms\Components\TextInput::make('items')
                            ->label('Items')
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('kilos')
                            ->label('Kilos')
                            ->columnSpan(4),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options(function () {
                                return [
                                    'Package' => 'Package',
                                    'Docuemnts' => 'Docuemnts'
                                ];
                            })
                            ->columnSpan(4),
                    ])
                    ->columns(12),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->query(
            //     CourierReceipt::query()
            //     ->select('courier_receipts.*', 'customers.organization as receiver_company_name')
            //     ->join('customers', 'customers.id', '=', 'courier_receipts.receiver_company_name')
            // )
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')->sortable(),
                Tables\Columns\TextColumn::make('receiver_company_name')
                    ->label('Receiver Company'),
                Tables\Columns\TextColumn::make('airway_bill_number')
                    ->label('Airway Bill #'),
                Tables\Columns\TextColumn::make('destination_code')
                    ->label('Destination Code'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('print_courier_receipt')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->url(fn (CourierReceipt $record) => route('print_courier_receipt', [
                        'id' => $record->id
                    ]))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListCourierReceipts::route('/'),
            'create' => Pages\CreateCourierReceipt::route('/create'),
            'view' => Pages\ViewCourierReceipt::route('/{record}'),
            'edit' => Pages\EditCourierReceipt::route('/{record}/edit'),
        ];
    }
}
