<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description_en')
                    ->label('Description (English)')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_ar')
                    ->label('Description (Arabic)')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('section')
                    ->options([
                        'open' => 'Open',
                        'middle_work' => 'Middle Work',
                        'close' => 'Close',
                    ])
                    ->required(),
                Forms\Components\Select::make('language')
                    ->options([
                        'english' => 'English',
                        'arabic' => 'Arabic',
                    ])
                    ->default('english')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default('default')
                    ->label('Assigned User'),
                Forms\Components\Toggle::make('is_completed')
                    ->label('Completed')
                    ->default(false)
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        if ($state) {
                            $set('completed_at', now());
                        }
                    }),
                Forms\Components\Toggle::make('is_daily')
                    ->label('Daily Task')
                    ->default(false),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label('Completed At')
                    ->visible(fn (callable $get) => $get('is_completed')),
                Forms\Components\FileUpload::make('image')
                    ->directory('task_images')
                    ->visibility('public')
                    ->visible(fn ($record) => $record && $record->is_completed && in_array($record->section, ['open', 'close']))
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state && $record) {
                            $record->images()->create(['image_path' => $state]);
                            session()->flash('success', 'Image uploaded successfully! Please move to the next task.');
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->getStateUsing(function ($record) {
                        return $record->language === 'arabic' && $record->description_ar
                            ? $record->description_ar
                            : ($record->description_en ?? $record->description);
                    })
                    ->html()
                    ->wrap(),
                Tables\Columns\TextColumn::make('section')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('language'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned User'),
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('Completed Image')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable(),
//                    ->visible(fn ($record) => $record->is_completed),
                Tables\Columns\ToggleColumn::make('is_completed'),
                Tables\Columns\IconColumn::make('is_daily')
                    ->label('Daily Task')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->options([
                        'open' => 'Open',
                        'middle_work' => 'Middle Work',
                        'close' => 'Close',
                    ]),
                Tables\Filters\Filter::make('completed')
                    ->query(fn (Builder $query) => $query->where('is_completed', true))
                    ->label('Completed Tasks'),
                Tables\Filters\TernaryFilter::make('is_daily')
                    ->label('Daily Task'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultGroup('section');
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
