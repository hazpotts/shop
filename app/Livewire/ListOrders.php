<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListOrders extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Order::where('user_id', auth()->user()->id))
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('total_amount')
                    ->money(),
                TextColumn::make('status'),
                TextColumn::make('payment_method'),
                TextColumn::make('payment_status'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.list-orders');
    }
}
