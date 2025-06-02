<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Employee;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AbsenPage extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.absen-page';

    protected static bool $shouldRegisterNavigation = false;

    public bool $nikValid = false;

    public ?string $name = '';

    public function mount(): void
    {
        if (request()->session()->has('success')) {
            Notification::make()
                ->title(request()->session()->get('success'))
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('PersonnelNo')
                    ->label('')
                    ->rules(['exists:tbl_employees,PersonnelNo'])
                    ->validationMessages([
                        'exists' => 'NIK yang anda masukkan salah'
                    ])
                    ->extraAttributes(['style' => 'display:none;', 'autocomplete' => 'off', 'list' => 'nik-suggestions'])
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Ketika NIK diubah, panggil fungsi untuk mendapatkan data karyawan
                        $employee = Employee::where('PersonnelNo', $state)->first();

                        if ($employee) {
                            $set('data.Name', $employee->Name);
                        } else {
                            $set('data.Name', null);
                        }
                    })
                    ->readOnly(),
                TextInput::make('Name')
                    ->label('Nama')
                    ->readOnly(),

                TextInput::make('Latitude')
                    ->label('Latitude')
                    ->required()
                    ->hidden(),
                TextInput::make('Longitude')
                    ->label('Longitude')
                    ->required()
                    ->hidden(),
                DateTimePicker::make('CurrentDateTime')
                    ->label('Waktu Absen')
                    // ->timezone('Asia/Jakarta')
                    // ->default(now())
                    ->required()
                    ->hidden(),
                Select::make('CheckType')
                    ->label('Jenis Absensi')
                    ->required()
                    ->options([
                        'in' => "Masuk",
                        'out' => "Pulang",
                    ])
                    ->default('in'),
                View::make('components.camera-capture'), // Reference the custom Blade view
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        // dd($data);

        $loginUser = auth()->user()->PersonnelNo;

        // Validasi NIK harus sama dengan NIK yang login user
        if ($data['PersonnelNo'] !== $loginUser) {
            $this->nikValid = false;

            Notification::make()
                ->title('NIK Anda tidak sesuai')
                ->body('NIK yang anda masukkan tidak sesuai')
                ->color('danger')
                ->send();

            return;
        }

        // Validasi NIK ada di database
        $employee = Employee::whereRaw('BINARY PersonnelNo = ?', [$data['PersonnelNo']])->first();

        if (!$employee) {
            $this->nikValid = false;

            Notification::make()->title('NIK Salah')->body('NIK yang anda masukkan salah')->color('danger')->send();

            return;
        }

        $this->nikValid = true;

        $data['CurrentDateTime'] = now();
        $data['IP'] = request()->ip();

        Attendance::create($data);

        Notification::make()
            ->title('Presensi berhasil disimpan')
            ->color('success')
            ->success()
            ->persistent()
            ->send();

        return redirect()->route('filament.admin.pages.absen')->with('success', 'Kehadiran berhasil disimpan');
    }

    public function updatedDataPersonnelNo($value)
    {
        $loggedInPersonnelNo = auth()->user()->PersonnelNo;

        if ($value !== $loggedInPersonnelNo) {
            $this->data['Name'] = null;
            $this->nikValid = false;

            Notification::make()
                ->title('NIK Anda tidak sesuai')
                ->body('NIK yang Anda masukkan tidak sesuai dengan akun yang sedang login.')
                ->color('danger')
                ->send();

            return;
        }

        // Cari data karyawan berdasarkan PersonnelNo
        // $employee = Employee::whereRaw('BINARY PersonnelNo = ?', [$value])->first();
        $employee = Employee::where('PersonnelNo', $value)->first();

        if ($employee) {
            // Set nilai Name jika karyawan ditemukan
            $this->data['Name'] = $employee->Name;
            $this->nikValid = true; // NIK valid
        } else {
            // Reset Name jika tidak ditemukan
            $this->data['Name'] = null;
            $this->nikValid = false;
        }
    }

    public function setLatitude($latitude)
    {
        $data['Latitude'] = $latitude;
    }
    public function hello()
    {
        // Log::alert("halo");
    }
    public function setLongitude($longitude)
    {
        $data['Longitude'] = $longitude;
    }
}
