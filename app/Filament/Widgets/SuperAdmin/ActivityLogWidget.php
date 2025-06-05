<?php

namespace App\Filament\Widgets\SuperAdmin;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogWidget extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->hasRole('super_admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Gabungkan query dari berbagai model untuk menampilkan aktivitas terbaru
                User::query()
                    ->select([
                        \DB::raw("CONCAT('user_', users.id) as id"),
                        'users.name as user_name',
                        'users.created_at',
                        \DB::raw("'User Registration' as activity_type"),
                        \DB::raw("CONCAT('User ', users.name, ' registered') as description")
                    ])
                    ->unionAll(
                        Attendance::query()
                            ->join('users', 'attendances.user_id', '=', 'users.id')
                            ->select([
                                \DB::raw("CONCAT('attendance_', attendances.id) as id"),
                                'users.name as user_name',
                                'attendances.created_at',
                                \DB::raw("'Attendance' as activity_type"),
                                \DB::raw("CONCAT('User ', users.name, ' checked in') as description")
                            ])
                            ->latest('attendances.created_at')
                            ->limit(10)
                    )
                    ->unionAll(
                        Leave::query()
                            ->join('users', 'leaves.user_id', '=', 'users.id')
                            ->select([
                                \DB::raw("CONCAT('leave_', leaves.id) as id"),
                                'users.name as user_name',
                                'leaves.created_at',
                                \DB::raw("'Leave Request' as activity_type"),
                                \DB::raw("CONCAT('User ', users.name, ' requested leave') as description")
                            ])
                            ->latest('leaves.created_at')
                            ->limit(10)
                    )
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user_name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('activity_type')
                    ->label('Jenis Aktivitas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'User Registration' => 'success',
                        'Attendance' => 'info',
                        'Leave Request' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
