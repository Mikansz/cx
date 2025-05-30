<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Filament\Resources\PenggajianResource\RelationManagers;
use App\Models\Penggajian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Penggajian';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $modelLabel = 'Penggajian';
    protected static ?string $pluralModelLabel = 'Penggajian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Karyawan')
                    ->schema([
                        Forms\Components\Select::make('karyawan_id')
                            ->relationship(
                                name: 'karyawan',
                                titleAttribute: 'kode_karyawan',
                                modifyQueryUsing: fn ($query) => $query->with('jabatan')->whereNotNull('kode_karyawan')->where('kode_karyawan', '!=', '')
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->kode_karyawan . ' - ' . ($record->user?->name ?? 'No User'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    // Get karyawan with jabatan
                                    $karyawan = \App\Models\Karyawan::with('jabatan')->find($state);
                                    
                                    if ($karyawan && $karyawan->jabatan) {
                                        $jabatan = $karyawan->jabatan;
                                        
                                        // Auto-fill gaji pokok dan tunjangan dari jabatan
                                        $set('gaji_pokok', $jabatan->gaji_pokok);
                                        $set('tunjangan_transport', $jabatan->tunjangan_transportasi);
                                        $set('tunjangan_makan', $jabatan->tunjangan_makan);
                                        
                                        // Set other allowances to 0 by default
                                        $set('tunjangan_komunikasi', 0);
                                        $set('tunjangan_kesehatan', 0);
                                        $set('tunjangan_lembur', 0);
                                        $set('tunjangan_hari_raya', 0);
                                        $set('tunjangan_insentif', 0);
                                        $set('tunjangan_lainnya', 0);
                                        
                                        // Set deductions to 0 by default
                                        $set('potongan_kasbon', 0);
                                        $set('potongan_tidak_hadir', 0);
                                        $set('potongan_penyesuaian_lainnya', 0);
                                        $set('potongan_pph21', 0);
                                        
                                        // Calculate total automatically
                                        self::updateTotalGaji($set, $get);
                                    }
                                }
                            })
                            ->label('Karyawan'),
                        Forms\Components\DatePicker::make('periode')
                            ->label('Periode Penggajian')
                            ->required()
                            ->default(now()),
                    ])->columns(2),
                
                Forms\Components\Section::make('Gaji & Tunjangan')
                    ->schema([
                        Forms\Components\TextInput::make('gaji_pokok')
                            ->label('Gaji Pokok')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Akan terisi otomatis berdasarkan jabatan karyawan'),
                        Forms\Components\TextInput::make('tunjangan_transport')
                            ->label('Tunjangan Transportasi')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Akan terisi otomatis berdasarkan jabatan karyawan'),
                        Forms\Components\TextInput::make('tunjangan_makan')
                            ->label('Tunjangan Makan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Akan terisi otomatis berdasarkan jabatan karyawan'),
                        Forms\Components\TextInput::make('tunjangan_komunikasi')
                            ->label('Tunjangan Komunikasi')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('tunjangan_kesehatan')
                            ->label('Tunjangan Kesehatan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('tunjangan_lembur')
                            ->label('Tunjangan Lembur')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('tunjangan_hari_raya')
                            ->label('Tunjangan Hari Raya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('tunjangan_insentif')
                            ->label('Tunjangan Insentif')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('tunjangan_lainnya')
                            ->label('Tunjangan Lainnya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                    ])->columns(3),
                

                
                Forms\Components\Section::make('Potongan')
                    ->schema([

                        Forms\Components\TextInput::make('potongan_kasbon')
                            ->label('Potongan Kasbon')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('potongan_tidak_hadir')
                            ->label('Potongan Tidak Hadir')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('potongan_penyesuaian_lainnya')
                            ->label('Potongan Penyesuaian Lainnya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                        Forms\Components\TextInput::make('potongan_pph21')
                            ->label('Potongan PPh21')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ])->columns(3),
                
                Forms\Components\Section::make('Total & Keterangan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('total_gaji')
                                    ->label('Total Gaji')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Klik tombol untuk menghitung total gaji'),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('hitung_total')
                                        ->label('Hitung Total Gaji')
                                        ->icon('heroicon-o-calculator')
                                        ->color('success')
                                        ->action(function (callable $set, callable $get) {
                                            self::updateTotalGaji($set, $get);
                                        }),
                                ]),
                            ]),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.kode_karyawan')
                    ->label('Kode Karyawan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('karyawan.user.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->date('F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_gaji')
                    ->label('Total Gaji')
                    ->money('IDR')
                    ->sortable(),


                Tables\Columns\TextColumn::make('tunjangan_transport')
                    ->label('Tunjangan Transport')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_makan')
                    ->label('Tunjangan Makan')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_komunikasi')
                    ->label('Tunjangan Komunikasi')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_kesehatan')
                    ->label('Tunjangan Kesehatan')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_lembur')
                    ->label('Tunjangan Lembur')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_hari_raya')
                    ->label('Tunjangan Hari Raya')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_insentif')
                    ->label('Tunjangan Insentif')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tunjangan_lainnya')
                    ->label('Tunjangan Lainnya')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('potongan_absen')
                    ->label('Potongan Absen')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('potongan_kasbon')
                    ->label('Potongan Kasbon')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('potongan_tidak_hadir')
                    ->label('Potongan Tidak Hadir')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('potongan_penyesuaian_lainnya')
                    ->label('Potongan Penyesuaian')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('potongan_pph21')
                    ->label('Potongan PPh21')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('karyawan_id')
                    ->relationship('karyawan', 'kode_karyawan', function ($query) {
                        return $query->whereNotNull('kode_karyawan')->where('kode_karyawan', '!=', '');
                    })
                    ->searchable()
                    ->preload()
                    ->label('Karyawan'),
                Tables\Filters\Filter::make('periode')
                    ->form([
                        Forms\Components\DatePicker::make('periode_from')
                            ->label('Dari Periode'),
                        Forms\Components\DatePicker::make('periode_until')
                            ->label('Sampai Periode'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['periode_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('periode', '>=', $date),
                            )
                            ->when(
                                $data['periode_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('periode', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus'),
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
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
    
    public static function updateTotalGaji(callable $set, callable $get): void
    {
        // Ambil semua nilai dari form
        $gajiPokok = (float) ($get('gaji_pokok') ?? 0);
        $tunjanganTransport = (float) ($get('tunjangan_transport') ?? 0);
        $tunjanganMakan = (float) ($get('tunjangan_makan') ?? 0);
        $tunjanganKomunikasi = (float) ($get('tunjangan_komunikasi') ?? 0);
        $tunjanganKesehatan = (float) ($get('tunjangan_kesehatan') ?? 0);
        $tunjanganLembur = (float) ($get('tunjangan_lembur') ?? 0);
        $tunjanganHariRaya = (float) ($get('tunjangan_hari_raya') ?? 0);
        $tunjanganInsentif = (float) ($get('tunjangan_insentif') ?? 0);
        $tunjanganLainnya = (float) ($get('tunjangan_lainnya') ?? 0);
        
        $potonganKasbon = (float) ($get('potongan_kasbon') ?? 0);
        $potonganTidakHadir = (float) ($get('potongan_tidak_hadir') ?? 0);
        $potonganPenyesuaianLainnya = (float) ($get('potongan_penyesuaian_lainnya') ?? 0);
        $potonganPph21 = (float) ($get('potongan_pph21') ?? 0);
        
        // Hitung total tunjangan
        $totalTunjangan = $tunjanganTransport + $tunjanganMakan + $tunjanganKomunikasi + 
                         $tunjanganKesehatan + $tunjanganLembur + $tunjanganHariRaya + 
                         $tunjanganInsentif + $tunjanganLainnya;
        
        // Hitung total potongan
        $totalPotongan = $potonganKasbon + $potonganTidakHadir + 
                        $potonganPenyesuaianLainnya + $potonganPph21;
        
        // Hitung total gaji
        $totalGaji = $gajiPokok + $totalTunjangan - $totalPotongan;
        
        // Set nilai total gaji
        $set('total_gaji', $totalGaji);
    }
}
