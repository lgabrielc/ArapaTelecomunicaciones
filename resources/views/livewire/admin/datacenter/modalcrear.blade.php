<button wire:click='resetcampos' type="button" class="btn btn-success rounded-pill" data-toggle="modal"
    data-target="#modalcrear">
    Crear Nuevo
</button>

<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalcrear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear nuevo Data Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">Nombre</label>
                    <input type="text" class="block text-sm py-3 px-4 rounded w-full border outline-none"
                        placeholder="Ejm: Data Center Villa Red" wire:model.defer="nombre">
                    @error('nombre') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">Ubicacion</label>
                    <input type="text" class="block text-sm py-3 px-4 rounded w-full border outline-none"
                        wire:model.defer="ubicacion" placeholder="Ejm: Villa El Salvador">
                    @error('ubicacion') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">Direccion</label>
                    <input type="text" class="block text-sm py-3 px-4 rounded w-full border outline-none"
                        wire:model.defer="direccion" placeholder="Ejm: Avenida 200 Millas Gr.4 Lt.4">
                    @error('direccion') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">Encargado</label>
                    <input type="text" class="block text-sm py-3 px-4 rounded w-full border outline-none"
                        wire:model.defer="encargado" placeholder="Ejm: Castiel Espinoza Rodriguez">
                    @error('encargado') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="block text-gray-500 font-bold mb-1 md:mb-0 pr-4">Estado</label>
                    <select class="block text-sm py-3 px-4 rounded w-full border outline-none" wire:model="estado_id">
                        @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                    @error('estado_id') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info close-btn rounded-pill" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="save" wire:loading.attr="disabled"
                    class="btn btn-danger close-modal rounded-pill">Guardar
                    Cambios</button>
            </div>
        </div>
    </div>
</div>