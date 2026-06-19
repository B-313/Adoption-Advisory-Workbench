<?php

namespace App\Http\Controllers;

use App\Support\Adoption;

class WorkbenchController extends Controller
{
    private function reference(): array
    {
        return [
            'sectors'  => Adoption::SECTORS,
            'bench'    => Adoption::BENCH,
            'sizes'    => Adoption::SIZES,
            'states'   => Adoption::STATES,
            'techs'    => Adoption::TECHS,
            'barriers' => Adoption::BARRIERS,
            'phases'   => Adoption::PHASES,
        ];
    }

    public function dashboard() { return view('workbench.dashboard', $this->reference()); }
    public function services()  { return view('workbench.services',  $this->reference()); }
    public function customers()  { return view('workbench.customers', $this->reference()); }
    public function emails()     { return view('workbench.emails',    $this->reference()); }
    public function tracking()   { return view('workbench.tracking',  $this->reference()); }
    public function insights()   { return view('workbench.insights',  $this->reference()); }
    public function support()    { return view('workbench.support',   $this->reference()); }
}
