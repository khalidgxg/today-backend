<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Added for form components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;

// Added for table components
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

// Spatie Media Library components
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                SpatieMediaLibraryFileUpload::make('category_icons')
                    ->collection('category_icons')
                    ->image()
                    ->imageEditor()
                    ->preserveFilenames()
                    ->label('Category Icon')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                ColorPicker::make('color')
                    ->nullable(),
                ColorPicker::make('backgroundColor')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('category_icons')
                    ->collection('category_icons')
                    ->conversion('thumb')
                    ->size(40)
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn (Category $record): ?string => $record->description),
                TextColumn::make('color'),
                TextColumn::make('backgroundColor'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
