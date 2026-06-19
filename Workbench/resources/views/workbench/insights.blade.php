@extends('layouts.workbench')
@section('title', 'Adoption Insights')
@section('view', 'insights')

@section('content')
<div class="ph"><div><h2>Adoption Insights</h2><p>Client portfolio benchmarked against UK AI adoption research by sector</p></div></div>

<div class="row g-3 mb-4" id="insights-kpis"></div>

<div class="row g-3 mb-4">
  <div class="col-lg-7"><div class="card-p"><div class="ch">Client Adoption vs Sector Benchmark</div><div class="cb"><canvas id="chart-bench" height="230"></canvas></div></div></div>
  <div class="col-lg-5"><div class="card-p"><div class="ch">AI Technology Mix (clients)</div><div class="cb"><canvas id="chart-tech" height="230"></canvas></div></div></div>
</div>

<div class="row g-3">
  <div class="col-lg-6"><div class="card-p"><div class="ch">Top Adoption Barriers</div><div class="cb"><canvas id="chart-barriers" height="200"></canvas></div></div></div>
  <div class="col-lg-6"><div class="card-p"><div class="ch">Readiness to Scale</div><div class="cb" id="readiness-scores"></div></div></div>
</div>
<div class="text-muted mt-3" style="font-size:.74rem;">Benchmarks: UK AI Adoption Research (gov.uk, 2025). Illustrative figures for demonstration.</div>
@endsection
