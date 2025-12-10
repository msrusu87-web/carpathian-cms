<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50),
                    
                    Forms\Components\Select::make('type')
                        ->options([
                            'percentage' => 'Procent',
                            'fixed' => 'Sumă fixă',
                        ])
                        ->required(),
                    
                    Forms\Components\TextInput::make('value')
                        ->numeric()
                        ->required(),
                    
                    Forms\Components\DateTimePicker::make('valid_from'),
                    
                    Forms\Components\DateTimePicker::make('valid_until'),
                    
                    Forms\Components\TextInput::make('usage_limit')
                        ->numeric()
                        ->minValue(1),
                    
                    Forms\Components\TextInput::make('usage_count')
                        ->numeric()
                        ->default(0)
                        ->disabled(),
                    
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('usage_count')->label('Utilizări'),
                Tables\Columns\TextColumn::make('usage_limit')->label('Limită'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('valid_until')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
