<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\VendorClaimStatus;
use App\Filament\App\Resources\VendorClaimResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\VendorClaim;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VendorClaimResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = VendorClaim::class;

    protected static ?string $module = 'rtv-vendor-claims';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static string|\UnitEnum|null $navigationGroup = 'Vendor claims';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('rma_item_id')->relationship('rmaItem', 'id')->searchable(),
                TextInput::make('supplier_name')->required(),
                Select::make('status')->options(collect(VendorClaimStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all()),
                TextInput::make('claim_amount_cents')->numeric(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_name')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('claim_amount_cents')->label('Amount (¢)'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendorClaims::route('/'),
            'create' => Pages\CreateVendorClaim::route('/create'),
            'edit' => Pages\EditVendorClaim::route('/{record}/edit'),
        ];
    }
}
