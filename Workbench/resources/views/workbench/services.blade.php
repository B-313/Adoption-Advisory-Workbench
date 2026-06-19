@extends('layouts.workbench')
@section('title', 'Services Management')
@section('view', 'services')

@section('content')
<div class="ph">
  <div><h2>Services Management</h2><p>Advisory products the practice sells to SME clients</p></div>
  <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#new-svc">+ Add Service</button>
</div>

<div class="collapse mb-3" id="new-svc"><div class="card-p cb">
  <form onsubmit="addService(event)">
    <div class="row g-2">
      <div class="col-sm-3"><label class="form-label-sm">Name</label><input id="sv-name" class="form-control form-control-sm" required></div>
      <div class="col-sm-4"><label class="form-label-sm">Description</label><input id="sv-desc" class="form-control form-control-sm"></div>
      <div class="col-sm-2"><label class="form-label-sm">Duration</label><input id="sv-dur" class="form-control form-control-sm" placeholder="e.g. 2 weeks"></div>
      <div class="col-sm-2"><label class="form-label-sm">Price (£)</label><input id="sv-price" type="number" min="0" step="0.01" class="form-control form-control-sm"></div>
      <div class="col-sm-1 d-flex align-items-end"><button class="btn btn-primary btn-sm w-100">Add</button></div>
    </div>
  </form>
</div></div>

<div class="row g-3" id="svc-grid"></div>
@endsection
