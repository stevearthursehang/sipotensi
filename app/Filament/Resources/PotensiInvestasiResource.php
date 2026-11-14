<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PotensiInvestasiResource\Pages;
use App\Filament\Resources\PotensiInvestasiResource\Schemas\PotensiInvestasiForm;
use App\Models\PotensiInvestasi;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PotensiInvestasiResource extends Resource
{
    protected static ?string $model = PotensiInvestasi::class;

    protected static string|UnitEnum|null $navigationGroup = 'Investasi';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Potensi Investasi';

    protected static ?string $modelLabel = 'Potensi Investasi';

    protected static ?string $pluralModelLabel = 'Potensi Investasi';

    public static function form(Schema $schema): Schema
    {
        return PotensiInvestasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelurahan.kecamatan.nama')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('kelurahan.nama')
                    ->label('Kelurahan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Potensi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_potensi')
                    ->label('Sektor')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('skala_peluang')
                    ->label('Skala')
                    ->badge(),

                Tables\Columns\TextColumn::make('nilai_estimasi_investasi')
                    ->label('Nilai Estimasi')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_lahan')
                    ->label('Status Lahan')
                    ->badge(),

                Tables\Columns\TextColumn::make('status_perizinan')
                    ->label('Status Perizinan')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->relationship('kelurahan.kecamatan', 'nama')
                    ->label('Kecamatan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('kelurahan')
                    ->relationship('kelurahan', 'nama')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('skala_peluang')
                    ->options([
                        'kecil' => 'Kecil',
                        'menengah' => 'Menengah',
                        'besar' => 'Besar',
                    ]),

                Tables\Filters\SelectFilter::make('status_lahan')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dalam_proses' => 'Dalam Proses',
                        'tidak_tersedia' => 'Tidak Tersedia',
                    ]),

                Tables\Filters\SelectFilter::make('status_perizinan')
                    ->options([
                        'sudah_izin' => 'Sudah Izin',
                        'dalam_proses' => 'Dalam Proses',
                        'belum_izin' => 'Belum Izin',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn (PotensiInvestasi $record) => route('potensi.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPotensiInvestasis::route('/'),
            'create' => Pages\CreatePotensiInvestasi::route('/create'),
            'view' => Pages\ViewPotensiInvestasi::route('/{record}'),
            'edit' => Pages\EditPotensiInvestasi::route('/{record}/edit'),
        ];
    }
}
