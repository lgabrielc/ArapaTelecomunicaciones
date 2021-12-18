<x-jet-dialog-modal wire:model='vermodalcrearservicio'>
    <x-slot name="title">
        Agregar Servicio
    </x-slot>
    <x-slot name="content">
        <div class="flex flex-col w-full md:flex-row">
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Nombre del Cliente
                </label>
                <x-jet-input type="text" class="block w-full px-6" value="{{ $nombre }}&nbsp;{{ $apellido }}"
                    disabled />
            </div>
        </div>
        <div class="flex flex-col w-full md:flex-row">
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Fecha de Registro
                </label>
                <x-jet-input type="date" class="block w-full px-6" wire:model='fechainicio' />
            </div>
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Fecha de Pago
                </label>
                <x-jet-input type="date" class="block w-full px-6" wire:model='fechavencimiento'
                    wire:change='changedatavencimiento' />
            </div>
        </div>
        <div class="flex flex-col w-full md:flex-row">

            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Fecha de Corte
                </label>
                <x-jet-input type="date" class="block w-full px-6" wire:model='fechacorte' />
            </div>
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Tipo de Servicio
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model='tiposervicio'
                    wire:change='cambiartipodeservicio' required>
                    <option value="" selected>-Escoja el Tipo de Servicio-</option>
                    <option value="Antena">Antena</option>
                    <option value="Fibra">Fibra Optica</option>
                </select>
                @error('tiposervicio')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex flex-col w-full md:flex-row">
            @if ($tiposervicio == 'Fibra' && $vermodalcrearservicio == true && $vermodalfibra == false)
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    DataCenter
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model="datacenterid"
                    wire:change='generarolts' required>
                    <option value="" selected>-Escoja una DataCenter-</option>
                    @foreach ($totaldatacenters as $datacenter)
                    <option value="{{ $datacenter->id }}">{{ $datacenter->nombre }}</option>
                    @endforeach
                </select>
                @error('datacenterid')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            @endif
            @if (is_numeric($datacenterid) && $vermodalcrearservicio == true && $vermodalfibra == false)
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Olt
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model="oltid"
                    wire:change="olttarjetarelacion" required>
                    <option value="" selected>-Escoja una Olt-</option>
                    @foreach ($datacenterselect->olts as $olt)
                    <option value="{{ $olt->id }}">{{ $olt->nombre }}</option>
                    @endforeach
                </select>
                @error('datacenterid')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            @endif
        </div>
        <div class="flex flex-col w-full md:flex-row">
            @if (is_numeric($oltid) && is_numeric($datacenterid) && $vermodalcrearservicio == true && $vermodalfibra
            == false)
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Tarjeta
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model="tarjetaid"
                    wire:change='tarjetagponrelacion'>
                    <option value="" selected>-Escoja una Tarjeta-</option>
                    @foreach ($olttarjetarelacionado->tarjetas as $tarjeta)
                    <option value="{{ $tarjeta->id }}">{{ $tarjeta->nombre }}&nbsp,&nbsp
                        Slots:{{ $tarjeta->slots }}</option>
                    @endforeach
                </select>
                @error('datacenterid')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            @endif
            @if (is_numeric($tarjetaid) && is_numeric($oltid) && is_numeric($datacenterid)&& $vermodalcrearservicio ==
            true && $vermodalfibra == false)
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Gpon
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model="gponid"
                    wire:change='gponnaprelacion' required>
                    <option value="" selected>-Escoja un Gpon-</option>
                    @foreach ($tarjetagponrelacionado->gpons as $gpon)
                    <option value="{{ $gpon->id }}">{{ $gpon->nombre }}&nbsp,&nbsp
                        Slots:{{ $gpon->slots }}</option>
                    @endforeach
                </select>
                @error('gponid')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            @endif
        </div>

        <div class="flex flex-col w-full md:flex-row">

            @if (is_numeric($tarjetaid) && is_numeric($oltid) && is_numeric($datacenterid) &&
            is_numeric($gponid)&& $vermodalcrearservicio == true && $vermodalfibra == false)
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Caja Nap
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model="napid" required>
                    <option value="" selected>-Escoja una Caja Nap-</option>
                    @foreach ($gponnaprelacionado->naps as $nap)
                    <option value="{{ $nap->id }}">{{ $nap->nombre }}&nbsp,&nbsp
                        Slots:{{ $nap->slots }}</option>
                    @endforeach
                </select>
                @error('napid')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            @endif
            @if (is_numeric($tarjetaid) && is_numeric($oltid) && is_numeric($datacenterid) &&
            is_numeric($gponid) && is_numeric($napid))
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Numero del Cliente
                </label>
                <x-jet-input type="text" class="block w-full px-6" placeholder="Ejm: 1001" wire:model='clientegpon' />
                @error('clientegpon')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            @endif
        </div>
        @if ($tiposervicio == 'Antena')
        <div class="flex flex-col w-full md:flex-row">
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Antena
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model.defer="antenaid">
                    <option value="" select>-Escoja una Antena-</option>
                    @foreach ($totalantenas as $antena)
                    <option value="{{ $antena->id }}">{{ $antena->nombre }}</option>
                    @endforeach
                </select>
                @error('antenaid')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Condición de la Antena:
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary"
                    wire:model.defer="condicionantena">
                    <option value="">-Escoja una opción-</option>
                    <option value="Propia">Propia</option>
                    <option value="Alquilada">Alquilada</option>
                </select>
                @error('condicionantena')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex flex-col w-full md:flex-row">
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Frecuencia
                </label>
                <x-jet-input type="text" class="block mt-1 w-full px-6" placeholder="Ejm: 5.8 GHZ"
                    wire:model.defer="frecuencia" />
                @error('frecuencia')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    IP
                </label>
                <x-jet-input type="text" class="block w-full px-6" placeholder="Ejm: 192.168.60.123"
                    wire:model.defer='ip' />
                @error('ip')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex flex-col w-full md:flex-row">
            <div class="w-full p-2">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Mac
                </label>
                <x-jet-input type="text" class="block w-full px-6" placeholder="Ejm: 80:2A:A8:B8:38:BC"
                    wire:model.defer='mac' />
                @error('mac')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @endif
        <div class="flex flex-col w-full md:flex-row">
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Estado:
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model.defer='estado'
                    required>
                    @foreach ($totalestados as $estados)
                    <option value={{$estados->id}} selected >{{$estados->nombre}}</option>
                    @endforeach
                </select>
                @error('estado')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="w-full p-2 ">
                <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">
                    Plan de Internet
                </label>
                <select class="border rounded-lg block mt-1 w-full px-6 border-secondary" wire:model.defer='plan'
                    required>
                    <option value="" select>-Escoja una Plan de Internet-</option>
                    @foreach ($totalplanes as $plan)
                    <option value="{{ $plan->id }}">
                        {{ $plan->nombre }}&nbsp{{ $plan->descarga }} </option>
                    @endforeach
                </select>
                @error('plan')
                <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$set('vermodalcrearservicio',false)" wire:loading.attr="disabled">
            {{ __('Cancelar') }}
        </x-jet-secondary-button>
        @if ($this->tiposervicio == 'Fibra')
        <x-jet-danger-button wire:click="saveserviciofibra" wire:loading.attr="disabled">
            {{ __('Guardar Cambios') }}
        </x-jet-danger-button>
        @elseif ($this->tiposervicio == 'Antena')
        <x-jet-danger-button wire:click="saveservicioantena" wire:loading.attr="disabled">
            {{ __('Guardar Cambios') }}
        </x-jet-danger-button>
        @endif
    </x-slot>
</x-jet-dialog-modal>