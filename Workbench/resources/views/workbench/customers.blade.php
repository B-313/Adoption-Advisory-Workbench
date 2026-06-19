@extends('layouts.workbench')
@section('title', 'Customer Database')
@section('view', 'customers')

@section('content')
<div class="ph">
  <div><h2>Customer Database</h2><p>SME clients with sector, size band and AI adoption state.</p></div>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#new-cust">+ Add Customer</button>
    <button class="btn btn-sm btn-gold" onclick="exportExcel('customers')">Export Excel</button>
  </div>
</div>

<div class="collapse mb-3" id="new-cust"><div class="card-p cb">
  <form onsubmit="addCustomer(event)">
    <div class="row g-2">
      <div class="col-sm-3"><label class="form-label-sm">Company</label><input id="cu-name" class="form-control form-control-sm" required></div>
      <div class="col-sm-3"><label class="form-label-sm">Contact email</label><input id="cu-email" type="email" class="form-control form-control-sm" required></div>
      <div class="col-sm-3"><label class="form-label-sm">Sector</label><select id="cu-sector" class="form-select form-select-sm">
        @foreach ($sectors as $sec)<option>{{ $sec }}</option>@endforeach</select></div>
      <div class="col-sm-2"><label class="form-label-sm">Size</label><select id="cu-size" class="form-select form-select-sm">
        @foreach ($sizes as $sz)<option>{{ $sz }}</option>@endforeach</select></div>
      <div class="col-sm-1 d-flex align-items-end"><button class="btn btn-primary btn-sm w-100">Add</button></div>
    </div>
  </form>
</div></div>

<div class="d-flex gap-2 mb-3 flex-wrap">
  <input id="cust-search" class="form-control form-control-sm" style="width:220px;" placeholder="Search customers…" oninput="renderCustomers()">
  <select id="cust-fStatus" class="form-select form-select-sm" style="width:150px;" onchange="renderCustomers()">
    <option value="">All engagements</option><option>Active</option><option>Closed</option></select>
  <select id="cust-fState" class="form-select form-select-sm" style="width:160px;" onchange="renderCustomers()">
    <option value="">All adoption states</option>@foreach ($states as $st)<option>{{ $st }}</option>@endforeach</select>
  <select id="cust-fSector" class="form-select form-select-sm" style="width:200px;" onchange="renderCustomers()">
    <option value="">All sectors</option>@foreach ($sectors as $sec)<option>{{ $sec }}</option>@endforeach</select>
</div>

<div class="card-p"><div class="table-responsive"><table class="table table-hover mb-0">
  <thead><tr><th>Company</th><th>Sector</th><th>Size</th><th>Adoption State</th><th>Score</th><th>vs Sector</th><th>Engagement</th><th></th></tr></thead>
  <tbody id="cust-tbody"></tbody></table></div></div>
@endsection
