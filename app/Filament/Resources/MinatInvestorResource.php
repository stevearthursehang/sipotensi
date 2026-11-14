<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinatInvestorResource\Pages;
use App\Models\MinatInvestor;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class MinatInvestorResource extends Resource
{
    protected static ?string $model = MinatInvestor::class;

    protected static string|UnitEnum|null $navigationGroup = 'Investasi';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Minat Investor';

    protected static ?string $modelLabel = 'Minat Investor';

    protected static ?string $pluralModelLabel = 'Minat Investor';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('1. Identitas Badan Usaha & Pemohon')
                    ->description('Data resmi pihak investor / perusahaan penanam modal')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('nama')
                                ->label('Nama Lengkap / Kontak Person')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('jabatan')
                                ->label('Jabatan di Perusahaan')
                                ->placeholder('Contoh: Direktur Utama / Investment Head')
                                ->maxLength(100),

                            TextInput::make('perusahaan')
                                ->label('Nama Badan Usaha / Perusahaan')
                                ->placeholder('Contoh: PT. Sumber Bahari Makmur')
                                ->maxLength(255),
                        ]),

                        Grid::make(3)->schema([
                            Select::make('jenis_investor')
                                ->label('Klasifikasi Modal')
                                ->options([
                                    'pmdn' => 'PMDN (Modal Dalam Negeri)',
                                    'pma' => 'PMA (Modal Asing)',
                                    'perorangan' => 'Perorangan / Mandiri',
                                ]),

                            TextInput::make('asal_negara')
                                ->label('Kota / Negara Asal')
                                ->placeholder('Contoh: Jakarta / Singapura')
                                ->maxLength(100),

                            TextInput::make('nib')
                                ->label('NIB / NPWP Badan')
                                ->placeholder('Nomor Induk Berusaha / NPWP')
                                ->maxLength(50),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('Email Korespondensi')
                                ->email()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('telepon')
                                ->label('Nomor Telepon / WhatsApp Resmi')
                                ->required()
                                ->maxLength(30),
                        ]),
                    ]),

                Section::make('2. Rencana & Komitmen Penanaman Modal')
                    ->description('Detail peluang yang diminati serta alokasi rencana modal')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('potensi_investasi_id')
                                ->relationship('potensiInvestasi', 'nama')
                                ->label('Peluang Investasi Diminati')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('kelurahan_id')
                                ->relationship('kelurahan', 'nama')
                                ->label('Kelurahan / Lokasi')
                                ->searchable()
                                ->preload(),
                        ]),

                        Grid::make(3)->schema([
                            Select::make('rentang_modal')
                                ->label('Perkiraan Rencana Modal')
                                ->options([
                                    '< Rp 1 Miliar' => '< Rp 1 Miliar (Usaha Mikro/Kecil)',
                                    'Rp 1 - 5 Miliar' => 'Rp 1 - 5 Miliar (Menengah)',
                                    'Rp 5 - 25 Miliar' => 'Rp 5 - 25 Miliar (Besar)',
                                    '> Rp 25 Miliar' => '> Rp 25 Miliar (Skala Strategis)',
                                ]),

                            Select::make('model_kerjasama')
                                ->label('Model Kerja Sama')
                                ->options([
                                    'kpbu' => 'Kerjasama Pemerintah & Badan Usaha (KPBU / PPP)',
                                    'sewa_lahan' => 'Sewa Lahan / Pemanfaatan Aset Pemda (BOT/BTO)',
                                    'joint_venture' => 'Kemitraan / Usaha Bersama (Joint Venture)',
                                    'mandiri_100' => 'Investasi Mandiri 100%',
                                    'lainnya' => 'Lainnya',
                                ]),

                            Select::make('timeline_realisasi')
                                ->label('Timeline Rencana Mulai')
                                ->options([
                                    'segera' => 'Segera (< 6 Bulan)',
                                    '6_12_bulan' => 'Jangka Pendek (6 - 12 Bulan)',
                                    'studi_kelayakan' => 'Tahap Studi Kelayakan Awal',
                                ]),
                        ]),

                        TextInput::make('nilai_komitmen')
                            ->label('Nominal Rencana Investasi Spesifik (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('0.00'),
                    ]),

                Section::make('3. Fasilitasi Pemkab & Dokumen Pendukung')
                    ->description('Kebutuhan pendampingan dari DPMPTSP dan berkas Company Profile/LoI')
                    ->schema([
                        CheckboxList::make('kebutuhan_fasilitasi')
                            ->label('Bentuk Pendampingan / Fasilitasi yang Diharapkan dari DPMPTSP')
                            ->options([
                                'perizinan_oss' => 'Pendampingan Perizinan Berusaha OSS-RBA & AMDAL',
                                'fasilitasi_lahan' => 'Fasilitasi Kepastian Hak Atas Lahan & Tata Ruang',
                                'insentif_daerah' => 'Kemudahan Retribusi & Insentif Investasi Daerah',
                                'audiensi_pemkab' => 'Jadwal Audiensi Teknis / Pertemuan dengan Pimpinan Daerah',
                                'mitra_lokal' => 'Koneksi dengan Pemasok / Pengusaha Lokal Sangihe',
                            ])
                            ->columns(2),

                        Textarea::make('pesan')
                            ->label('Pesan / Catatan Tambahan Pemohon')
                            ->placeholder('Jelaskan rencana bisnis singkat, kebutuhan lahan, pasokan listrik, dsb.')
                            ->rows(3),

                        FileUpload::make('dokumen_loi')
                            ->label('Dokumen Letter of Intent (LoI) / Profil Perusahaan (PDF)')
                            ->disk('public')
                            ->directory('investor/loi')
                            ->visibility('public')
                            ->extraInputAttributes(['accept' => '.pdf,application/pdf'])
                            ->helperText('Format PDF, maksimal 10MB'),
                    ]),

                Section::make('4. Verifikasi & Tindak Lanjut DPMPTSP')
                    ->description('Panel internal dinas untuk memperbarui status dan catatan bagi investor')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('status')
                                ->label('Status Penanganan Minat')
                                ->options([
                                    'diajukan' => 'Diajukan (Baru Masuk)',
                                    'sedang_ditinjau' => 'Sedang Ditinjau Tim DPMPTSP',
                                    'jadwal_audiensi' => 'Dijadwalkan Pertemuan / Audiensi',
                                    'disetujui' => 'Disetujui / Rekomendasi Terbit (MoU)',
                                    'ditolak' => 'Belum Memenuhi Syarat / Ditolak',
                                ])
                                ->required(),

                            Forms\Components\DateTimePicker::make('tanggal_tindak_lanjut')
                                ->label('Jadwal Pertemuan / Tanggal Update'),

                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->label('Akun Investor Terkait')
                                ->searchable(),
                        ]),

                        Textarea::make('catatan_admin')
                            ->label('Catatan Dinas untuk Investor (Dapat Dibaca Investor di Dashboard)')
                            ->placeholder('Tuliskan catatan tindak lanjut, link Zoom audiensi, atau arahan persyaratan untuk investor...')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama & Perusahaan')
                    ->description(fn (MinatInvestor $record): string => ($record->jabatan ? $record->jabatan . ' - ' : '') . ($record->perusahaan ?: 'Perorangan'))
                    ->searchable(['nama', 'perusahaan'])
                    ->sortable(),

                TextColumn::make('jenis_investor')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => strtoupper($state ?? 'PMDN'))
                    ->color(fn (?string $state): string => match ($state) {
                        'pma' => 'danger',
                        'pmdn' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('potensiInvestasi.nama')
                    ->label('Potensi Diminati')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                TextColumn::make('rentang_modal')
                    ->label('Rencana Modal')
                    ->default(fn (MinatInvestor $record): string => $record->nilai_komitmen ? 'Rp ' . number_format($record->nilai_komitmen, 0, ',', '.') : '-'),

                TextColumn::make('telepon')
                    ->label('Kontak')
                    ->icon('heroicon-m-phone')
                    ->url(fn (MinatInvestor $record): string => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->telepon), true),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'diajukan' => 'Diajukan',
                        'sedang_ditinjau' => 'Ditinjau',
                        'jadwal_audiensi' => 'Jadwal Audiensi',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        default => 'Diajukan',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'diajukan' => 'gray',
                        'sedang_ditinjau' => 'info',
                        'jadwal_audiensi' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'sedang_ditinjau' => 'Sedang Ditinjau',
                        'jadwal_audiensi' => 'Jadwal Audiensi',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),

                SelectFilter::make('jenis_investor')
                    ->label('Klasifikasi Modal')
                    ->options([
                        'pmdn' => 'PMDN (Modal Dalam Negeri)',
                        'pma' => 'PMA (Modal Asing)',
                        'perorangan' => 'Perorangan',
                    ]),

                SelectFilter::make('potensi_investasi_id')
                    ->relationship('potensiInvestasi', 'nama')
                    ->label('Potensi Investasi')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListMinatInvestors::route('/'),
            'create' => Pages\CreateMinatInvestor::route('/create'),
            'edit' => Pages\EditMinatInvestor::route('/{record}/edit'),
        ];
    }
}
