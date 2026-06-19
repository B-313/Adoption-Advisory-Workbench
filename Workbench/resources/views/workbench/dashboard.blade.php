@extends('layouts.workbench')
@section('title', 'Dashboard')
@section('view', 'dashboard')

@section('content')
<div class="ph">
  <div><h2>Dashboard</h2><p>Outreach, response and client portfolio overview</p></div>
  <button class="btn btn-sm btn-gold" onclick="seedDemo()">Load Demo Data</button>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="kpi"><div class="kv" id="kpi-sent">0</div><div class="kl">Scan Emails Sent</div><div class="ks" id="kpi-sent-sub">0 this week</div></div></div>
  <div class="col-6 col-lg-3"><div class="kpi gold"><div class="kv" id="kpi-rate">0%</div><div class="kl">Response Rate</div><div class="ks" id="kpi-rate-sub">0 of 0 responded</div></div></div>
  <div class="col-6 col-lg-3"><div class="kpi"><div class="kv" id="kpi-active">0</div><div class="kl">Active Customers</div><div class="ks" id="kpi-active-sub">0 closed</div></div></div>
  <div class="col-6 col-lg-3"><div class="kpi gold"><div class="kv" id="kpi-score">0</div><div class="kl">Avg Adoption Score</div><div class="ks">across active clients</div></div></div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-5"><div class="card-p"><div class="ch">
    <span id="outreach-title">Scan Outreach — Last 7 Days</span>
    <select class="form-select form-select-sm" style="width:auto;font-size:.75rem;" id="outreach-range" onchange="outreachChart()">
      <option value="7d">7 Days</option><option value="month">Month</option><option value="year">Year</option>
    </select></div><div class="cb"><canvas id="chart-outreach" height="200"></canvas></div></div></div>
  <div class="col-lg-3"><div class="card-p h-100"><div class="ch">Customers by State</div>
    <div class="cb" style="display:flex;align-items:center;justify-content:center;min-height:200px;"><canvas id="chart-state" width="180" height="180"></canvas></div></div></div>
  <div class="col-lg-4"><div class="card-p h-100"><div class="ch">Adoption vs Sector Benchmark</div><div class="cb"><canvas id="chart-bench-mini" height="200"></canvas></div></div></div>
</div>

<div class="row g-3">
  <div class="col-lg-8"><div class="card-p"><div class="ch">Recent Activity
    <a class="btn btn-sm btn-outline-primary" style="font-size:.72rem;padding:1px 8px;" href="{{ route('workbench.tracking') }}">Tracking</a></div>
    <div class="table-responsive"><table class="table table-hover mb-0">
      <thead><tr><th>When</th><th>Customer</th><th>Service</th><th>Stage</th><th>Sector</th></tr></thead>
      <tbody id="dash-activity"></tbody></table></div></div></div>
  <div class="col-lg-4"><div class="card-p"><div class="ch">Awaiting Response</div><div class="cb" id="dash-awaiting"></div></div></div>
</div>
@endsection
