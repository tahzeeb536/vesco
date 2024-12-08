<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->label('First Name'),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Last Name'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->label('Email Address'),
                    Forms\Components\TextInput::make('organization')
                        ->label('Organization'),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->label('Phone'),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->label('Address'),
                        Forms\Components\Select::make('country')
                        ->options([
                            'Afghanistan' => 'Afghanistan',
                            'Albania' => 'Albania',
                            'Algeria' => 'Algeria',
                            'Andorra' => 'Andorra',
                            'Angola' => 'Angola',
                            'Antigua and Barbuda' => 'Antigua and Barbuda',
                            'Argentina' => 'Argentina',
                            'Armenia' => 'Armenia',
                            'Australia' => 'Australia',
                            'Austria' => 'Austria',
                            'Azerbaijan' => 'Azerbaijan',
                            'Bahamas' => 'Bahamas',
                            'Bahrain' => 'Bahrain',
                            'Bangladesh' => 'Bangladesh',
                            'Barbados' => 'Barbados',
                            'Belarus' => 'Belarus',
                            'Belgium' => 'Belgium',
                            'Belize' => 'Belize',
                            'Benin' => 'Benin',
                            'Bhutan' => 'Bhutan',
                            'Bolivia' => 'Bolivia',
                            'Bosnia and Herzegovina' => 'Bosnia and Herzegovina',
                            'Botswana' => 'Botswana',
                            'Brazil' => 'Brazil',
                            'Brunei' => 'Brunei',
                            'Bulgaria' => 'Bulgaria',
                            'Burkina Faso' => 'Burkina Faso',
                            'Burundi' => 'Burundi',
                            'Cabo Verde' => 'Cabo Verde',
                            'Cambodia' => 'Cambodia',
                            'Cameroon' => 'Cameroon',
                            'Canada' => 'Canada',
                            'Central African Republic' => 'Central African Republic',
                            'Chad' => 'Chad',
                            'Chile' => 'Chile',
                            'China' => 'China',
                            'Colombia' => 'Colombia',
                            'Comoros' => 'Comoros',
                            'Congo (Congo-Brazzaville)' => 'Congo (Congo-Brazzaville)',
                            'Costa Rica' => 'Costa Rica',
                            'Croatia' => 'Croatia',
                            'Cuba' => 'Cuba',
                            'Cyprus' => 'Cyprus',
                            'Czech Republic' => 'Czech Republic',
                            'Denmark' => 'Denmark',
                            'Djibouti' => 'Djibouti',
                            'Dominica' => 'Dominica',
                            'Dominican Republic' => 'Dominican Republic',
                            'Ecuador' => 'Ecuador',
                            'Egypt' => 'Egypt',
                            'El Salvador' => 'El Salvador',
                            'Equatorial Guinea' => 'Equatorial Guinea',
                            'Eritrea' => 'Eritrea',
                            'Estonia' => 'Estonia',
                            'Eswatini (fmr. Swaziland)' => 'Eswatini (fmr. Swaziland)',
                            'Ethiopia' => 'Ethiopia',
                            'Fiji' => 'Fiji',
                            'Finland' => 'Finland',
                            'France' => 'France',
                            'Gabon' => 'Gabon',
                            'Gambia' => 'Gambia',
                            'Georgia' => 'Georgia',
                            'Germany' => 'Germany',
                            'Ghana' => 'Ghana',
                            'Greece' => 'Greece',
                            'Grenada' => 'Grenada',
                            'Guatemala' => 'Guatemala',
                            'Guinea' => 'Guinea',
                            'Guinea-Bissau' => 'Guinea-Bissau',
                            'Guyana' => 'Guyana',
                            'Haiti' => 'Haiti',
                            'Honduras' => 'Honduras',
                            'Hungary' => 'Hungary',
                            'Iceland' => 'Iceland',
                            'India' => 'India',
                            'Indonesia' => 'Indonesia',
                            'Iran' => 'Iran',
                            'Iraq' => 'Iraq',
                            'Ireland' => 'Ireland',
                            'Israel' => 'Israel',
                            'Italy' => 'Italy',
                            'Jamaica' => 'Jamaica',
                            'Japan' => 'Japan',
                            'Jordan' => 'Jordan',
                            'Kazakhstan' => 'Kazakhstan',
                            'Kenya' => 'Kenya',
                            'Kiribati' => 'Kiribati',
                            'Kuwait' => 'Kuwait',
                            'Kyrgyzstan' => 'Kyrgyzstan',
                            'Laos' => 'Laos',
                            'Latvia' => 'Latvia',
                            'Lebanon' => 'Lebanon',
                            'Lesotho' => 'Lesotho',
                            'Liberia' => 'Liberia',
                            'Libya' => 'Libya',
                            'Liechtenstein' => 'Liechtenstein',
                            'Lithuania' => 'Lithuania',
                            'Luxembourg' => 'Luxembourg',
                            'Madagascar' => 'Madagascar',
                            'Malawi' => 'Malawi',
                            'Malaysia' => 'Malaysia',
                            'Maldives' => 'Maldives',
                            'Mali' => 'Mali',
                            'Malta' => 'Malta',
                            'Marshall Islands' => 'Marshall Islands',
                            'Mauritania' => 'Mauritania',
                            'Mauritius' => 'Mauritius',
                            'Mexico' => 'Mexico',
                            'Micronesia' => 'Micronesia',
                            'Moldova' => 'Moldova',
                            'Monaco' => 'Monaco',
                            'Mongolia' => 'Mongolia',
                            'Montenegro' => 'Montenegro',
                            'Morocco' => 'Morocco',
                            'Mozambique' => 'Mozambique',
                            'Myanmar (formerly Burma)' => 'Myanmar (formerly Burma)',
                            'Namibia' => 'Namibia',
                            'Nauru' => 'Nauru',
                            'Nepal' => 'Nepal',
                            'Netherlands' => 'Netherlands',
                            'New Zealand' => 'New Zealand',
                            'Nicaragua' => 'Nicaragua',
                            'Niger' => 'Niger',
                            'Nigeria' => 'Nigeria',
                            'North Korea' => 'North Korea',
                            'North Macedonia' => 'North Macedonia',
                            'Norway' => 'Norway',
                            'Oman' => 'Oman',
                            'Pakistan' => 'Pakistan',
                            'Palau' => 'Palau',
                            'Panama' => 'Panama',
                            'Papua New Guinea' => 'Papua New Guinea',
                            'Paraguay' => 'Paraguay',
                            'Peru' => 'Peru',
                            'Philippines' => 'Philippines',
                            'Poland' => 'Poland',
                            'Portugal' => 'Portugal',
                            'Qatar' => 'Qatar',
                            'Romania' => 'Romania',
                            'Russia' => 'Russia',
                            'Rwanda' => 'Rwanda',
                            'Saint Kitts and Nevis' => 'Saint Kitts and Nevis',
                            'Saint Lucia' => 'Saint Lucia',
                            'Saint Vincent and the Grenadines' => 'Saint Vincent and the Grenadines',
                            'Samoa' => 'Samoa',
                            'San Marino' => 'San Marino',
                            'Sao Tome and Principe' => 'Sao Tome and Principe',
                            'Saudi Arabia' => 'Saudi Arabia',
                            'Senegal' => 'Senegal',
                            'Serbia' => 'Serbia',
                            'Seychelles' => 'Seychelles',
                            'Sierra Leone' => 'Sierra Leone',
                            'Singapore' => 'Singapore',
                            'Slovakia' => 'Slovakia',
                            'Slovenia' => 'Slovenia',
                            'Solomon Islands' => 'Solomon Islands',
                            'Somalia' => 'Somalia',
                            'South Africa' => 'South Africa',
                            'South Korea' => 'South Korea',
                            'South Sudan' => 'South Sudan',
                            'Spain' => 'Spain',
                            'Sri Lanka' => 'Sri Lanka',
                            'Sudan' => 'Sudan',
                            'Suriname' => 'Suriname',
                            'Sweden' => 'Sweden',
                            'Switzerland' => 'Switzerland',
                            'Syria' => 'Syria',
                            'Taiwan' => 'Taiwan',
                            'Tajikistan' => 'Tajikistan',
                            'Tanzania' => 'Tanzania',
                            'Thailand' => 'Thailand',
                            'Timor-Leste' => 'Timor-Leste',
                            'Togo' => 'Togo',
                            'Tonga' => 'Tonga',
                            'Trinidad and Tobago' => 'Trinidad and Tobago',
                            'Tunisia' => 'Tunisia',
                            'Turkey' => 'Turkey',
                            'Turkmenistan' => 'Turkmenistan',
                            'Tuvalu' => 'Tuvalu',
                            'Uganda' => 'Uganda',
                            'Ukraine' => 'Ukraine',
                            'United Arab Emirates' => 'United Arab Emirates',
                            'United Kingdom' => 'United Kingdom',
                            'United States' => 'United States',
                            'Uruguay' => 'Uruguay',
                            'Uzbekistan' => 'Uzbekistan',
                            'Vanuatu' => 'Vanuatu',
                            'Vatican City' => 'Vatican City',
                            'Venezuela' => 'Venezuela',
                            'Vietnam' => 'Vietnam',
                            'Yemen' => 'Yemen',
                            'Zambia' => 'Zambia',
                            'Zimbabwe' => 'Zimbabwe',
                        ])
                        ->searchable()
                        ->required()
                        ->label('Country'),
                    Forms\Components\TextInput::make('state')
                        ->label('State'),
                    Forms\Components\TextInput::make('city')
                        ->required()
                        ->label('City'),
                    Forms\Components\TextInput::make('post_code')
                        ->label('Post Code'),
                    Forms\Components\Select::make('currency')
                        ->options([
                            'pkr' => 'PKR',
                            'usd' => 'USD',
                            'gbp' => 'GBP',
                            'eur' => 'EUR',
                        ])
                        ->label('Currency'),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            '1' => 'Active',
                            '0' => 'Inactive',
                        ])
                        ->default(1)
                        ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('First Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->label('Last Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email Address')->searchable(),
                Tables\Columns\TextColumn::make('organization')->label('Organization')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')->label('Phone')->searchable(),
                Tables\Columns\TextColumn::make('country')->label('Country')->sortable(),
                Tables\Columns\TextColumn::make('city')->label('City')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currency')->label('Currency')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')->boolean(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
