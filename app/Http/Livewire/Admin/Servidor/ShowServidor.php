<?php

namespace App\Http\Livewire\Admin\Servidor;

use App\Models\Direccion;
use App\Models\Servidor;
use App\Models\Telefono;
use Livewire\Component;
use Livewire\WithPagination;

class ShowServidor extends Component

{
    use WithPagination;
    public $search, $totalservidores, $nombre, $ipEntrada, $ipSalida;
    public $sort = 'id';
    public $direction = 'desc';
    public $servidorEdit, $nombreEdit, $ipEntradaEdit, $ipSalidaEdit, $servidorid;
    public $cant = '5';
    public $open = false;
    public $identificador;
    public $prueba;

    protected $rules = [
        'nombre' => 'required|min:5|max:30',
        'ipEntrada' => 'required|ipv4',
        'ipSalida' => 'required|ipv4',
    ];
    public function mount()
    {
        $this->identificador = rand();
        $this->totalservidores = Servidor::count();
    }
    public function order($sort)
    {
        if ($sort == $this->sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
        }
    }
    public function edit(Servidor $servidor)
    {
        $this->servidorEdit = $servidor;
        $this->servidorid = $this->servidorEdit->id;
        $this->nombreEdit = $this->servidorEdit->nombre;
        $this->ipEntradaEdit = $this->servidorEdit->ipEntrada;
        $this->ipSalidaEdit = $this->servidorEdit->ipSalida;
    }
    public function update()
    {
        $this->validate([
            'nombreEdit' => 'required|min:5|max:30',
            'ipEntradaEdit' => 'required|ipv4',
            'ipSalidaEdit' => 'required|ipv4',
        ]);
        
        if ($this->servidorid) {
            $updServidor = Servidor::find($this->servidorid);
            $updServidor->update([
                'nombre' => $this->nombreEdit,
                'ipEntrada' => $this->ipEntradaEdit,
                'ipSalida' => $this->ipSalidaEdit,
            ]);
            $this->reset(['nombreEdit', 'ipEntradaEdit', 'ipSalidaEdit']);
        }
        $this->totalservidores = Servidor::count();

        $this->emit('cerrarModalEditarServidor');
        $this->emit('alert', 'El servidor se actualizo satisfactoriamente');
    }
    public function save()
    {
        $this->validate();
        Servidor::create([
            'nombre' => $this->nombre,
            'ipEntrada' => $this->ipEntrada,
            'ipSalida' => $this->ipSalida,
        ]);
        $this->totalservidores = Servidor::count();
        $this->identificador = rand();
        $this->reset(['nombre', 'ipEntrada', 'ipSalida']);
        $this->emit('cerrarModalCrearServidor');
        $this->emit('alert', 'El servidor se creo satisfactoriamente');
    }
    public function render()
    {
        $servidores = Servidor::where('nombre', 'like', '%' . $this->search . '%')
            ->orwhere('ipEntrada', 'like', '%' . $this->search . '%')
            ->orwhere('ipSalida', 'like', '%' . $this->search . '%')
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->cant);
        return view('livewire.admin.servidor.show-servidor', compact('servidores'));
    }
    public function delete($id)
    {
        $this->prueba = $id;
        Servidor::where('id', $id)->delete();
        $this->totalservidores = Servidor::count();
        $this->identificador = rand();
        $Eliminarphone = Telefono::where('telefono_id', $id)->where('telefono_type','App\Models\Torre')->delete();
        $Eliminarphone = Direccion::where('direccion_id', $id)->where('direccion_type','App\Models\Torre')->delete();

        // $servidorEliminar->delete();
    }
}