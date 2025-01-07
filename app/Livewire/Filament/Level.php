<?php

namespace App\Livewire\Filament;

use App\Models\KycRequest;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Livewire\WithFileUploads;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions\Action;
use Livewire\Attributes\Rule;
class Level extends Component implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;
    public ?array $data = [];
    public function upgradeLevel()
    {
        $this->validate();
        $data = $this->form->getState();
        $level = Auth::user()->level->level + 1;
        $user_id = Auth::id();
        $data = [
            'reuest_for_level' => $level,
            'user_id' => $user_id,
            'status' => 1,
            'driving_license' => $data['driving_license'],
            'passport' => $data['passport'],
            'org_document' => $data['org_document'] ?? null,
        ];
        $existKycRequest = KycRequest::where(['user_id' => $user_id, 'status' => 1, 'reuest_for_level' => $level])->exists();
        if ($existKycRequest) {
            Notification::make()
                ->title('Error')
                ->body('You have already applied for upgrading level.')
                ->danger()
                ->icon('heroicon-o-exclamation-circle')
                ->iconColor('danger')
                ->duration(5000)
                ->send();
            $this->dispatch('close-modal', id: 'upgrade-level');
            return redirect()->route('filament.admin.resources.levels.index');
        }

        KycRequest::updateOrCreate($data);
        $this->form->fill();
        $this->data = [];
        $this->reset('data');
        $this->dispatch('close-modal', id: 'upgrade-level');
        Notification::make()
            ->title('Success')
            ->body('You have successfully submitted your application for upgrading Your level.')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->duration(5000)
            ->send();
            return redirect()->route('filament.admin.resources.levels.index');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('driving_license')
                    ->imageEditor()
                    ->required()
                    ->uploadingMessage('Uploading driver license...')
                    ->image(),
                FileUpload::make('passport')
                    ->imageEditor()
                    ->required()
                    ->uploadingMessage('Uploading driver license...')
                    ->image(),
                FileUpload::make('org_document')
                    ->label('Organization Document')
                    ->visible(Auth::user()->hasRole('Organization'))
                    ->imageEditor()
                    ->openable()
                    ->required()
                    ->uploadingMessage('Uploading organization document...')
                    ->columnSpanFull()
                    ->image(),
                TextInput::make('email_verification')
                    ->hintIcon('heroicon-o-question-mark-circle')
                    ->hintIconTooltip(fn() => !empty(Auth::user()->email_verified_at) ? 'Your Email is Verified' : 'Your email is not verified. First verify your email by clicking the button below')
                    ->label(fn() => !empty(Auth::user()->email_verified_at) ? 'Your Email is Verified' : 'Your email is not verified.')
                    ->placeholder(Auth::user()->email)
                    ->disabled()
                    ->visible(fn() => empty(Auth::user()->email_verified_at))
                    ->suffixAction(
                        Action::make('sendEmailVerification')
                            ->icon('heroicon-m-paper-airplane')
                            ->tooltip('Send Email Verification')
                            ->action(function () {
                                auth()->user()->sendEmailVerificationNotification();
                                Notification::make()
                                    ->title('Verification Email Sent')
                                    ->success()
                                    ->send();
                            })
                    )->columnSpanFull(),
            ])
            ->statePath('data')
            ->columns(2);
    }
    public function showModal()
    {
        $this->dispatch('open-modal', id: 'upgrade-level');
    }
    public function mount(): void
    {
        $this->form->fill();
    }
}
