<x-filament::page>
    <form wire:submit.prevent="submit">

        <div class="text-info">
            Nyalakan izin kamera dan lokasi untuk melakukan absensi
        </div>

        <br />

        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="data.PersonnelNo">
            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                Masukkan NIK<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
            </span>
        </label>

        <div class="fi-input-wrp-input min-w-0 flex-1">
            <input type="text" wire:model.lazy="data.PersonnelNo" id="data.PersonnelNo" required="required"
                name="PersonnelNo" list="nik-suggestions" autocomplete="off" placeholder="Masukkan NIK"
                class="fi-input block w-full border border-gray-300 py-1.5 text-base text-gray-950 transition duration-75
                placeholder:text-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                disabled:text-gray-500 sm:text-sm sm:leading-6 bg-white ps-3 pe-3">
        </div>
        <input type="hidden" wire:model.lazy="data.PersonnelNo">

        @if (!$nikValid && !empty($data['PersonnelNo']))
            <sup class="text-danger-600 dark:text-danger-400 font-medium">
                <div class="text-red-500 text-sm mt-2">
                    @if ($data['PersonnelNo'] !== auth()->user()->PersonnelNo)
                        NIK yang anda masukkan tidak sesuai dengan akun yang sedang login
                    @else
                        NIK yang anda masukkan salah
                    @endif
                </div>
            </sup>
        @endif

        {{-- Form Fields --}}
        {{ $this->form }}

        {{-- Latitude, Longitude, dan CurrentDateTime --}}
        <input type="hidden" id="data.Latitude" name="Latitude" value="">
        <input type="hidden" id="data.Longitude" name="Longitude" value="">
        <input type="hidden" id="data.CurrentDateTime" name="CurrentDateTime" value="">

        {{-- Submit Button --}}
        <x-filament::button type="submit" size="sm" class="my-4" :disabled="!$nikValid">
            Submit
        </x-filament::button>
    </form>

    <script>
        function requestLocation() {
            navigator.geolocation.getCurrentPosition(function(location) {
                let latInput = document.getElementById("data.Latitude");
                let longInput = document.getElementById("data.Longitude");
                let timeInput = document.getElementById("data.CurrentDateTime");

                if (latInput && longInput && timeInput) {
                    latInput.value = location.coords.latitude;
                    latInput.dispatchEvent(new Event('input'));

                    longInput.value = location.coords.longitude;
                    longInput.dispatchEvent(new Event('input'));

                    timeInput.value = ((new Date).toISOString()).substring(0, 19);
                    timeInput.dispatchEvent(new Event('input'));
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function(event) {
            requestLocation();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
                    const inputNIK = document.querySelector('input[name="PersonnelNo"]');
                    const datalist = document.getElementById('nik-suggestions');

                    inputNIK.addEventListener('blur', function() {
                            const NIK = this.value;

                            fetch(/search-personnel-no?query=${NIK})
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        // Update nama secara otomatis
                                        document.querySelector('input[name="Name"]').value = data[0].Name;
                                        @this.set('nikValid', true); // Enable submit button
                                    } else {
                                        alert('NIK tidak valid!');
                                        @this.set('nikValid', false); // Disable submit button
                                        document.querySelector('input[name="Name"]').value = ''; // Clear Name
                                    }
                                });
                            });
                    });
    </script>

    <datalist id="nik-suggestions"></datalist>

</x-filament::page>