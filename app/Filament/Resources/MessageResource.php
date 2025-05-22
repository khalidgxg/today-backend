<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Filament\Notifications\Notification;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name') // Assuming 'name' is the column to display from the Category model
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('text')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name') // Assuming 'name' is the column to display from the Category model
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
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
            ])
            ->headerActions([
                Action::make('importMessages')
                    ->label('Import Messages')
                    ->form([
                        FileUpload::make('excel_file')
                            ->label('Excel File')
                            ->required()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Category')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $filePath = Storage::disk('public')->path($data['excel_file']);
                        $categoryId = $data['category_id'];
                        try {
                            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                            $sheet = $spreadsheet->getActiveSheet();
                            $messagesCreated = 0;

                            foreach ($sheet->getRowIterator() as $row) {
                                // Skip header row if you have one
                                // if ($row->getRowIndex() === 1) { continue; }

                                $cellIterator = $row->getCellIterator();
                                $cellIterator->setIterateOnlyExistingCells(false);
                                $cells = [];
                                foreach ($cellIterator as $cell) {
                                    $cells[] = $cell->getValue();
                                }

                                // Assuming the message text is in the first column (index 0)
                                $messageText = $cells[0] ?? null;

                                if ($messageText) {
                                    Message::create([
                                        'text' => $messageText,
                                        'category_id' => $categoryId,
                                    ]);
                                    $messagesCreated++;
                                }
                            }

                            Notification::make()
                                ->title('Messages imported successfully')
                                ->body($messagesCreated . ' messages were created.')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error importing messages')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }

                        // Clean up the uploaded file
                        Storage::disk('public')->delete($data['excel_file']);
                    })
            ])
        ;
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
