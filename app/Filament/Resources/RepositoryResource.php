<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\Owner;
use App\Models\License;
use Filament\Forms\Form;
use App\Models\Repository;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\RepositoryResource\Pages;

class RepositoryResource extends Resource
{

    protected static ?string $model = Repository::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name'),
                TextInput::make('html_url')
                    ->suffixAction(
                        fn(?string $state): Action =>
                        Action::make('visit')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->url(
                                filled($state) ? $state : null,
                                shouldOpenInNewTab: true,
                            )
                    ),
                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),
                // add stats count as grid
                Grid::make(6)
                    ->schema([
                        TextInput::make('stargazers_count'),
                        TextInput::make('watchers_count'),
                        TextInput::make('forks_count'),
                        TextInput::make('open_issues_count'),
                        Checkbox::make('archived'),
                        Checkbox::make('disabled')
                    ]),
                Select::make('owners')
                    ->relationship('owners')
                    ->options(fn() => Owner::pluck('login', 'id'))
                    ->searchable(),
                Select::make('licenses')
                    ->relationship('licenses')
                    ->options(fn() => License::pluck('name', 'id'))
                    ->searchable(),
                FileUpload::make('thumbnail_url')
                    ->maxFiles(1)
                    ->required()
                    ->imageResizeTargetWidth('200')
                    ->imageResizeTargetHeight('400')
                    ->directory('repositories/thumbails')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name'),
                BooleanColumn::make('approved_at')
                    ->label('Approved')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->getStateUsing(fn(Repository $record): bool => $record->approved_at?->isPast() ?? false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListRepositories::route('/'),
            'create' => Pages\CreateRepository::route('/create'),
            'edit' => Pages\EditRepository::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereNull('approved_at')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
