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
use Illuminate\Support\Facades\Http;

class AbsenPage extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.absen-page';

    protected static bool $shouldRegisterNavigation = false;

    public bool $nikValid = true;

    public ?string $name = '';

    public function mount(): void
    {
        $loginUser = auth()->user();
        $employee = Employee::where('PersonnelNo', $loginUser->PersonnelNo)->first();

        $this->data = [
            'PersonnelNo' => $loginUser->PersonnelNo,
            'Name' => $employee?->Name ?? '',
        ];

        if (request()->session()->has('success')) {
            Notification::make()
                ->title(request()->session()->get('success'))
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        $loginUser = auth()->user();
        $employee = Employee::where('PersonnelNo', $loginUser->PersonnelNo)->first();

        return $form
            ->schema([
                TextInput::make('PersonnelNo')
                    ->label('')
                    ->default($loginUser->PersonnelNo)
                    ->readOnly()
                    ->hidden(), // Tidak ditampilkan ke user

                TextInput::make('Name')
                    ->label('Nama')
                    ->default($employee?->Name ?? '')
                    ->readOnly(),

                TextInput::make('Latitude')->required(),
                TextInput::make('Longitude')->required(),
                DateTimePicker::make('jam')->required()->hidden(),
                Select::make('status')
                    ->label('Jenis Absensi')
                    ->required()
                    ->options([
                        'in' => "Masuk",
                        'out' => "Pulang",
                    ])
                    // ->default('in'),
                    ->default(null),
                View::make('components.camera-capture'),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

        // dd($data);

        $loginUser = auth()->user()->PersonnelNo;

        $this->nikValid = true;

        // $absenData = [
        //     'tgl_tarik' => now()->format('Y-m-d'),
        //     'tanggal' => now()->format('Y-m-d'),
        //     'mesin' => 1,
        //     'nik' => $loginUser,
        //     'jam' => now()->format('Y-m-d H:i:s'),
        //     'status' => $data['status'] === 'in' ? 1 : 2,
        //     'f_export' => 2,
        //     'idplant' => 1,
        // ];

        $latitude = $data['Latitude'];
        $longitude = $data['Longitude'];

        // Ambil lokasi dari LocationIq
        $apiKey = env('LOCATIONIQ_API_KEY');
        $locationName = null;

        try {
            $response = Http::timeout(10)->get("https://us1.locationiq.com/v1/reverse.php", [
                'key' => $apiKey,
                'lat' => $latitude,
                'lon' => $longitude,
                'format' => 'json',
            ]);

            $locationName = $response->json()['display_name'] ?? null;
        } catch (\Exception $e) {
            $locationName = null;
        }

        $absenData = [
            'tgl_tarik' => now()->format('Y-m-d'),
            'tanggal' => now()->format('Y-m-d'),
            'mesin' => 'HP',
            'nik' => $loginUser,
            'jam' => now()->format('Y-m-d H:i:s'),
            'status' => $data['status'] === 'in' ? 1 : 2,
            'f_export' => 2,
            'idplant' => 1,
            'Latitude' => $latitude,
            'Longitude' => $longitude,
            'Lokasi' => $locationName,
        ];

        Attendance::create($absenData);

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
