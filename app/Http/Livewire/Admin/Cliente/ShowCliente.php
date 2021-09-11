<?php

namespace App\Http\Livewire\Admin\Cliente;

use App\Models\Antena;
use App\Models\Cliente;
use App\Models\Datacenter;
use App\Models\Estado;
use App\Models\Gpon;
use App\Models\Olt;
use App\Models\Plan;
use App\Models\Servicio;
use App\Models\Tarjeta;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCliente extends Component
{


    use WithPagination;
    public $sort = 'id';
    public $direction = 'desc';
    public $tiposervicio, $VerServicio;
    public $search, $totalcontar, $totalestados, $totalplanes, $totalantenas, $totaldatacenters;
    //Editar Cliente
    public $EditarCliente, $EditarNombre, $EditarID, $EditarApellido, $EditarDNI, $EditarCorreo;
    //Agregar Servicio
    public $AgregarServicio, $IDClienteServicio, $NombreClienteServicio, $ApellidoClienteServicio;
    public $fechaInicio, $fechaVencimiento, $fechaCorte, $condicionantena, $mac, $ip, $frecuencia, $antenarelacionada;
    public $fechaInicioV, $fechaVencimientoV, $fechaCorteV;
    public $gponrelacionado, $clientegpon, $estado, $plannuevo, $olttarjetarelacionado, $tarjetagponrelacionado,$gponnaprelacionado;
    public $datacenterid, $datacenterselect, $oltid, $tarjetaid,$gponid,$napid;
    //Datos Cliente
    public $nombre, $apellido, $dni, $correo;
    //Agregar Plan
    public $NombrePlan, $VelocidadDescarga, $VelocidadSubida, $PrecioPlan;
    public $isDisabled = true;
    public $agregarplan;
    public $cant = '5';
    public $open = false;

    protected $rules = [
        'nombre' => 'required|min:5|max:50',
        'apellido' => 'required|min:3|max:50',
        'dni' => 'required|size:8',
        'correo' => 'required|email|min:3|max:30',
    ];
    public function gponnaprelacion()
    {
        if (isset($this->gponidnuevo)) {
            $this->gponid = $this->gponidnuevo;
        }
        if (is_numeric($this->gponid)) {
            $this->gponnaprelacionado = Gpon::find($this->gponid);
        }
    }
    public function tarjetagponrelacion()
    {
        if (isset($this->tarjetaidnuevo)) {
            $this->tarjetaide = $this->tarjetaidnuevo;
            $this->tarjetaid = $this->tarjetaidnuevo;
            // $this->reset('gponidnuevo', 'gponid');
        }
        if (is_numeric($this->tarjetaid)) {
            $this->tarjetagponrelacionado = Tarjeta::find($this->tarjetaid);
            // $this->reset('gponidnuevo', 'gponid');
        }
    }
    public function olttarjetarelacion()
    {
        if (isset($this->oltidnuevo)) {
            $this->oltid = $this->oltidnuevo;
        }
        if (is_numeric($this->oltid)) {
            $this->olttarjetarelacionado = Olt::find($this->oltid);
            $this->reset('tarjetaid');
        }
    }

    public function mount()
    {
        $this->totalcontar = Cliente::count();
        $this->fechaInicio = date('Y-m-d');
        $this->fechaVencimiento = date("Y-m-d", strtotime($this->fechaInicio . "+ 1 month"));
        $this->fechaCorte = date("Y-m-d", strtotime($this->fechaVencimiento . "+ 3 days"));
        $this->totalplanes = Plan::all();
        $this->totalestados = Estado::all();
        $this->totalantenas = Antena::all();
        $this->totaldatacenters = DataCenter::where('estado_id', "=", '1')->get();
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
    public function edit(Cliente $cliente)
    {
        $this->EditarCliente = $cliente;
        $this->EditarID = $this->EditarCliente->id;
        $this->EditarNombre = $this->EditarCliente->nombre;
        $this->EditarApellido = $this->EditarCliente->apellido;
        $this->EditarDNI = $this->EditarCliente->dni;
        $this->EditarCorreo = $this->EditarCliente->correo;
    }
    public function saveplan()
    {
        $this->validate([
            'NombrePlan' => 'required|min:5|max:50',
            'VelocidadDescarga' => 'required|min:3|max:15',
            'VelocidadSubida' => 'required|min:3|max:15',
            'PrecioPlan' => 'required|numeric',
        ]);
        $estado = 1;
        $NuevoPlan = Plan::create([
            'nombre' => $this->NombrePlan,
            'descarga' => $this->VelocidadDescarga,
            'subida' => $this->VelocidadSubida,
            'precio' => $this->PrecioPlan,
            'estado_id' => $estado,
        ]);

        $this->totalplanes = Plan::all();
        $this->totalcontar = Cliente::count();
        $this->reset(['NombrePlan', 'VelocidadDescarga', 'VelocidadSubida', 'PrecioPlan']);
        $this->emit('cerrarModalCrearPlan');
        $this->emit('alert', 'El Plan se creo satisfactoriamente');
    }
    public function resetearcampos($value)
    {
        if ($value == 'Antena') {
            $this->reset('gponrelacionado', 'clientegpon', 'gponrelacionado');
        } elseif ($value == 'Fibra') {
            $this->reset('condicionantena', 'mac', 'ip', 'frecuencia', 'antenarelacionada');
        }
    }
    public function saveservicioantena()
    {
        $this->validate([
            'fechaInicio' => 'required|date_format:Y-m-d',
            'fechaVencimiento' => 'required|date_format:Y-m-d|after:fechaInicio',
            'fechaCorte' => 'required|date_format:Y-m-d|after:fechaVencimiento',
            'tiposervicio' => 'required',
            'condicionantena' => 'required',
            'mac' => 'required|size:17',
            'ip' => 'required|ipv4',
            'frecuencia' => 'required|min:4|max:9',
            'antenarelacionada' => 'required',
            'clientegpon' => 'nullable',
            'gponrelacionado' => 'nullable',
            'estado' => 'required',
            'plannuevo' => 'required',
        ]);

        $nuevoServicio = Servicio::create([
            'fechaInicio' => $this->fechaInicio,
            'fechaVencimiento' => $this->fechaVencimiento,
            'fechaCorte' => $this->fechaCorte,
            'tiposervicio' => $this->tiposervicio,
            'condicionAntena' => $this->condicionantena,
            'mac' => $this->mac,
            'ip' => $this->ip,
            'frecuencia' => $this->frecuencia,
            'antena_id ' => $this->antenarelacionada,
            'clientegpon' => $this->clientegpon,
            'gponrelacionado' => $this->gponrelacionado,
            'estado_id' => $this->estado,
            'plan_id' => $this->plannuevo,
            'cliente_id' => $this->IDClienteServicio,
        ]);
        $this->reset(['tiposervicio', 'condicionantena', 'mac', 'ip', 'frecuencia']);
        $this->emit('cerrarModalCrearServicio');
        $this->emit('alert', 'El Servicio se añadio satisfactoriamente');
    }
    public function saveserviciofibra()
    {
        $this->validate([
            'fechaInicio' => 'required|date_format:Y-m-d',
            'fechaVencimiento' => 'required|date_format:Y-m-d|after:fechaInicio',
            'fechaCorte' => 'required|date_format:Y-m-d|after:fechaVencimiento',
            'tiposervicio' => 'required',
            'condicionantena' => 'nullable',
            'mac' => 'nullable',
            'ip' => 'nullable',
            'frecuencia' => 'nullable',
            'antenarelacionada' => 'nullable',
            'gponrelacionado' => 'required',
            'clientegpon' => 'required|numeric',
            'gponrelacionado' => 'required',
            'estado' => 'required',
            'plannuevo' => 'required',
        ]);

        $nuevoServicio = Servicio::create([
            'fechaInicio' => $this->fechaInicio,
            'fechaVencimiento' => $this->fechaVencimiento,
            'fechaCorte' => $this->fechaCorte,
            'tiposervicio' => $this->tiposervicio,
            'condicionAntena' => $this->condicionantena,
            'mac' => $this->mac,
            'ip' => $this->ip,
            'frecuencia' => $this->frecuencia,
            'antena_id ' => $this->antenarelacionada,
            'clientegpon' => $this->clientegpon,
            'gponrelacionado' => $this->gponrelacionado,
            'estado_id' => $this->estado,
            'plan_id' => $this->plannuevo,
            'cliente_id' => $this->IDClienteServicio,
        ]);
    }
    public function agregarservicio(Cliente $cliente)
    {
        $this->AgregarServicio = $cliente;
        $this->IDClienteServicio = $this->AgregarServicio->id;
        $this->NombreClienteServicio = $this->AgregarServicio->nombre;
        $this->ApellidoClienteServicio = $this->AgregarServicio->apellido;
        $this->reset('fechaInicio', 'fechaVencimiento', 'fechaCorte');
        $this->fechaInicio = date('Y-m-d');
        $this->fechaVencimiento = date("Y-m-d", strtotime($this->fechaInicio . "+ 1 month"));
        $this->fechaCorte = date("Y-m-d", strtotime($this->fechaVencimiento . "+ 3 days"));
    }
    public function verservicio(Cliente $cliente)
    {
        $this->VerServicio = $cliente;
        $this->NombreClienteServicio = $this->VerServicio->nombre;
        $this->ApellidoClienteServicio = $this->VerServicio->apellido;
        $this->fechaInicioV = $this->VerServicio->servicio->fechaInicio;
        $this->fechaVencimientoV = $this->VerServicio->servicio->fechaVencimiento;
        $this->fechaCorteV = $this->VerServicio->servicio->fechaCorte;
        $this->tiposervicio = $this->VerServicio->servicio->tiposervicio;
    }
    public function update()
    {
        $this->validate([
            'EditarNombre' => 'required|min:5|max:50',
            'EditarApellido' => 'required|min:5|max:50',
            'EditarDNI' => 'required|size:8',
            'EditarCorreo' => 'required|email|min:3|max:30',
        ]);
        if ($this->EditarID) {
            $updAntena = Cliente::find($this->EditarID);
            $updAntena->update([
                'nombre' => $this->EditarNombre,
                'apellido' => $this->EditarApellido,
                'dni' => $this->EditarDNI,
                'correo' => $this->EditarCorreo,
            ]);
        }
        $this->totalcontar = Cliente::count();
        $this->emit('cerrarModalEditar');
        $this->emit('alert', 'El Cliente se actualizo satisfactoriamente');
    }
    public function save()
    {
        $this->validate();
        $tower = Cliente::create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'dni' => $this->dni,
            'correo' => $this->correo,
        ]);

        $this->totalcontar = Cliente::count();
        $this->reset(['nombre', 'apellido', 'dni', 'correo']);
        $this->emit('cerrarModalCrear');
        $this->emit('alert', 'El cliente se creo satisfactoriamente');
    }
    // public function savetipoantena()
    // {
    //     $this->validate([
    //         'crearnuevotipoantena' => 'required|min:5|max:30',
    //     ]);
    //     $newTipoAntena = TipoAntena::create([
    //         'nombre' => $this->crearnuevotipoantena,
    //     ]);

    //     $this->tipoantenas = TipoAntena::all();
    //     $this->totalcontar = Antena::count();
    //     $this->emit('cerrarModalCrearTipoAntena');
    //     $this->emit('alert', 'El Tipo de Antena se creo satisfactoriamente');
    // }
    public function actualizarfechas($value)
    {
        // $this->fechainicio = date('Y-m-d');
        $this->fechaVencimiento = date("Y-m-d", strtotime($value . "+ 1 month"));
        $this->fechaCorte = date("Y-m-d", strtotime($this->fechaVencimiento . "+ 3 days"));
    }
    public function actualizarfechas2($value)
    {
        $this->fechacorte = date("Y-m-d", strtotime($value . "+ 3 days"));
    }
    public function generarolts()
    {
        if (isset($this->datacenteride)) {
            $this->datacenterid = $this->datacenteride;
        }
        if (is_numeric($this->datacenterid)) {
            $this->datacenterselect = Datacenter::find($this->datacenterid);
            // $this->reset('tarjetaid', 'oltid', 'olttarjetarelacionado', 'tarjetagponrelacionado', 'oltidnuevo', 'tarjetaidnuevo');
        } else {
            // $this->reset('oltid', 'tarjetaid', 'datacenterid', 'olttarjetarelacionado', 'tarjetagponrelacionado');
        }
    }
    public function render()
    {
        $clientes = Cliente::where('nombre', 'like', '%' . $this->search . '%')
            ->orwhere('apellido', 'like', '%' . $this->search . '%')
            ->orwhere('dni', 'like', '%' . $this->search . '%')
            ->orwhere('correo', 'like', '%' . $this->search . '%')
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->cant);
        return view('livewire.admin.cliente.show-cliente', compact('clientes'));
    }

    public function delete($id)
    {
        Cliente::where('id', $id)->delete();
        $this->totalcontar = Cliente::count();

        // $Eliminarphone = Telefono::where('telefono_id', $id)->delete();
        // $Eliminarphone = Direccion::where('direccion_id', $id)->delete();
        $this->identificador = rand();
        // $servidorEliminar->delete();
    }
}
