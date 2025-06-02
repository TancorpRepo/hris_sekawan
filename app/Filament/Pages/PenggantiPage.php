<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use App\Models\Employee;
use App\Models\Ijin;

class PenggantiPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Pengganti Hari Libur';

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pengganti-page';

    protected static bool $shouldRegisterNavigation = false;

    public bool $nikValid = false;

    public ?string $name = null;

    public function mount()
    {
        if (request()->session()->has('success')) {
            Notification::make()
                ->title(request()->session()->get('success'))->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('PersonnelNo')
                    ->label('')
                    // ->required()
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
                DatePicker::make('CurrentDateTime')
                    ->label('Tanggal Off')
                    ->timezone('Asia/Jakarta')
                    ->displayFormat('YYYY-MM-DD')
                    ->required(),
                TextInput::make('Keterangan')
                    ->label('Keterangan')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();

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

        //Validasi
        $employee = Employee::whereRaw('BINARY PersonnelNo = ?', $data['PersonnelNo'])->first();

        if (!$employee) {
            Notification::make()->title('NIK Salah')->body('NIK yang anda masukkan salah')->color('danger')->send();

            return;
        }

        $data['IP'] = Request()->ip();

        // $data['created_at'] = now();

        Ijin::create($data);

        Notification::make()
            ->title('Presensi berhasil disimpan')
            ->color('success')
            ->success()
            ->persistent()
            ->send();

        return redirect()->route('filament.admin.pages.pengganti')->with('sucess', 'Ijin berhasil disimpan');
    }

    public function searchPersonnelNo()
    {
        $query = request('query', '');

        $hasil = Employee::where('PersonnelNo', 'LIKE', "%$query%")
            ->limit(1)
            ->get(['PersonnelNo', 'Name']);

        return response()->json($hasil);
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


