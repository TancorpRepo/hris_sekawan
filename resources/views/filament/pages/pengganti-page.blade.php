<x-filament::page>
    <form wire:submit.prevent="submit">

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

        {{ $this->form->render() }}

        <input type="hidden" id="data.Latitude" name="Latitude" value="">
        <input type="hidden" id="data.Longitude" name="Longitude" value="">
        <input type="hidden" id="data.CurrentDateTime" name="CurrentDateTime" value="">

        <x-filament::button type="submit" size="sm" class="my-4" :disabled="!$nikValid">
            Submit
        </x-filament::button>
    </form>
    <script>
        function requestLocation() {
            navigator.geolocation.getCurrentPosition(function(location) {
                var lat = location.coords.latitude;
                var long = location.coords.longitude;

                // Set Latitude & Longitude values
                document.querySelector("[wire\\:model='data.Latitude']").value = lat;
                document.querySelector("[wire\\:model='data.Longitude']").value = long;

                document.querySelector("[wire\\:model='data.Latitude']").dispatchEvent(new Event('input'));
                document.querySelector("[wire\\:model='data.Longitude']").dispatchEvent(new Event('input'));

                var currentDate = new Date().toLocaleDateString("en-CA"); // Format ISO (YYYY-MM-DD)
                document.querySelector("[wire\\:model='data.CurrentDateTime']").value = currentDate;

                document.querySelector("[wire\\:model='data.CurrentDateTime']").dispatchEvent(new Event('input'));
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            requestLocation();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
                    const inputNIK = document.querySelector('input[name="PersonnelNo"]');
                    const datalist = document.getElementById('nik-suggestions');

                    input.addEventListener('blur', function() {
                            const NIK = this.value;

                            fetch(/search-personnel-no?query=${NIK})
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        // Update nama secara otomatis
                                        document.querySelector('input[name="Name"]').value = data[0].Name;
                                    } else {
                                        alert('NIK tidak valid!');
                                    }
                                });
                            });
                    });
    </script>

    <datalist id="nik-suggestions"></datalist>

</x-filament::page>


